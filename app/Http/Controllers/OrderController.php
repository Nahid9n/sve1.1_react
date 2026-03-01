<?php

namespace App\Http\Controllers;

use App\AccountTransaction;
use App\Admin;
use App\CarryBeeZone;
use App\Courier;
use App\Exports\OrderExport;
use App\Order;
use App\OrderActivity;
use App\OrderAssign;
use App\OrderNote;
use App\OrderProduct;
use App\PathaoCity;
use App\PathaoZone;
use App\PrintSetting;
use App\Product;
use App\ProductAttribute;
use App\ProductVariant;
use App\RedxArea;
use App\Services\Courier\PathaoCourier;
use App\Services\Courier\RedxCourier;
use App\Services\Courier\SteadfastCourier;
use App\Services\Courier\CarryBeeCourier;
use App\ShippingMethod;
use App\User;
use App\WebSettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $themeId = activeThemeData()->id;

        $courier_id     = $request->courier_id;
        $source         = $request->source;
        $payment_status = $request->payment_status;
        $search         = $request->input('query');
        $sts            = $request->status ?? 'pending';

        $statusMap = [
            'pending'        => 1,
            'confirm'        => 2,
            'processing'     => 3,
            'hold'           => 4,
            'printed'        => 5,
            'packaging'      => 6,
            'courier_entry'  => 7,
            'on_delivery'    => 8,
            'delivered'      => 9,
            'cancelled'      => 10,
            'returned'       => 11,
        ];

        /* ===============================
            BASE QUERY (single source)
        =============================== */
        $baseQuery = Order::where('theme_id', $themeId);

        /* ===============================
            FILTERS
        =============================== */
        $baseQuery->when(
            $courier_id,
            fn($q) =>
            $q->where('courier_id', $courier_id)
        );

        $baseQuery->when(
            $source,
            fn($q) =>
            $q->where('source', $source)
        );

        $baseQuery->when(
            $payment_status !== null && $payment_status !== '',
            fn($q) =>
            $q->where('payment_status', $payment_status)
        );


        $baseQuery->when($search, function ($q) use ($search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('customer_phone', 'LIKE', "%{$search}%")
                    ->orWhere('customer_name', 'LIKE', "%{$search}%")
                    ->orWhere('invoice_id', 'LIKE', "%{$search}%");
            });
        });

        /* ===============================
            DATE RANGE
        =============================== */
        $start = $end = null;

        if ($request->filled('date_range')) {
            $range = json_decode($request->date_range, true);

            if (!empty($range['start']) && !empty($range['end'])) {
                $start = Carbon::parse($range['start'])->startOfDay();
                $end   = Carbon::parse($range['end'])->endOfDay();

                $baseQuery->whereBetween('created_at', [$start, $end]);
            }
        }

        /* ===============================
            STATUS COUNTS (cards)
        =============================== */
        $statusCounts = (clone $baseQuery)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $data = [
            'total_order'                 => $statusCounts->sum(),
            'total_pending_order'         => $statusCounts[1]  ?? 0,
            'total_confirm_order'         => $statusCounts[2]  ?? 0,
            'total_processing_order'      => $statusCounts[3]  ?? 0,
            'total_hold_order'            => $statusCounts[4]  ?? 0,
            'total_printed_order'         => $statusCounts[5]  ?? 0,
            'total_packaging_order'       => $statusCounts[6]  ?? 0,
            'total_courier_entry_order'   => $statusCounts[7]  ?? 0,
            'total_on_delivery_order'     => $statusCounts[8]  ?? 0,
            'total_delivered_order'       => $statusCounts[9]  ?? 0,
            'total_cancelled_order'       => $statusCounts[10] ?? 0,
            'total_returned_order'        => $statusCounts[11] ?? 0,
            'total_trash_order'           => Order::onlyTrashed()->where('theme_id', $themeId)->count(),
        ];

        /* ===============================
            STATUS FILTER
        =============================== */
        $filteredQuery = clone $baseQuery;

        if ($sts !== 'total' && isset($statusMap[$sts])) {
            $filteredQuery->where('status', $statusMap[$sts]);
        }

        /* ===============================
            DUPLICATE PHONE (FULL DB)
        =============================== */
        $duplicatePhones = DB::table('orders')
            ->where('theme_id', $themeId)
            ->select('customer_phone')
            ->groupBy('customer_phone')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('customer_phone')
            ->toArray();

        /* ===============================
            FINAL DATA
        =============================== */
        $data['orders'] = $filteredQuery
            ->with([
                'get_products',
                'get_courier',
                'get_assigned',
                'get_staff_notes',
                'get_customer_notes',
            ])
            ->where('theme_id', $themeId)
            ->orderByDesc('id')
            ->where('theme_id', $themeId)
            ->paginate(20)
            ->through(function ($order) use ($duplicatePhones) {
                $order->is_duplicate = in_array($order->customer_phone, $duplicatePhones);
                return $order;
            });

        return view(
            'backEnd.admin.orders.index',
            compact(
                'data',
                'courier_id',
                'search',
                'sts',
                'payment_status',
                'source',
                'start',
                'end'
            )
        );
    }


    public function create()
    {
        $products = Product::where('status', 1)->where('theme_id', activeThemeData()->id)->select('id', 'name', 'has_variant')->latest()->get();
        $courier = Courier::where('status', 1)->pluck('courier_name', 'id');
        $shipping_methods = ShippingMethod::where('status', 1)->get();
        $invoice_id = invoice_generate();

        // dd($invoice_id);
        return view('backEnd.admin.orders.add', compact('products', 'courier', 'invoice_id', 'shipping_methods'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $check_cus = User::where('phone', $request->phone)->first();
        if ($check_cus) {
            $user_id = $check_cus;
        } else {
            $user_id = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'password' => Hash::make($request->phone),
            ]);
        }

        $courier_id = $request->courier_id ?? null;
        //dd($courier_id);
        switch ($courier_id) {
            case 2:
                $courier_city_id = PathaoZone::where('parent_id', $request->courier_zone_id)->first()->city_id;
                break;
            case 3:
                $courier_city_id = RedxArea::where('parent_id', $request->courier_zone_id)->first()->zone_id;
                break;
            case 4:
                $courier_city_id = CarrybeeZone::where('id', $request->courier_zone_id)->first()->carry_bee_city_id;
                break;
            default:
                $courier_city_id = null;
                break;
        }
        $inputs = array_merge($request->all(), [
            'order_date' => Carbon::parse($request->order_date)->format('Y-m-d'),
            'theme_id' => activeThemeData()->id,
            'customer_id' => $user_id->id ?? 0,
            'customer_name' => $request->name,
            'customer_phone' => $request->phone,
            'customer_address' => $request->address,
            'payment_method' => $request->payment_method ?? null,
            'payment_status' => $request->payment_status ?? 0,
            'shipping_method' => $request->shipping_method_id ?? null,
            'sub_total' => $request->sub_total ?? 0,
            'shipping_cost' => $request->delivery_cost ?? 0,
            'discount' => $request->discount ?? 0,
            'total' => $request->sub_total + $request->delivery_cost ?? 0,
            // 'payment_status'   => $request->paid_amount == $request->grand_total ? 1 : ($request->paid_amount == 0 ? 0 : 2),
            'source' => $request->source ?? null,
            'due' => $request->due_amount ?? 0,
            'paid' => $request->paid_amount ?? 0,
            'courier_city_id' => $courier_city_id,
        ]);
        // dd($inputs);
        $order = Order::create($inputs);

        if ($request->has('products') && is_array($request->products)) {
            foreach ($request->products as $p) {
                $product_id = $p['product_id'] ?? null;
                $sku = $p['sku'] ?? null;
                $qty = $p['qty'] ?? 1;
                $price = $p['price'] ?? 0;
                $variant_name = $p['variant_name'] ?? null;
                $variant_choice = $p['variant_choice'] ?? null;
                $purchase_price = null;
                if ($variant_choice) {
                    $purchase_price = ProductVariant::where('product_id', $product_id)->where('sku', $sku)->first()->purchase_price;
                } else {
                    $purchase_price = Product::find($product_id)->purchase_price;
                }

                $orderProduct = OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $product_id,
                    'product_sku' => $sku,
                    'qty' => $qty,
                    'price' => $price,
                    'purchase_price' => $purchase_price,
                    'attributes' => $variant_choice ? str_replace(',', '-', $variant_choice) : null,
                    // 'variant_choice' => $variant_choice,
                ]);

                $product = Product::find($product_id);
                if ($product) {
                    $product->decrement('stock', $qty);
                }

                if ($sku) {
                    $productVariant = ProductVariant::where('product_id', $product_id)->where('sku', $sku)->first();
                    if ($productVariant) {
                        $productVariant->decrement('stock', $qty);
                    }
                }
            }
        }

        $order_history = [
            'order_id' => $order->id,
            'user_type' => 'staff',
            'activity_type' => 1,
            'created_by' => Auth::user()->id,
            'text' => strtr(config('order_activities.new_order'), [
                '{user_name}' => Auth::user()->name,
            ]),
            'comment' => null,
            'old_order' => $request->except(['_token']),
        ];
        order_activities($order_history);

        $employees = Admin::where('is_order_assign', 1)
            ->where('status', 1)
            ->withCount('get_order_assign')
            ->get();
        if ($employees->count() > 0) {
            $minOrder = $employees->min('get_orders_count');
            $leastLoadedEmployees = $employees->where('get_orders_count', $minOrder);
            $selectedEmployee = $leastLoadedEmployees->random();
            OrderAssign::create([
                'order_id' => $order->id,
                'employee_id' => $selectedEmployee->id,
            ]);
        }

        return redirect()->route('admin.orders')->with('success', 'Order Created Successfully');
    }

    public function edit($id)
    {
        $data = Order::with('get_products', 'get_activities')->findOrFail($id);
        // dd($data);
        $shipping_methods = ShippingMethod::where('status', 1)->get();
        $products = Product::with('get_variants')->where('status', 1)->where('theme_id', activeThemeData()->id)->select('id', 'name', 'has_variant')->latest()->get();
        $product_id = [];
        foreach ($data->get_products as $order_item) {
            array_push($product_id, $order_item->get_product->id);
        }

        // carrybee and pathao cities and zones
        $cities = null;
        $zones = null;
        if ($data->courier_id == 2) {
            $cities = PathaoCity::pluck('name', 'parent_id');
            $zones = PathaoZone::pluck('name', 'id');
        } elseif ($data->courier_id == 4) {
            // $cities = CarrybeeCity::pluck('name', 'id');
            // $zones = CarrybeeZone::where('city_id', $data->city_id)->pluck('name', 'id');
        }

        return view('backEnd.admin.orders.edit', compact('data', 'products', 'shipping_methods', 'product_id', 'cities', 'zones'));
    }

    public function update(Request $request, $id)
    {
        //  dd($request->all());
        $order = Order::findOrFail($id);

        // dd($order);
        $oldData = [];
        if ($order->get_activities()->count() > 0) {
            $oldData = $order->get_activities()->latest('id')->first();
            if (isset($oldData->new_order)) {
                $oldData = $oldData->new_order;
            } else {
                $oldData = $oldData->old_order;
            }
        }

        $courier_id = $request->courier_id ?? null;
        // dd($courier_id);
        switch ($courier_id) {
            case 2:
                $courier_city_id = PathaoZone::where('parent_id', $request->courier_zone_id)->first()->city_id;
                break;
            case 3:
                $courier_city_id = RedxArea::where('parent_id', $request->courier_zone_id)->first()->zone_id;
                break;
            case 4:
                $courier_city_id = CarryBeeZone::where('id', $request->courier_zone_id)->first()->carry_bee_city_id;
                break;
            default:
                $courier_city_id = null;
                break;
        }

        // Preserve old coupon data if not coming from request
        $couponCode = $request->coupon_code ?? $order->coupon_code;
        $couponDiscount = $request->coupon_discount ?? $order->coupon_discount;
        $couponAppliedOn = $request->coupon_applied_on ?? $order->coupon_applied_on;

        $inputs = array_merge($request->all(), [
            'order_date' => Carbon::parse($request->order_date)->format('Y-m-d'),
            'customer_name' => $request->name,
            'customer_phone' => $request->phone,
            'customer_address' => $request->address,
            'shipping_method' => $request->shipping_method_id ?? null,
            'sub_total' => $request->sub_total ?? 0,
            'shipping_cost' => $request->delivery_cost ?? 0,
            'discount' => $request->discount ?? 0,
            'total' => $request->sub_total + $request->delivery_cost ?? 0,
            'paid_amount' => $request->paid_amount,
            'due_amount' => $request->due_amount,
            'payment_status' => $request->paid_amount == $request->grand_total ? 1 : ($request->paid_amount == 0 ? 0 : 2),
            'source' => $request->source ?? null,
            'due' => $request->due_amount ?? 0,
            'paid' => $request->paid_amount ?? 0,
            // ✅ coupon preserve
            'coupon_code' => $couponCode,
            'coupon_discount' => $couponDiscount,
            'coupon_applied_on' => $couponAppliedOn,
            'courier_city_id' => $courier_city_id,
        ]);

        // 1. Increment stock of old order
        foreach ($order->get_products as $item) {
            // dd($item);
            $product = Product::with('get_variants')->find($item->product_id);
            if ($product) {
                $product->increment('stock', $item->qty);
            }
            if ($item->attributes) {
                $product_variant = ProductVariant::where([
                    'product_id' => $item->product_id,
                    'sku' => $item->product_sku,
                ])->first();
                // dd($product_variant);

                if ($product_variant) {
                    $product_variant->increment('stock', $item->qty);
                }
            }
        }

        // 2. Update order & insert new products
        //    Then decrement stock for all products in $request->products

        $order->update($inputs);

        OrderProduct::where('order_id', $id)->delete();

        if ($request->has('products') && is_array($request->products)) {
            foreach ($request->products as $p) {
                $product_id = $p['product_id'] ?? null;
                $sku = $p['sku'] ?? null;
                $qty = $p['qty'] ?? 1;
                $price = $p['price'] ?? 0;
                $variant_name = $p['variant_name'] ?? null;
                $variant_choice = $p['variant_choice'] ?? null;
                $purchase_price = null;
                if ($variant_choice) {
                    $purchase_price = ProductVariant::where('product_id', $product_id)->where('sku', $sku)->first()->purchase_price;
                } else {
                    $purchase_price = Product::find($product_id)->purchase_price;
                }
                $orderProduct = OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $product_id,
                    'product_sku' => $sku,
                    'qty' => $qty,
                    'price' => $price,
                    'purchase_price' => $purchase_price,
                    'attributes' => $variant_choice ? str_replace(',', '-', $variant_choice) : null,
                    // 'variant_choice' => $variant_choice,
                ]);

                $product = Product::find($product_id);
                if ($product) {
                    $product->decrement('stock', $qty);
                }

                if ($sku) {
                    $productVariant = ProductVariant::where('product_id', $product_id)->where('sku', $sku)->first();
                    if ($productVariant) {
                        $productVariant->decrement('stock', $qty);
                    }
                }
            }
        }

        // dd($oldData);

        if ($oldData && count($oldData) > 0) {
            $updatedData = $request->except(['_token']);
            // dd($updatedData);
            $this->orderChanges($order, $oldData, $updatedData);
        } else {
            $order_history = [
                'order_id' => $order->id,
                'user_type' => 'staff',
                'activity_type' => 2,
                'created_by' => Auth::user()->id,
                'text' => 'Order Updated By ' . Auth::user()->name,
                'old_order' => $request->except(['_token']),
                'comment' => null,
            ];
            order_activities($order_history);
        }

        return redirect()->route('admin.orders')->with('success', 'Order Updated Successfully');
    }

    public function paymentStatus(Request $request, $id, $status)
    {
        // dd($status);
        $account = DB::table('accounts')->where('is_default', 1)->first();
        $order = Order::findOrFail($id);
        $data = $order;
        $order->update(['payment_status' => $status]);
        if ($status == 1) {
            $trans_one = AccountTransaction::create([
                'account_id' => $account->id,
                'order_id' => $id,
                'amount' => $data->total,
                'transaction_type' => 0,
                'purpose' => 'Order payment.',
            ]);

            $trans_one->get_account()->increment('balance', $data->total);

            $trans_two = AccountTransaction::create([
                'account_id' => $account->id,
                'order_id' => $id,
                'amount' => $data->shipping_cost,
                'transaction_type' => 1,
                'purpose' => 'Order shipping cost.',
            ]);

            $trans_two->get_account()->decrement('balance', $data->shipping_cost);

            // order activity
            OrderActivity::create([
                'order_id' => $order->id,
                'note' => 'Order Payment Paid by ' . Auth::guard('admin')->user()->name . ' (' . Auth::guard('admin')->user()->role . ')',
            ]);
        } elseif ($status == 0) {
            if ($data->get_transactions && count($data->get_transactions) > 0) {
                foreach ($data->get_transactions as $transaction) {
                    if ($transaction->transaction_type == 0) {
                        $transaction->get_account()->decrement('balance', $transaction->amount);
                    } else {
                        $transaction->get_account()->increment('balance', $transaction->amount);
                    }
                }
                $data->get_transactions()->delete();
            }

            // order activity
            OrderActivity::create([
                'order_id' => $order->id,
                'note' => 'Order Payment Unpaid by ' . Auth::guard('admin')->user()->name . ' (' . Auth::guard('admin')->user()->role . ')',
            ]);
        } else {
            $trans_one = AccountTransaction::create([
                'account_id' => $account->id,
                'order_id' => $id,
                'amount' => 0,
                'transaction_type' => 0,
                'purpose' => 'Order partial payment',
            ]);

            $trans_one->get_account()->increment('balance', $data->total);

            // order activity
            OrderActivity::create([
                'order_id' => $order->id,
                'note' => 'Order Partial Payment by ' . Auth::guard('admin')->user()->name . ' (' . Auth::guard('admin')->user()->role . ')',
            ]);
        }

        return back()->with('success', 'Payment Status Updated Successfully.');
    }

    public function statusChange($id, $status)
    {
        $order_id = Order::find($id);
        $order_id->update([
            'status' => $status,
        ]);
        // dd($pathao);
        $sts = $this->getOrderStatusName($order_id->status);
        $order_history = [
            'order_id' => $order_id->id,
            'user_type' => 'staff',
            'activity_type' => 3,
            'created_by' => Auth::user()->id,
            'text' => strtr(config('order_activities.order_status_change'), [
                '{status}' => $sts,
                '{user_name}' => Auth::user()->name,
            ]),
            'comment' => null,
        ];
        order_activities($order_history);

        return back()->with('success', 'Status Changed Successfully');
    }

    public function delete($id)
    {
        $order = Order::find($id);
        // dd($order);
        $order->delete();
        // order activity
        OrderActivity::create([
            'order_id' => $id,
            'user_type' => Auth::guard('admin')->user()->role,
            'created_by' => Auth::guard('admin')->user()->id,
            'activity_type' => 3,
            'text' => 'Order Trash by ' . Auth::guard('admin')->user()->name . ' (' . Auth::guard('admin')->user()->role . ')',
        ]);

        return back()->with('success', 'Order Deleted Successfully');
    }

    public function ajaxGetProductAttributeModal(Request $request)
    {
        // dd(1);
        $data = Product::with('get_product_variants')->find($request->id);

        // dd($data->get_product_choice_attributes);
        return view('backEnd.admin.orders.partials.variant_choice', compact('data'));
    }

    public function ajaxGetProductAttributeModalEdit(Request $request)
    {
        // dd(2);variant_choice
        $data = Product::with('get_product_variants')->find($request->id);

        return view('backEnd.admin.orders.partials.variant_choice_edit', compact('data'));
    }

    public function ajaxGetProducts(Request $request)
    {
        $data = Product::find($request->id);

        // dd($data);
        return view('backEnd.admin.orders.partials.ajax_products', compact('data'))->render();
    }

    public function ajaxGetModalChoiceAttributes(Request $request)
    {
        // dd($request->all());
        if (is_array($request->choice_variants) && count($request->choice_variants) > 0) {
            $quantity = $request->quantity;
            $choice_variants = $request->choice_variants;
            $variant_attr = '';
            // dd($choice_variant );
            foreach ($choice_variants as $key => $choice_variant) {
                if ($key == array_key_first($choice_variants)) {
                    $variant_attr .= $choice_variant;
                } else {
                    $variant_attr .= '-' . $choice_variant;
                }
            }
            $product_variant = ProductAttribute::where([['product_id', $request->id], ['variant', strtolower(str_replace(' ', '_', $variant_attr))]])->first();
            $price = $product_variant->sale_price > 0 ? number_format($product_variant->sale_price, 2) : number_format($product_variant->regular_price, 2);
            $sku = $product_variant->sku;
            $stock = $product_variant->stock;
            $data = Product::find($request->id);
            $variant = $product_variant;

            //  dd($data, $variant, $choice_variants, $sku, $price, $stock);
            return view('backEnd.admin.orders.partials.product_variants', compact('data', 'variant', 'choice_variants', 'sku', 'price', 'stock'))->render();
        }

        // dd($product_attributes);
    }

    public function ajaxGetChoiceAttributes(Request $request)
    {

        // dd($request->all());
        $quantity = $request->quantity;
        $choice_variants = $request->choice_variants ?? [];
        $product_id = $request->id;

        $variant_string = implode('-', $choice_variants);

        $product_attribute = ProductAttribute::where([
            ['product_id', $product_id],
            ['variant', strtolower(str_replace(' ', '_', $variant_string))],
        ])->first();

        // dd($product_attribute);

        if ($product_attribute) {
            $sku = $product_attribute->sku;
            $price = $product_attribute->sale_price > 0 ? number_format($product_attribute->sale_price, 2) : number_format($product_attribute->regular_price, 2);
            $stock = $product_attribute->stock;
        } else {
            $sku = '';
            $price = 0;
            $stock = 0;
        }
        // dd( $choice_variants, $sku, $price, $stock, $quantity);

        // Any additional data you need to send to the view
        $data = Product::find($product_id);
        $variants = $choice_variants;

        // Render Blade to HTML
        if ($product_attribute) {
            $html = view('backEnd.admin.orders.partials.attr_checkbox', compact('data', 'variants', 'choice_variants', 'sku', 'price', 'stock', 'quantity'))->render();
            // return view('backEnd.admin.orders.attr_checkbox', compact('data', 'attributes', 'choice_attributes', 'sku', 'price', 'stock', 'quantity'))->render();

            return response()->json([
                'html' => $html,
                'sku' => $sku,
                'price' => $price,
                'subtotal' => $price * $quantity,
                'stock' => $stock,
            ]);
        }
    }

    public function printInvoice(Request $request)
    {
        $data = Order::find($request->id);
        // order activity
        OrderActivity::create([
            'order_id' => $data->id,
            'note' => 'Invoice Printed by ' . Auth::guard('admin')->user()->name . ' (' . Auth::guard('admin')->user()->role . ')',
        ]);

        $web_settings = WebSettings::first();

        $printSetting = PrintSetting::first();

        if ($printSetting->single_print == 0) {
            return view('backEnd.admin.print.layout.single_default', compact('data', 'web_settings'))->render();
        } elseif ($printSetting->single_print == 1) {
            return view('backEnd.admin.print.layout.single_layout_1', compact('data', 'web_settings'))->render();
        }
    }

    public function printBulkInvoice(Request $request)
    {
        // dd($request->all());
        $orders = Order::find($request->all_inv_id);
        foreach ($orders as $order) {
            // order activity
            OrderActivity::create([
                'order_id' => $order->id,
                'note' => 'Invoice Printed by ' . Auth::guard('admin')->user()->name . ' (' . Auth::guard('admin')->user()->role . ')',
            ]);
        }
        $web_settings = WebSettings::first();
        $printSetting = PrintSetting::first();

        if ($printSetting->bulk_print == 0) {
            return view('backEnd.admin.print.layout.bulk_default', compact('orders', 'web_settings'))->render();
        } elseif ($printSetting->bulk_print == 1) {
            return view('backEnd.admin.print.layout.bulk_layout_1', compact('orders', 'web_settings'))->render();
        } elseif ($printSetting->bulk_print == 2) {
            return view('backEnd.admin.print.layout.bulk_3_2_layout', compact('orders', 'web_settings'))->render();
        }
    }

    public function allStatusChange(Request $request)
    {
        if ($request->status_update) {
            foreach (explode(',', $request->all_status) as $item) {
                $order_id = Order::find($item);
                $order_id->update([
                    'status' => $request->status,
                ]);
                $sts = $this->getOrderStatusName($order_id->status);
                $order_history = [
                    'order_id' => $order_id->id,
                    'user_type' => 'staff',
                    'activity_type' => 3,
                    'created_by' => Auth::user()->id,
                    'text' => strtr(config('order_activities.order_status_change'), [
                        '{status}' => $sts,
                        '{user_name}' => Auth::user()->name,
                    ]),
                    'comment' => null,
                ];
                order_activities($order_history);
            }

            return back()->with('success', 'Status Changed Successfully');
        }
        if ($request->status_delete) {
            foreach (explode(',', $request->all_delete_id) as $item) {
                $order_id = Order::find($item);

                // order activity
                $sts = $this->getOrderStatusName($order_id->status);
                $order_history = [
                    'order_id' => $order_id->id,
                    'user_type' => 'staff',
                    'activity_type' => 3,
                    'created_by' => Auth::user()->id,
                    'text' => strtr(config('order_activities.order_status_change'), [
                        '{status}' => $sts,
                        '{user_name}' => Auth::user()->name,
                    ]),
                    'comment' => null,
                ];
                order_activities($order_history);
                $order_id->delete();
            }

            return back()->with('success', 'Order Delete Successfully');
        }
    }

    // courier courier_csv
    public function courierCsv(Request $request)
    {
        $orders_id = explode(',', $request->oders_id);
        if ($request->status == 1) {
            $name = 'stead_fast';
        } else {
            $name = 'redex';
        }
        // order activity
        // dd(json_decode($request->oders_id));
        foreach ($orders_id as $id) {
            OrderActivity::create([
                'order_id' => $id,
                'note' => 'Order Sent to Courier by ' . Auth::guard('admin')->user()->name . ' (' . Auth::guard('admin')->user()->role . ')',
            ]);
        }
        $file_name = $name . '_' . date('d-M-Y') . '.xlsx';

        return Excel::download(new OrderExport(explode(',', $request->oders_id), $request->status), $file_name);
    }

    public function sendToCourier(Request $request)
    {
        if ($request->courier_id == 3 || $request->courier_id == 2) {
            if ($request->courier_id == 3) {
                $order = Order::find($request->order_id);
                $courier_id = $request->courier_id;

                return view('backEnd.admin.orders.courier_modal', compact('order', 'courier_id'))->render();
            }
            if ($request->courier_id == 2) {

                $order = Order::find($request->order_id);
                $courier_id = $request->courier_id;

                return view('backEnd.admin.orders.courier_modal', compact('order', 'courier_id'))->render();
            }
        } else {
            $order = Order::find($request->order_id);
            $order->update([
                'courier_id' => $request->courier_id,
            ]);

            return response()->json(['status' => 200]);
        }
    }

    public function sendToCourierStore(Request $request)
    {
        // dd($request->all());
        $order = Order::with('get_city', 'get_zone')->find($request->order_id);
        // dd($order);

        if ($request->courier_id == 3) {

            $credential = DB::table('api_couriers')->select('pathao_access_token')->where('id', 1)->first();
            // dd($credential);
            // dd($credential);
            $url = 'https://api-hermes.pathao.com//aladdin/api/v1/orders';
            $ch = curl_init();
            $data = [
                'store_id' => 70676,
                'merchant_order_id' => $order->invoice_id,
                'name' => $order->customer_name ?? 'Mr. X',
                'contact_name' => $order->customer_name ?? 'Mr. X',
                'contact_number' => '01846464646',
                'address' => 'House-1, Road-1, Block-A, Mirpur-1, Dhaka-1216',
                'city_id' => $request->city ?? 1,
                'zone_id' => $request->zone ?? 1,
                'area_id' => 1,
                'delivery_type' => 48,
                'item_type' => 2,
                'special_instruction' => 'Need to Delivery before 5 PM',
                'item_quantity' => 1,
                'item_weight' => '0.5',
                'item_description' => 'this is a Cloth item, price- 3000',
                'amount_to_collect' => 900,
            ];
            // dd($data);

            $datas = json_encode($data);
            // dd($datas);
            $headers = [
                'accept: application/json',
                'content-type: application/json',
                'authorization: Bearer ' . $credential->pathao_access_token,
            ];
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $response = json_decode($response, true);
            // dd($response);

        }

        // order activity
        OrderActivity::create([
            'order_id' => $request->order_id,
            'note' => 'Order Sent to Courier by ' . Auth::guard('admin')->user()->name . ' (' . Auth::guard('admin')->user()->role . ')',
        ]);

        $order->update([
            'courier_id' => $request->courier_id,
            'courier_city_id' => $request->city,
            'courier_zone_id' => $request->zone,
        ]);

        return back()->with('success', 'Order Sent To Courier Successfully');
    }

    public function bulkSendToCourier(Request $request)
    {
        if ($request->courier_id == 3 || $request->courier_id == 2) {
            $orders = Order::whereIn('id', explode(',', $request->order_id))->get();
            $courier_id = $request->courier_id;

            return view('backEnd.admin.orders.bulk_courier_modal', compact('orders', 'courier_id'))->render();
        } else {
            $orders = Order::whereIn('id', explode(',', $request->order_id))->get();
            foreach ($orders as $order) {
                $order->update([
                    'courier_id' => $request->courier_id,
                ]);
            }

            return response()->json(['status' => 200]);
        }
    }

    public function bulkSendToCourierStore(Request $request)
    {
        $orders = Order::whereIn('id', $request->order_id)->get();
        foreach ($orders as $key => $order) {
            $order->update([
                'courier_id' => $request->courier_id,
                'courier_city_id' => $request->city[$key],
                'courier_zone_id' => $request->zone[$key],
            ]);
        }

        // order activity
        OrderActivity::create([
            'order_id' => $request->order_id,
            'note' => 'Order Sent to Courier by ' . Auth::guard('admin')->user()->name . ' (' . Auth::guard('admin')->user()->role . ')',
        ]);

        return back()->with('success', 'Order Sent To Courier Successfully');
    }

    public function courierZone(Request $request)
    {
        $pathao_zones = PathaoZone::where('pathao_city_id', $request->city_id)->pluck('name', 'id');

        // dd($pathao_zones);
        return view('backEnd.admin.orders.pathao_zone', compact('pathao_zones'))->render();
    }

    public function getCities(Request $request)
    {
        // dd($request->all());
        $courier_id = $request->courier_id;
        $data = [];
        if ($courier_id == 2) {   // Pathao
            $zones = DB::table('pathao_cities as c')->join('pathao_zones as z', 'c.parent_id', '=', 'z.city_id')->select('c.parent_id as city_id', 'c.name as city_name', 'z.parent_id as zone_id', 'z.name as zone_name')->get();
        } elseif ($courier_id == 3) {   // RedX
            $zones = DB::table('redx_areas')->select('parent_id', 'district', 'division', 'name')->get();
        } elseif ($courier_id == 4) {   // CarryBee
            // $zones = DB::table('carrybee_zones')
            //     ->join('carrybee_cities', 'carrybee_zones.city_id', '=', 'carrybee_cities.id')
            //     ->select(
            //         'carrybee_zones.id as zone_id',
            //         'carrybee_zones.name as zone_name',
            //         'carrybee_cities.name as city_name'
            //     )
            //     ->get();
            $zones = CarryBeeZone::with('city')
                ->get()
                ->map(function ($zone) {
                    return [
                        'zone_id'   => $zone->id,
                        'zone_name' => $zone->name,
                        'city_name' => $zone->city->name ?? null,
                    ];
                });
            // dd($zones);
        } else {

            return response()->json([
                'success' => false,
                'data' => [],
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $zones,
            'courier_id' => $courier_id,
        ]);
    }

    public function getZones(Request $request)
    {
        $courier_id = $request->courier_id;
        $city_id = $request->city_id;

        if ($courier_id == 2) { // Pathao
            $zones = PathaoZone::where('city_id', $city_id)->pluck('name', 'id');
            // dd($zones);
        } elseif ($courier_id == 4) { // CarryBee
            // $zones = CarrybeeZone::where('city_id', $city_id)->pluck('name', 'id');
        } else {
            return response()->json(['success' => false]);
        }

        return response()->json([
            'success' => true,
            'zones' => $zones,
        ]);
    }

    // customer note
    public function customerNote(Request $request)
    {
        // $order     = Order::find($request->order_id);
        $orderNote = OrderNote::where('order_id', $request->order_id)->where('id', $request->note_id)->where('user_id', $request->user_id)->first();

        // dd($orderNote);
        return view('backEnd.admin.orders.notes.customer-note', compact('orderNote'))->render();
    }

    public function customerNoteUpdate(Request $request)
    {
        // dd($request->all());
        $orderNote = OrderNote::where('order_id', $request->order_id)->where('id', $request->note_id)->where('user_id', $request->user_id)->where('user_type', 'customer')->first();
        $orderNote->update([
            'note' => $request->note,
        ]);

        return back()->with('success', 'Note Updated Successfully');
    }

    // staff note
    public function staffNote(Request $request)
    {
        $order = Order::find($request->order_id);
        $orderNote = OrderNote::where('order_id', $request->order_id)->where('id', $request->note_id)->first();

        return view('backEnd.admin.orders.notes.staff-note', compact('order', 'orderNote'))->render();
    }

    public function staffNoteUpdate(Request $request)
    {
        // dd($request->all());
        OrderNote::create([
            'order_id' => $request->order_id,
            'note' => $request->note,
            'user_id' => Auth::guard('admin')->user()->id,
            'user_type' => Auth::guard('admin')->user()->role,
        ]);

        return back()->with('success', 'Note Updated Successfully');
    }

    // all notes
    public function notes(Request $request)
    {
        $order = Order::with('get_notes')->find($request->order_id);

        return view('backEnd.admin.orders.notes.all-notes', compact('order'))->render();
    }

    // bulkAssign
    public function bulkAssign(Request $request)
    {
        $orders = Order::with('get_assigned')->whereIn('id', explode(',', $request->order_id))->get();
        foreach ($orders as $order) {
            if ($order->get_assigned) {
                $order->get_assigned()->delete();
            }
            OrderAssign::create([
                'order_id' => $order->id,
                'employee_id' => $request->user_id,
            ]);
            // order activity
            OrderActivity::create([
                'order_id' => $order->id,
                'note' => 'Order Assigned by ' . Auth::guard('admin')->user()->name . ' (' . Auth::guard('admin')->user()->role . ')',
            ]);
        }

        return back()->with('success', 'Order Assigned Successfully');
    }

    // order activity
    public function activies(Request $request)
    {
        $order = Order::with('get_activities')->find($request->order_id);

        return view('backEnd.admin.orders.activities', compact('order'))->render();
    }

    public function viewActivity($id)
    {
        $activity = OrderActivity::find($id);

        return view('backEnd.admin.orders.view-more', compact('activity'));
    }

    // viewMore
    public function viewMore(Request $request)
    {
        $order = Order::with('get_products', 'get_courier', 'get_assigned', 'get_activities')->find($request->order_id);

        return view('backEnd.admin.orders.view-more', compact('order'))->render();
    }

    public function sendToCourierOrderIds(Request $request)
    {
        $ids = explode(',', $request->query('ids'));
        $orders = Order::whereIn('id', $ids)->get();

        if ($orders->isEmpty()) {
            return back()->with('error', 'No orders found.');
        }

        return view('backEnd.admin.orders.send-to-courier', compact('orders'));
    }

    public function sendToCourierItems(Request $request)
    {
        // dd($request->all());
        $order = Order::with('get_products')->find($request->order_id);
        // dd($order->courier_error_msg);
        if (! $order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found',
                'courier' => null,
                'consignment_id' => null,
            ]);
        }
        // Already sent check
        if (! empty($order->consignment_id)) {
            return response()->json([
                'status' => true,
                'message' => '⚠ Already sent' . ($order->consignment_id ? " (ID: {$order->consignment_id})" : ''),
                'courier' => $order->get_courier->courier_name ?? 'Unknown',
                'consignment_id' => $order->consignment_id ?? null,
            ]);
        }
        // Update city/zone if provided
        if ($request->city_id && $request->zone_id) {
            $order->update([
                'courier_city_id' => $request->city_id,
                'courier_zone_id' => $request->zone_id,
            ]);
        }
        $courier_id = $order->courier_id;
        if ($courier_id && $order->consignment_id === null && $order->status != 7) {
            // $error_response = null;
            switch ($courier_id) {
                case 1: // Steadfast
                    $steadfastService = new SteadfastCourier;
                    $response = $steadfastService->sendOrder(['data' => $order]);
                    // dd($response);
                    if ($response && isset($response['status']) && $response['status'] == 200) {
                        $data = $response['consignment'];
                        $order->update([
                            'status' => 7,
                            'consignment_id' => $data['consignment_id'] ?? null,
                            'tracking_id' => $data['tracking_code'] ?? null,
                            'courier_status' => $data['status'] ?? null,
                            'courier_error_msg' => null,
                        ]);
                    } else {
                        $order->update([
                            'courier_error_msg' => $response['errors'] ?? null,
                        ]);
                    }
                    break;
                case 2: // Pathao
                    $pathaoService = new PathaoCourier;
                    $response = $pathaoService->sendOrder([
                        'data' => $order,
                    ]);
                    // dd($response);
                    if (isset($response['code']) && $response['code'] == 200) {
                        $order->update([
                            'status' => 7,
                            'consignment_id' => $response['data']['consignment_id'] ?? null,
                            'courier_status' => $response['data']['order_status'] ?? null,
                            'courier_error_msg' => null,
                        ]);
                    } else {
                        $order->update([
                            'courier_error_msg' => $response['errors'] ?? null,
                        ]);
                    }
                    break;
                case 3: // RedX
                    $redxService = new RedxCourier;
                    $response = $redxService->sendOrder([
                        'data' => $order,
                    ]);
                    // dd($response);
                    if (isset($response['tracking_id'])) {
                        $order->update([
                            'status' => 7,
                            'tracking_id' => $response['tracking_id'] ?? null,
                            'courier_error_msg' => null,
                        ]);
                    } else {
                        $order->update([
                            'courier_error_msg' => $response['validation_errors'] ?? null,
                        ]);
                    }
                    break;
                case 4: // carrybee
                    $carrybeeService = new CarryBeeCourier;
                    // dd($carrybeeService);
                    $response = $carrybeeService->sendOrder([
                        'data' => $order,
                    ]);
                    // dd($response);
                    if (isset($response['data']['order']['consignment_id'])) {
                        $order->update([
                            'status' => 7,
                            'consignment_id' =>  $response['data']['order']['consignment_id'] ?? null,
                            'courier_error_msg' => null,
                        ]);
                    } else {
                        $order->update([
                            'courier_error_msg' => $response['errors'] ?? null,
                        ]);
                    }
                    break;
            }
        }
        // dd($order);
        if ($order->status == 7 && empty($order->courier_error_msg) && ! empty($order->courier_id)) {
            $couriers = [1 => 'Steadfast', 2 => 'Pathao', 3 => 'RedX', 4 => 'RX', 5 => 'Carrybee'];
            $courierName = $couriers[$courier_id] ?? 'Unknown Courier';
            if ($order->courier_id == 3) {
                return response()->json([
                    'status' => true,
                    'courier' => $courierName,
                    'consignment_id' => $order->tracking_id,
                    'message' => "Sent to {$courierName}" . ($order->tracking_id ? " (Tracking ID: {$order->tracking_id})" : ''),
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'courier' => $courierName,
                    'consignment_id' => $order->consignment_id,
                    'message' => "Sent to {$courierName}" . ($order->consignment_id ? " (Consignment ID: {$order->consignment_id})" : ''),
                ]);
            }
        } else {
            $message = '';
            if (! empty($order->courier_error_msg) && is_array($order->courier_error_msg)) {
                if ($order->courier_id == 3) {
                    foreach ($order->courier_error_msg as $key => $value) {
                        foreach ($value as $k => $v) {
                            $message .= $k . ': ' . $v . "\n";
                        }
                    }
                } else {
                    foreach ($order->courier_error_msg as $key => $value) {
                        // dd($value);
                        if (is_array($value) && isset($value[0])) {
                            $message .= $key . ': ' . $value[0] . "\n";
                        }
                    }
                }
            } else {
                $message = 'Unknown courier error.';
            }

            // dd($message);
            return response()->json([
                'status' => false,
                'message' => $message,
                'courier' => null,
                'consignment_id' => null,
            ]);
        }
    }

    private function getOrderStatusName($status)
    {
        $status = (int) $status;
        switch ($status) {
            case 1:
                return 'Pending';
            case 2:
                return 'Confirm';
            case 3:
                return 'Processing';
            case 4:
                return 'Hold';
            case 5:
                return 'Printed';
            case 6:
                return 'Packaging';
            case 7:
                return 'Courier Entry';
            case 8:
                return 'On Delivery';
            case 9:
                return 'Delivered';
            case 10:
                return 'Cancelled';
            case 11:
                return 'Returned';

            default:
                return 'Unknown';
        }
    }

    private function orderChanges($order, array $oldData, array $updatedData)
    {
        $changes_mark = [];
        $changes_fields = [];

        // 🔹 Check other non-product field changes
        foreach ($updatedData as $key => $value) {
            if (! array_key_exists($key, $oldData) || $oldData[$key] != $value) {
                if (in_array($key, ['updated_at', 'created_at', 'products'])) {
                    continue;
                }

                $oldVal = $oldData[$key] ?? null;
                $changes_mark[$key] = ['old' => $oldVal, 'new' => $value];
                $changes_fields[] = $key;
            }
        }

        // 🔹 Decode JSON if stored as string
        $oldProducts = is_string($oldData['products'] ?? null)
            ? json_decode($oldData['products'], true)
            : ($oldData['products'] ?? []);

        $newProducts = is_string($updatedData['products'] ?? null)
            ? json_decode($updatedData['products'], true)
            : ($updatedData['products'] ?? []);

        // 🔹 Compare product data (product_id + variant_choice)
        foreach ($newProducts as $newProd) {
            $productId = $newProd['product_id'];
            $variantChoice = $newProd['variant_choice'] ?? '';
            $match = collect($oldProducts)->first(function ($oldProd) use ($productId, $variantChoice) {
                return $oldProd['product_id'] == $productId &&
                    ($oldProd['variant_choice'] ?? '') == $variantChoice;
            });

            // If product didn’t exist before → new product added
            if (! $match) {
                $changes_fields[] = "product_{$productId}_added";
                $changes_mark["product_{$productId}_added"] = [
                    'old' => null,
                    'new' => $newProd,
                ];

                continue;
            }

            // 🔸 Check quantity change
            if ($match['qty'] != $newProd['qty']) {
                $changes_fields[] = "product_{$productId}_qty";
                $changes_mark["product_{$productId}_qty"] = [
                    'old' => $match['qty'],
                    'new' => $newProd['qty'],
                ];
            }

            // 🔸 Check price change
            if ($match['price'] != $newProd['price']) {
                $changes_fields[] = "product_{$productId}_price";
                $changes_mark["product_{$productId}_price"] = [
                    'old' => $match['price'],
                    'new' => $newProd['price'],
                ];
            }
        }

        // 🔹 Detect removed products
        foreach ($oldProducts as $oldProd) {
            $productId = $oldProd['product_id'];
            $variantChoice = $oldProd['variant_choice'] ?? '';
            $exists = collect($newProducts)->contains(function ($newProd) use ($productId, $variantChoice) {
                return $newProd['product_id'] == $productId &&
                    ($newProd['variant_choice'] ?? '') == $variantChoice;
            });

            if (! $exists) {
                $changes_fields[] = "product_{$productId}_removed";
                $changes_mark["product_{$productId}_removed"] = [
                    'old' => $oldProd,
                    'new' => null,
                ];
            }
        }

        // 🔹 Save activity
        $updatedData['changes_fields'] = $changes_fields;

        if (! empty($changes_mark)) {
            order_activities([
                'order_id' => $order->id,
                'store_id' => $order->store_id,
                'user_type' => 'staff',
                'created_by' => Auth::id(),
                'text' => 'Order updated by ' . Auth::user()->name,
                'old_order' => $oldData,
                'new_order' => $updatedData,
                'changes' => $changes_mark,
                'activity_type' => 2,
            ]);
        }
    }
}
