<?php

namespace App\Services\Courier;

use App\ApiCourier;
use Illuminate\Support\Facades\DB;

class SteadfastCourier
{
    private function curlPostWithRetry(string $url, array $data = [], array $credentials = [], int $maxAttempts = 2)
    {
        $attempt = 0;
        $response = null;
        $credentials = $credentials['data'];
        $headers = [
            'Api-Key: '.$credentials->config['api_key'],
            'Secret-Key: '.$credentials->config['secret_key'],
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
        $steadfast_data = [
            'invoice' => $order->invoice_id ?? null,
            'recipient_name' => $order->customer_name ?? null,
            'recipient_address' => $order->customer_address ?? null,
            'recipient_phone' => $order->customer_phone ?? null,
            'cod_amount' => (int) round($order->due ?? 0),
            'note' => $order->customer_note ?? null,
            'item_description' => $item_description,
            'total_lot' => $order->get_products->sum('qty'),
        ];
        $credentials = ApiCourier::where('courier_name', 'steadfast')->first();

        return $this->curlPostWithRetry(
            'https://portal.packzy.com/api/v1/create_order',
            $steadfast_data,
            ['data' => $credentials],
            2 // max 2 attempts
        );
    }

    // webhook
    public function webhook(array $object)
    {
        $credentials = ApiCourier::where('courier_name', 'steadfast')->first();
        // delivered
        if ($object['status'] == 'delivered') {
            DB::table('orders')->where('consignment_id', $object['consignment_id'])->update([
                'status' => 1,
                'courier_error_msg' => null,
                'courier_status' => $object['notification_type'],
            ]);
        }
        // pending
        // elseif ($object['status'] == 'pending') {
        //     DB::table('orders')->where('consignment_id', $object['consignment_id'])->update([
        //         'status' => 1,
        //     ]);
        // }
        // partial_delivered
        // elseif ($object['status'] == 'partial_delivered') {
        //     DB::table('orders')->where('consignment_id', $object['consignment_id'])->update([
        //         'status' => 4,
        //     ]);
        // }
        // cancelled
        elseif ($object['status'] == 'cancelled') {
            DB::table('orders')->where('consignment_id', $object['consignment_id'])->update([
                'status' => 10,
                'courier_error_msg' => null,
                'courier_status' => $object['notification_type'],
            ]);
        }
    }
}
