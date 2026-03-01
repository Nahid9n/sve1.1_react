<?php

namespace App\Services\Courier;

use App\ApiCourier;
use Illuminate\Support\Facades\DB;

class PathaoCourier
{
    private function curlPost(string $url, array $payload = [], array $headers = [])
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);

            return ['status' => false, 'message' => $error];
        }
        curl_close($curl);

        return json_decode($response, true);
    }

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

            if (isset($response['code']) && $response['code'] == 200) {
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
            return $p->get_product->name.' x '.$p->qty;
        })->implode(', ');

        $credentials = ApiCourier::where('courier_name', 'pathao')->first();

        $payload = [
            'store_id' => $credentials->config['store_id'],
            'merchant_order_id' => $order->invoice_id,
            'recipient_name' => $order->customer_name,
            'recipient_phone' => $order->customer_phone,
            'recipient_address' => $order->customer_address,
            'recipient_city' => $order->courier_city_id ?? null,
            'recipient_zone' => $order->courier_zone_id ?? null,
            'delivery_type' => 48,
            'item_type' => 2,
            'special_instruction' => $order->customer_note,
            'item_quantity' => $order->get_products->sum('qty'),
            'item_weight' => 0.5,
            'amount_to_collect' => (int) round($order->due),
            'item_description' => $item_description,
        ];

        $headers = [
            "Authorization: Bearer {$credentials->config['access_token']}",
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        return $this->curlPostWithRetry(
            'https://api-hermes.pathao.com/aladdin/api/v1/orders',
            $payload,
            $headers,
            2 // max 2 attempts
        );
    }

    public function addressParser(array $data)
    {
        $credentials = ApiCourier::where('courier_name', 'pathao')->first();
        $headers = [
            "Authorization: Bearer {$credentials->config['access_token']}",
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        return $this->curlPost(
            'https://merchant.pathao.com/api/v1/address-parser',
            $data,
            $headers
        );
    }

    public function generateApiKey(array $data)
    {
        $d = $data['data'];

        $payload = [
            'client_id' => $d->config['client_id'],
            'client_secret' => $d->config['client_secret'],
            'username' => $d->username,
            'password' => $d->password,
            'grant_type' => 'password',
        ];

        $url = rtrim($d->base_url, '/').'/aladdin/api/v1/issue-token';

        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        return $this->curlPost($url, $payload, $headers);
    }

    // webhook
    public function webhook(array $object)
    {
        $credentials = ApiCourier::where('courier_name', 'pathao')->first();

        // Check for store_id exist
        if (! isset($object['store_id']) || $object['store_id'] != $credentials->config['store_id']) {
            return;
        }

        // Safety: check event exist
        if (! isset($object['event'])) {
            return;
        }

        // Order created
        if ($object['event'] == 'order.created') {
            DB::table('orders')->where('invoice_id', $object['merchant_order_id'])->update([
                'courier_status' => 'Order Created',
                'consignment_id' => $object['consignment_id'],
                'courier_error_msg' => null,
            ]);
        }

        // Order updated
        elseif ($object['event'] == 'order.updated') {
            DB::table('orders')->where('consignment_id', $object['consignment_id'])->update([
                'courier_status' => $object['order_status'],
            ]);
        }

        // Pickup requested
        elseif ($object['event'] == 'order.pickup-requested') {
            DB::table('orders')->where('consignment_id', $object['consignment_id'])->update([
                'courier_status' => $object['order_status'],
            ]);
        }

        // Assigned for pickup
        elseif ($object['event'] == 'order.assigned-for-pickup') {
            DB::table('orders')->where('consignment_id', $object['consignment_id'])->update([
                'courier_status' => $object['order_status'],
            ]);
        }

        // Picked
        elseif ($object['event'] == 'order.picked') {
            DB::table('orders')->where('consignment_id', $object['consignment_id'])->update([
                'courier_status' => $object['order_status'],
            ]);
        }

        // Pickup failed
        elseif ($object['event'] == 'order.pickup-failed') {
            DB::table('orders')->where('consignment_id', $object['consignment_id'])->update([
                'courier_status' => $object['order_status'],
            ]);
        }

        // Pickup cancelled
        elseif ($object['event'] == 'order.pickup-cancelled') {
            DB::table('orders')->where('consignment_id', $object['consignment_id'])->update([
                'courier_status' => $object['order_status'],
            ]);
        }

        // Sorting hub
        elseif ($object['event'] == 'order.at-the-sorting-hub') {
            DB::table('orders')->where('consignment_id', $object['consignment_id'])->update([
                'courier_status' => $object['order_status'],
                // 'status'         => 6,
            ]);
        }

        // In transit
        elseif ($object['event'] == 'order.in-transit') {
            DB::table('orders')->where('consignment_id', $object['consignment_id'])->update([
                'courier_status' => $object['order_status'],
            ]);
        }

        // Last mile hub
        elseif ($object['event'] == 'order.received-at-last-mile-hub') {
            DB::table('orders')->where('consignment_id', $object['consignment_id'])->update([
                'courier_status' => $object['order_status'],
            ]);
        }

        // Assigned for delivery
        elseif ($object['event'] == 'order.assigned-for-delivery') {
            DB::table('orders')->where('consignment_id', $object['consignment_id'])->update([
                'courier_status' => $object['order_status'],
            ]);
        }

        // Delivered
        elseif ($object['event'] == 'order.delivered') {
            DB::table('orders')->where('consignment_id', $object['consignment_id'])->update([
                'status' => 9,
                'courier_status' => $object['order_status'],
            ]);
        }

        // Partial delivery
        elseif ($object['event'] == 'order.partial-delivery') {
            DB::table('orders')->where('consignment_id', $object['consignment_id'])->update([
                'courier_status' => $object['order_status'],
                'courier_reason_msg' => $object['reason'],
            ]);
        }

        // Returned
        elseif ($object['event'] == 'order.returned') {
            DB::table('orders')->where('consignment_id', $object['consignment_id'])->update([
                'status' => 11,
                'courier_status' => $object['order_status'],
                'courier_reason_msg' => $object['reason'],
            ]);
        }

        // Delivery failed
        elseif ($object['event'] == 'order.delivery-failed') {
            DB::table('orders')->where('consignment_id', $object['consignment_id'])->update([
                'courier_status' => $object['order_status'],
                'courier_reason_msg' => $object['reason'],
            ]);
        }

        // On hold
        elseif ($object['event'] == 'order.on-hold') {
            DB::table('orders')->where('consignment_id', $object['consignment_id'])->update([
                'courier_status' => $object['order_status'],
                'courier_reason_msg' => $object['reason'],
            ]);
        }

        // Paid
        elseif ($object['event'] == 'order.paid') {
            DB::table('orders')->where('consignment_id', $object['consignment_id'])->update([
                'courier_status' => $object['order_status'],
                'courier_reason_msg' => $object['invoice_id'],
                'payment_status' => 1,
            ]);
        }

        // Paid return
        elseif ($object['event'] == 'order.paid-return') {
            DB::table('orders')->where('consignment_id', $object['consignment_id'])->update([
                'courier_status' => $object['order_status'],
                'courier_reason_msg' => $object['reason'],
            ]);
        }

        // Exchanged
        elseif ($object['event'] == 'order.exchanged') {
            DB::table('orders')->where('consignment_id', $object['consignment_id'])->update([
                'courier_status' => $object['order_status'],
                'courier_reason_msg' => $object['reason'],
            ]);
        }

        // No return needed
    }
}
