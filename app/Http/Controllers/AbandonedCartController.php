<?php

namespace App\Http\Controllers;

use App\AbandonedCart;
use App\Order;
use App\OrderProduct;
use App\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AbandonedCartController extends Controller
{
    public function index(Request $request)
    {
        $query = AbandonedCart::query();

        if ($request->has('search') && trim($request->search) !== '') {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('customer_phone', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        $data = $query
            ->latest()
            ->paginate(20)
            ->withQueryString(); // 🔥 pagination এ search ধরে রাখবে

        return view('backEnd.admin.abandoned-cart.index', compact('data'));
    }
    public function updateNote(Request $request)
    {
        $request->validate([
            'id'   => 'required|exists:abandoned_carts,id',
            'note' => 'nullable|string'
        ]);

        AbandonedCart::where('id', $request->id)->update([
            'note' => $request->note
        ]);

        return response()->json([
            'success' => true,
            'note' => $request->note
        ]);
    }
    public function updateField(Request $request)
    {
        $request->validate([
            'id'    => 'required|exists:abandoned_carts,id',
            'field' => 'required|in:customer_name,customer_phone,customer_address',
            'value' => 'nullable|string'
        ]);

        $cart = AbandonedCart::find($request->id);
        $cart->{$request->field} = $request->value;
        $cart->save();

        return response()->json(['success' => true]);
    }
    public function createOrder($id)
    {
        $data = AbandonedCart::find($id);
        // order create
        $order = Order::create([
            'invoice_id' => invoice_generate(),
            'theme_id' => activeThemeData()->id,
            'order_date' => date('Y-m-d'),
            'customer_name' => $data->customer_name ?? '',
            'customer_phone' => $data->customer_phone ?? '',
            'customer_address' => $data->customer_address ?? '',
            'shipping_cost' => $data->shipping_cost ?? 0,
            'total' => $data->total,
            'status' => 1,
            'sub_total' => $data->subtotal,
            'discount' => $data->discount,
            'source' => 'Inc. Order',
            'customer_note' => $data->note,
        ]);
        // order items create
        foreach (json_decode($data->abandoned_item, true) as $item) {
            // dd($item);
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_sku'=> $item['sku'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'attributes' => $item['variant'] ?? null,
                'created_at'=> now(),
                'updated_at'=> now(),
            ]);
        }
        $data->delete();

        return redirect()->back()->with('success', 'Order Created Successfully');
    }
    public function bulkCreate(Request $request)
    {
        $ids = $request->ids;

        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No abandoned carts selected'
            ]);
        }

        DB::beginTransaction();

        try {

            // 🔥 Load all carts in one query
            $carts = AbandonedCart::whereIn('id', $ids)->get();

            foreach ($carts as $cart) {

                // ✅ Create Order
                $order = Order::create([
                    'invoice_id'       => invoice_generate(),
                    'theme_id'         => activeThemeData()->id,
                    'order_date'       => now()->format('Y-m-d'),
                    'customer_name'    => $cart->customer_name ?? '',
                    'customer_phone'   => $cart->customer_phone ?? '',
                    'customer_address' => $cart->customer_address ?? '',
                    'shipping_cost'    => $cart->shipping_cost ?? 0,
                    'sub_total'        => $cart->subtotal ?? 0,
                    'discount'         => $cart->discount ?? 0,
                    'total'            => $cart->total ?? 0,
                    'status'           => 1,
                    'source'           => 'Inc. Order',
                    'customer_note'    => $cart->note,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);

                // 🔥 Prepare bulk order items
                $items = [];
                $abandonedItems = json_decode($cart->abandoned_item, true);

                if (is_array($abandonedItems)) {
                    foreach ($abandonedItems as $item) {
                        $items[] = [
                            'order_id'   => $order->id,
                            'product_id'=> $item['product_id'],
                            'product_sku'=> $item['sku'],
                            'qty'        => $item['qty'],
                            'price'      => $item['price'],
                            'attributes' => $item['variant'] ?? null,
                            'created_at'=> now(),
                            'updated_at'=> now(),
                        ];
                    }

                    // Bulk insert (SUPER FAST)
                    OrderProduct::insert($items);
                }

                $cart->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Orders created successfully'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Order creation failed',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    // delete abandoned cart
    public function delete($id)
    {
        $data = AbandonedCart::find($id);
        $data->delete();

        return redirect()->back()->with('success', 'Abandoned Cart Deleted Successfully');
    }
}
