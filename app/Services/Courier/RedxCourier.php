<?php

namespace App\Services\Courier;

use App\ApiCourier;
use App\RedxArea;
use Illuminate\Support\Facades\DB;

class RedxCourier
{
    private function curlPostWithRetry(string $url, array $data = [], array $credentials = [], int $maxAttempts = 2)
    {
        $attempt = 0;
        $response = null;
        $credentials = $credentials['data'];
        $headers = [
            'API-ACCESS-TOKEN: Bearer '.$credentials->config['api_token'],
            'Content-Type: application/json',
        ];
        while ($attempt < $maxAttempts) {
            $attempt++;

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
            ]);

            $result = curl_exec($curl);
            if (curl_errno($curl)) {
                $error = curl_error($curl);
                curl_close($curl);
                if ($attempt < $maxAttempts) {
                    sleep(2);
                } // wait 2s before retry

                continue;
            }
            curl_close($curl);
            $response = json_decode($result, true);
            if (isset($response['status']) && $response['status'] == 200) {
                return $response; // success
            }
            if ($attempt < $maxAttempts) {
                sleep(2);
            } // wait before next attempt
        }

        return $response ?? ['status' => false, 'message' => 'Failed after retries'];
    }

    public function sendOrder(array $orderData)
    {
        $order = $orderData['data'];
        $item_description = $order->get_products->map(function ($p) {
            return $p->get_product->name.' x '.$p->qty;
        })->implode(', ');
        $redxData = [
            'customer_name' => $order->customer_name ?? null,
            'customer_phone' => $order->customer_phone ?? null,
            'delivery_area' => RedxArea::where('parent_id', $order->courier_zone_id)->first()->name ?? null,
            'delivery_area_id' => $order->courier_zone_id ?? null,
            'customer_address' => $order->customer_address ?? null,
            'merchant_invoice_id' => $order->invoice_id ?? null,
            'cash_collection_amount' => (int) round($order->due ?? 0),
            'parcel_weight' => 0.5,
            'instruction' => $order->customer_note ?? null,
            'value' => $order->get_products->sum('qty'),
        ];
        // dd($redxData);
        $credentials = ApiCourier::where('courier_name', 'redx')->first();

        return $this->curlPostWithRetry(
            'https://openapi.redx.com.bd/v1.0.0-beta/parcel',
            $redxData,
            ['data' => $credentials],
            2 // max 2 attempts
        );
    }

    // webhook
    public function webhook(array $object)
    {
        $credentials = ApiCourier::where('courier_name', 'redx')->first();
        // delivered
        if ($object['status'] == 'delivered') {
            DB::table('orders')->where('tracking_id', $object['tracking_number'])->update([
                'status' => 9,
                'courier_error_msg' => null,
                'courier_status' => $object['status'],
            ]);
        } elseif ($object['status'] == 'returned') {
            DB::table('orders')->where('tracking_id', $object['tracking_number'])->update([
                'status' => 11,
                'courier_error_msg' => null,
                'courier_status' => $object['status'],
                'courier_reason_msg' => $object['message_en'],
            ]);
        }
    }
}
