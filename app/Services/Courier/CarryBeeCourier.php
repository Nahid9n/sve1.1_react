<?php

namespace App\Services\Courier;

use App\ApiCourier;
use App\CarryBeeCity;
use App\CarryBeeZone;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Can;

class CarryBeeCourier
{

    private function curlPostWithRetry(string $url, array $payload = [], array $headers = [], int $maxAttempts = 2)
    {
        $attempt = 0;
        $response = null;

        while ($attempt < $maxAttempts) {
            $attempt++;

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($payload),
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

            //  dd($response);

            if (isset($response['error']) && $response['error'] == false) {
                return $response; // success
            }

            if ($attempt < $maxAttempts) {
                sleep(2);
            } // wait before next attempt
        }

        return $response ?? ['status' => false, 'message' => 'Failed after retries'];
    }

    public function sendOrder($order)
    {
        // dd($order['data']);
        $order = $order['data'];
        $item_description = $order->get_products->map(function ($p) {
            return $p->get_product->name . ' x ' . $p->qty;
        })->implode(', ');

        $credentials = ApiCourier::where('courier_name', 'carrybee')->first();

        $payload = [
            'store_id' => (string) $credentials->config['store_id'],
            'merchant_order_id' => (string) $order->invoice_id,
            'delivery_type' => 1,
            'product_type' => 1,
            'recipient_phone' => (string) $order->customer_phone,
            'recipient_name' => $order->customer_name,
            'recipient_address' => $order->customer_address,
            'city_id' =>  CarryBeeCity::where('id', $order->courier_city_id)->first()->parent_id,
            'zone_id' =>  CarryBeeZone::where('id', $order->courier_zone_id)->first()->parent_id,
            'special_instruction' => $order->customer_note ?? '',
            'product_description' => $item_description ?? '',
            'item_weight' => 500, // MUST BE GRAMS
            'item_quantity' => max(1, (int) $order->get_products->sum('qty')),
            'collectable_amount' => max(0, (int) round($order->due)),
            'is_closed' => false,
        ];
        $headers = [
            'Content-Type: application/json',
            'Client-ID: ' . $credentials->config['client_id'],
            'Client-Secret: ' . $credentials->config['client_secret'],
            'Client-Context: ' . $credentials->config['client_context'],
        ];

        //dd($headers);
        //dd($payload);

        return $this->curlPostWithRetry(
            'https://developers.carrybee.com/api/v2/orders',
            $payload,
            $headers,
            2 // max 2 attempts
        );
    }

    // webhook
    public function webhook(array $object) {}
}
