<?php

namespace App\Http\Controllers;

use App\AccountTransaction;
use App\Coupon;
use App\Courier;
use App\Exports\AccountTransactionExport;
use App\Exports\ProductStockReportExport;
use App\Exports\ProfitLossReportExport;
use App\Order;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function salesOrderReport(Request $request)
    {
        $start = Carbon::today()->startOfDay();
        $end = Carbon::today()->endOfDay();

        if ($request->filled('date_range')) {
            $range = json_decode($request->date_range, true);

            if (! empty($range['start']) && ! empty($range['end'])) {
                $start = Carbon::parse($range['start'])->startOfDay();
                $end = Carbon::parse($range['end'])->endOfDay();
            }
        }

        $orders = Order::with([
            'get_products.get_product',
        ])
            ->where('status', 9)
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->get();

        $sales = $orders->map(function ($order) {

            $purchaseCost = $order->get_products->sum(function ($item) {
                return ($item->purchase_price ?? $item->get_product->purchase_price) * $item->qty;
            });
            $product_discount = $order->coupon_discount ?? 0;

            $totalQty = $order->get_products->sum('qty');

            // Net Profit including discount & shipping
            $netProfit = $order->total - $purchaseCost - ($order->discount ?? 0) - ($order->shipping_cost ?? 0) - ($product_discount ?? 0);

            return [
                'order_id' => $order->invoice_id ?? $order->id,
                'date' => $order->created_at->format('d M Y'),
                'customer' => $order->customer_name ?? 'Guest',
                'qty' => $totalQty,
                'sales' => $order->total,
                'purchase' => $purchaseCost,
                'discount' => $order->discount ?? 0,
                'product_discount' => $product_discount ?? 0,
                'shipping' => $order->shipping_cost ?? 0,
                'profit' => $netProfit,
            ];
        });

        // Footer sum
        $footer = [
            'total_qty' => $sales->sum('qty'),
            'total_sales' => $sales->sum('sales'),
            'total_purchase' => $sales->sum('purchase'),
            'total_discount' => $sales->sum('discount'),
            'total_product_discount' => $sales->sum('product_discount'),
            'total_shipping' => $sales->sum('shipping'),
            'total_profit' => $sales->sum('profit'),
        ];

        return view('backEnd.admin.report.order_sales', compact('sales', 'footer', 'start', 'end'));
    }

    public function salesProductReport(Request $request)
    {
        $start = Carbon::today()->startOfDay();
        $end = Carbon::today()->endOfDay();

        // Date range filter
        if ($request->filled('date_range')) {
            $range = json_decode($request->date_range, true);
            if (! empty($range['start']) && ! empty($range['end'])) {
                $start = Carbon::parse($range['start'])->startOfDay();
                $end = Carbon::parse($range['end'])->endOfDay();
            }
        }

        // Fetch orders with products
        $orders = Order::with(['get_products.get_product'])
            ->where('status', 9) // Delivered/Completed
            ->whereBetween('order_date', [$start, $end])
            ->get();

        $products = collect();

        foreach ($orders as $order) {

            $orderDiscount = $order->discount ?? 0;
            $orderShipping = $order->shipping_cost ?? 0;
            $orderCoupon = $order->coupon_discount ?? 0;

            $orderTotalQty = $order->get_products->sum('qty');
            if ($orderTotalQty == 0) {
                continue;
            }

            // Get coupon applied product ids if product type coupon
            $couponAppliedProducts = [];
            if ($order->coupon_applied_on === 'product' && ! empty($order->coupon_code)) {
                // Use product_ids from coupon table
                $couponAppliedProducts = Coupon::where('code', $order->coupon_code)->first()->product_ids;
            }

            foreach ($order->get_products as $item) {
                $productId = $item->product_id;

                $prod = $products->get($productId, [
                    'product_name' => $item->get_product->name ?? 'Unknown',
                    'sku' => $item->get_product->sku ?? '-',
                    'qty' => 0,
                    'sales' => 0,
                    'purchase' => 0,
                    'discount' => 0,
                    'shipping' => 0,
                    'coupon' => 0,
                    'profit' => 0,
                ]);

                $totalPrice = $item->price * $item->qty;
                $totalPurchase = ($item->purchase_price ?? $item->get_product->purchase_price) * $item->qty;

                $proportion = $item->qty / $orderTotalQty;

                // Normal order discount proportional
                $distributedDiscount = $orderDiscount * $proportion;

                // Shipping proportional
                $distributedShipping = $orderShipping * $proportion;

                // Coupon discount
                $distributedCoupon = 0;
                if ($orderCoupon > 0) {
                    if ($order->coupon_applied_on === 'cart') {
                        // proportional for all products
                        $distributedCoupon = $orderCoupon * $proportion;
                    } elseif ($order->coupon_applied_on === 'product') {
                        // only for selected products, ignore qty or total, one time per product
                        if (in_array($productId, $couponAppliedProducts)) {
                            $distributedCoupon = $orderCoupon; // no division by proportion
                        }
                    }
                }

                $netProfit = $totalPrice - $totalPurchase - $distributedDiscount - $distributedShipping - $distributedCoupon;

                $prod['qty'] += $item->qty;
                $prod['sales'] += $totalPrice;
                $prod['purchase'] += $totalPurchase;
                $prod['discount'] += $distributedDiscount;
                $prod['shipping'] += $distributedShipping;
                $prod['coupon'] += $distributedCoupon;
                $prod['profit'] += $netProfit;

                $products->put($productId, $prod);
            }
        }

        $footer = [
            'total_qty' => $products->sum('qty'),
            'total_sales' => $products->sum('sales'),
            'total_purchase' => $products->sum('purchase'),
            'total_discount' => $products->sum('discount'),
            'total_shipping' => $products->sum('shipping'),
            'total_coupon' => $products->sum('coupon'),
            'total_profit' => $products->sum('profit'),
        ];

        return view('backEnd.admin.report.product_sales', compact('products', 'footer', 'start', 'end'));
    }

    public function profit_loss(Request $request)
    {
        // dd($request->all());
        $start = Carbon::today()->subDays(29);
        $end = Carbon::today();

        if ($request->has('date_range')) {
            $range = json_decode($request->date_range, true);
            if (isset($range['start']) && isset($range['end'])) {
                $start = Carbon::parse($range['start']);
                $end = Carbon::parse($range['end']);
            }
        }
        $trans = AccountTransaction::query();
        $totalPurchaseCost = 0;
        $totalExpenseCost = 0;
        $totalSell = 0;
        if ($request->start_date && $request->end_date) {
            $start = Carbon::parse($request->start_date)->startOfDay()->format('Y-m-d H:i:s');
            $end = Carbon::parse($request->end_date)->endOfDay()->format('Y-m-d H:i:s');
            $tranRange = $trans->whereBetween('created_at', [$start, $end]);
            $totalPurchaseCost = $tranRange->where('purchase_id', '!=', null)->sum('amount');
            $totalSell = $tranRange->where('order_id', '!=', null)->sum('amount');
            $totalExpenseCost = $tranRange->where('expense_id', '!=', null)->sum('amount');
            $daterange = date('d/m/Y', strtotime($request->start_date)).' - '.date('d/m/Y', strtotime($request->end_date));
            if ($request->has('export')) {
                // dd($totalPurchaseCost, $totalSell, $totalExpenseCost);
                $name = 'profit_loss_report';
                $file_name = $name.'_'.date('d-M-Y').'.xlsx';

                return Excel::download(new ProfitLossReportExport($totalSell, $totalPurchaseCost, $totalExpenseCost), $file_name);
            } else {
                return view('backEnd.admin.report.profit_loss', compact('totalPurchaseCost', 'totalSell', 'totalExpenseCost', 'daterange', 'start', 'end'));
            }
        } else {
            $trans = $trans->whereDate('created_at', now()->toDateString());
            $totalPurchaseCost = $trans->where('purchase_id', '!=', null)->sum('amount');
            $totalSell = $trans->where('order_id', '!=', null)->sum('amount');
            $totalExpenseCost = $trans->where('expense_id', '!=', null)->sum('amount');
            if ($request->has('export')) {
                // dd($totalPurchaseCost,$totalSell ,$totalExpenseCost);
                $name = 'profit_loss_report';
                $file_name = $name.'_'.date('d-M-Y').'.xlsx';

                return Excel::download(new ProfitLossReportExport($totalSell, $totalPurchaseCost, $totalExpenseCost), $file_name);
            } else {
                return view('backEnd.admin.report.profit_loss', compact('totalPurchaseCost', 'totalSell', 'totalExpenseCost', 'start', 'end'));
            }
        }

        // return view('backEnd.admin.report.profit_loss', compact('totalPurchaseCost', 'totalSell', 'totalExpenseCost'));

    }

    public function productStock(Request $request)
    {

        $data['products'] = Product::query();
        $product_list = DB::table('products')->pluck('name', 'id');
        if ($request->input('product_id')) {

            $productRange = $data['products']->where('id', $request->input('product_id'))->paginate(10);
            $product_id = $request->input('product_id');
            if ($request->has('export')) {
                $name = 'product_stock_report';
                $file_name = $name.'_'.date('d-M-Y').'.xlsx';

                return Excel::download(new ProductStockReportExport($productRange), $file_name);
            } else {
                return view('backEnd.admin.report.product-stock', compact('productRange', 'product_list', 'product_id'));
            }
        }

        $productRange = $data['products']->where('status', 1)->paginate(10);
        if ($request->has('export')) {
            $name = 'product-stock_report';
            $file_name = $name.'_'.date('d-M-Y').'.xlsx';

            return Excel::download(new ProductStockReportExport($productRange), $file_name);
        } else {
            return view('backEnd.admin.report.product-stock', compact('productRange', 'product_list'));
        }

        // return view('backEnd.admin.report.product-stock', compact('data'));

    }

    public function account_trans(Request $request)
    {
        // dd($request->all());
        $data = AccountTransaction::query();
        $cashIn = 0;
        $cashOut = 0;
        if ($request->start_date && $request->end_date) {
            $start = Carbon::parse($request->start_date)->startOfDay()->format('Y-m-d H:i:s');
            $end = Carbon::parse($request->end_date)->endOfDay()->format('Y-m-d H:i:s');
            $filteredData = $data->whereBetween('created_at', [$start, $end])->get();
            $cashOut = $filteredData->where('transaction_type', 1)->sum('amount');
            $cashIn = $filteredData->where('transaction_type', 0)->sum('amount');

            // dd($cashIn, $cashOut);
            $daterange = date('d/m/Y', strtotime($request->start_date)).' - '.date('d/m/Y', strtotime($request->end_date));
            if ($request->has('export')) {
                $name = 'transaction_report';
                $file_name = $name.'_'.date('d-M-Y').'.xlsx';

                return Excel::download(new AccountTransactionExport($cashIn, $cashOut), $file_name);
            } else {
                return view('backEnd.admin.report.account-trans', compact('cashIn', 'cashOut', 'daterange', 'start', 'end'));
            }
        } else {
            $data = $data->whereDate('created_at', now()->toDateString());
            $cashOut = $data->where('transaction_type', 1)->sum('amount');
            $cashIn = $data->where('transaction_type', 0)->sum('amount');
            $daterange = date('d/m/Y', strtotime(now()->toDateString()));
            if ($request->has('export')) {
                $name = 'transaction_report';
                $file_name = $name.'_'.date('d-M-Y').'.xlsx';

                return Excel::download(new AccountTransactionExport($cashIn, $cashOut), $file_name);
            } else {
                return view('backEnd.admin.report.account-trans', compact('cashIn', 'cashOut', 'daterange'));
            }
        }
    }

    public function product_report()
    {

        $data['products'] = Product::latest()->paginate(30);

        return view('backEnd.admin.report.product-stock', compact('data'));
    }

    public function productReport()
    {

        $products = Product::with('get_order_products')->where('status', 1)->get();
        $productReports = [];
        foreach ($products as $key => $product) {
            $pending = 0;
            $confirmed = 0;
            $processing = 0;
            $hold = 0;
            $printed = 0;
            $packaging = 0;
            $on_delivery = 0;
            $delivered = 0;
            $cancelled = 0;
            $returned = 0;
            if ($product->get_order_products->count() > 0) {
                foreach ($product->get_order_products as $key => $item) {
                    // dd($item->get_order->status);
                    if ($item->get_order->status) {
                        if ($item->get_order->status == 1) {
                            $pending += 1;
                        }
                        if ($item->get_order->status == 2) {
                            $confirmed += 1;
                        }
                        if ($item->get_order->status == 3) {
                            $processing += 1;
                        }
                        if ($item->get_order->status == 4) {
                            $hold += 1;
                        }
                        if ($item->get_order->status == 5) {
                            $printed += 1;
                        }
                        if ($item->get_order->status == 6) {
                            $packaging += 1;
                        }
                        if ($item->get_order->status == 7) {
                            $on_delivery += 1;
                        }
                        if ($item->get_order->status == 8) {
                            $delivered += 1;
                        }
                        if ($item->get_order->status == 9) {
                            $cancelled += 1;
                        }
                        if ($item->get_order->status == 10) {
                            $returned += 1;
                        }
                    }
                }
                $productReports[$product->name] = [
                    'pending' => $pending,
                    'confirmed' => $confirmed,
                    'processing' => $processing,
                    'hold' => $hold,
                    'printed' => $printed,
                    'packaging' => $packaging,
                    'on_delivery' => $on_delivery,
                    'delivered' => $delivered,
                    'cancelled' => $cancelled,
                    'returned' => $returned,
                ];
            } else {
                $productReports[$product->name] = [
                    'pending' => 0,
                    'confirmed' => 0,
                    'processing' => 0,
                    'hold' => 0,
                    'printed' => 0,
                    'packaging' => 0,
                    'on_delivery' => 0,
                    'delivered' => 0,
                    'cancelled' => 0,
                    'returned' => 0,
                ];
            }
        }

        return view('backEnd.admin.report.product', compact('productReports'));
    }

    // courierReport
    public function courierReport()
    {
        $couriers = Courier::with('get_orders')->get();
        foreach ($couriers as $key => $courier) {
            $pending = 0;
            $confirmed = 0;
            $processing = 0;
            $hold = 0;
            $printed = 0;
            $packaging = 0;
            $on_delivery = 0;
            $delivered = 0;
            $cancelled = 0;
            $returned = 0;
            if ($courier->get_orders->count() > 0) {
                foreach ($courier->get_orders as $key => $item) {
                    if ($item->status == 1) {
                        $pending += 1;
                    }
                    if ($item->status == 2) {
                        $confirmed += 1;
                    }
                    if ($item->status == 3) {
                        $processing += 1;
                    }
                    if ($item->status == 4) {
                        $hold += 1;
                    }
                    if ($item->status == 5) {
                        $printed += 1;
                    }
                    if ($item->status == 6) {
                        $packaging += 1;
                    }
                    if ($item->status == 7) {
                        $on_delivery += 1;
                    }
                    if ($item->status == 8) {
                        $delivered += 1;
                    }
                    if ($item->status == 9) {
                        $cancelled += 1;
                    }
                    if ($item->status == 10) {
                        $returned += 1;
                    }
                }
                $courierReports[$courier->courier_name] = [
                    'pending' => $pending,
                    'confirmed' => $confirmed,
                    'processing' => $processing,
                    'hold' => $hold,
                    'printed' => $printed,
                    'packaging' => $packaging,
                    'on_delivery' => $on_delivery,
                    'delivered' => $delivered,
                    'cancelled' => $cancelled,
                    'returned' => $returned,
                ];
            } else {
                $courierReports[$courier->courier_name] = [
                    'pending' => 0,
                    'confirmed' => 0,
                    'processing' => 0,
                    'hold' => 0,
                    'printed' => 0,
                    'packaging' => 0,
                    'on_delivery' => 0,
                    'delivered' => 0,
                    'cancelled' => 0,
                    'returned' => 0,
                ];
            }
        }

        return view('backEnd.admin.report.courier', compact('courierReports'));
    }
}
