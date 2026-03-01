<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Expense;
use App\Order;
use App\OrderAssign;
use App\OrderProduct;
use App\Product;
use App\Services\Courier\PathaoCourier;
use App\Services\Courier\RedxCourier;
use App\Services\Courier\SteadfastCourier;
use App\Subscribe;
use App\Theme;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
    // public function __construct()
    // {
    //     if (file_exists(base_path('vendor/laravel/framework/src/Illuminate/license.dat'))) {
    //         $file = fopen(base_path() . "/vendor/laravel/framework/src/Illuminate/license.dat", 'r');
    //         $read = fgets($file);
    //         fclose($file);
    //         if ($read != str_replace('www.', '', $_SERVER['SERVER_NAME'])) {
    //             function getIPAddress()
    //             {
    //                 if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    //                     $ip = $_SERVER['HTTP_CLIENT_IP'];
    //                 } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    //                     $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    //                 } else {
    //                     $ip = $_SERVER['REMOTE_ADDR'];
    //                 }
    //                 return $ip;
    //             }

    //             $ip = getIPAddress();
    //             if ($ip == '::1') {
    //                 $ip = gethostname();
    //             }
    //             $client = new \GuzzleHttp\Client();
    //             $url = 'https://license.prodevsltd.com/activation/attempt/store';
    //             $url2 = 'http://' . $_SERVER['SERVER_NAME'];
    //             $form_params = [
    //                 'ip' => $ip,
    //                 'parent' => $file,
    //                 'url' => $url2,
    //             ];
    //             $response = $client->post($url, ['form_params' => $form_params]);
    //             $response->getBody()->getContents();
    //             dd('This Product Is Pirated. Please Contact with ask@prodevsltd.com or www.prodevsltd.com');
    //         }
    //     } else {
    //         function getIPAddress()
    //         {
    //             if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    //                 $ip = $_SERVER['HTTP_CLIENT_IP'];
    //             } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    //                 $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    //             } else {
    //                 $ip = $_SERVER['REMOTE_ADDR'];
    //             }
    //             return $ip;
    //         }

    //         $parent = env('APP_NAME') . ', ' . env('APP_URL');

    //         $ip = getIPAddress();
    //         if ($ip == '::1') {
    //             $ip = gethostname();
    //         }
    //         $client = new \GuzzleHttp\Client();
    //         $url = 'https://license.prodevsltd.com/activation/attempt/store';
    //         $url2 = 'http://' . $_SERVER['SERVER_NAME'];
    //         $form_params = [
    //             'ip' => $ip,
    //             'parent' => $parent,
    //             'url' => $url2,
    //         ];
    //         $response = $client->post($url, ['form_params' => $form_params]);
    //         $response->getBody()->getContents();
    //         dd('This Product Is Pirated. Please Contact with ask@prodevsltd.com or www.prodevsltd.com');
    //     }
    // }

    public function dashboard()
    {

        $today = Carbon::today();
        $activeThemeId = Theme::where('is_active', 1)->value('id');

        $totalSales = Order::where('status', 9)->sum('total');
        $totalProductCost = OrderProduct::whereHas('get_order', function ($q) {
            $q->where('status', 9);
        })->sum(DB::raw('purchase_price * qty'));

        $data['gross_profit'] = $totalSales - $totalProductCost;
        $data['total_expense'] = Expense::sum('amount');
        $data['net_profit'] = $data['gross_profit'] - $data['total_expense'];

        $data['total_customer'] = User::count();
        $data['total_product'] = Product::where('theme_id', $activeThemeId)->count();
        $data['total_staff'] = Admin::count();
        $data['total_order'] = Order::count();
        $statusCounts = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        //  dd($statusCounts);

        $data['total_pending_order'] = $statusCounts[1] ?? 0;
        $data['total_confirm_order'] = $statusCounts[2] ?? 0;
        $data['total_processing_order'] = $statusCounts[3] ?? 0;
        $data['total_hold_order'] = $statusCounts[4] ?? 0;
        $data['total_printed_order'] = $statusCounts[5] ?? 0;
        $data['total_packaging_order'] = $statusCounts[6] ?? 0;
        $data['total_courier_entry_order'] = $statusCounts[7] ?? 0;
        $data['total_on_delivery_order'] = $statusCounts[8] ?? 0;
        $data['total_delivered_order'] = $statusCounts[9] ?? 0;
        $data['total_cancelled_order'] = $statusCounts[10] ?? 0;
        $data['total_returned_order'] = $statusCounts[11] ?? 0;
        $data['recent_orders'] = Order::select('id', 'order_date', 'customer_name', 'customer_phone', 'total', 'status')
            ->latest()
            ->limit(10)
            ->get();

        $employeeId = Auth::guard('admin')->id();
        $todayAssignedOrders = OrderAssign::where('employee_id', $employeeId)
            ->whereHas('get_order', function ($q) use ($today) {
                $q->whereDate('order_date', $today);
            })
            ->with('get_order')
            ->get()
            ->groupBy('get_order.status');

        $data['today_all_orders'] = $todayAssignedOrders->flatten()->count();
        $data['today_pending_order'] = $todayAssignedOrders->get(1, collect())->count();
        $data['today_confirm_order'] = $todayAssignedOrders->get(2, collect())->count();
        $data['today_processing_order'] = $todayAssignedOrders->get(3, collect())->count();
        $data['today_hold_order'] = $todayAssignedOrders->get(4, collect())->count();
        $data['today_printed_order'] = $todayAssignedOrders->get(5, collect())->count();
        $data['today_packaging_order'] = $todayAssignedOrders->get(6, collect())->count();
        $data['today_courier_entry_order'] = $todayAssignedOrders->get(7, collect())->count();
        $data['today_on_delivery_order'] = $todayAssignedOrders->get(8, collect())->count();
        $data['today_delivered_order'] = $todayAssignedOrders->get(9, collect())->count();
        $data['today_cancelled_order'] = $todayAssignedOrders->get(10, collect())->count();
        $data['today_returned_order'] = $todayAssignedOrders->get(11, collect())->count();
        $data['top_selling_products'] = Product::where('theme_id', $activeThemeId)
            ->whereHas('get_order_products') // যেগুলায় order আছে শুধু সেগুলা
            ->withSum('get_order_products as total_sold', 'qty')
            ->orderByDesc('total_sold')
            ->get();

        // dd($data['top_selling_products']);
        return view('backEnd.admin.dashboard', compact('data'));
    }

    // change password
    public function change_pass()
    {
        return view('backEnd.admin.change_pass');
    }

    public function update_pass(Request $request)
    {

        $user_id = Auth::guard('admin')->user()->id;
        if (Hash::check($request->old_pass, Admin::find($user_id)->password)) {
            Admin::find($user_id)->update([
                'password' => Hash::make($request->password),
            ]);

            $this->guard_admin()->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return $this->loggedOut($request) ?: redirect()->route('admin.home')->with('success', 'Password Changed Successfully');
        } else {
            return back()->with('error', 'Incorrect Old Password');
        }
    }

    protected function loggedOut(Request $request) {}

    // ckeditor image upload
    public function ckeditorUpload(Request $request)
    {
        // dd($request->all());
        $file = $request->file('upload');
        $file_name = uniqid().'.'.$file->getClientOriginalExtension();
        $destinationPath = public_path('uploads');
        $file->move($destinationPath, $file_name);
        $url = 'uploads/'.$file_name;
        $msg = 'Image Uploaded Successfully';

        return response()->json(['fileName' => $file_name, 'uploaded' => 1, 'url' => asset($url)]);
    }

    // newsletter
    public function newsletter()
    {
        $data = Subscribe::orderBy('id', 'desc')->paginate(10);

        return view('backEnd.admin.newsletter.index', compact('data'));
    }

    // delete newsletter
    public function newsletterDelete(Request $request)
    {
        Subscribe::where('id', $request->id)->delete();

        return back()->with('success', 'Deleted Successfully');
    }

    // fraudChecker
    public function fraudChecker($id)
    {
        $order = Order::find($id);

        if (! $order) {
            return back()->with('error', 'Order not found');
        }

        if (! $order->customer_phone) {
            return back()->with('error', 'Customer phone not found');
        }

        $baseUrl = env('FROODLY_URL');
        $token   = env('FROODLY_TOKEN');

        if (! $baseUrl || ! $token) {
            return back()->with('error', 'Fraud API configuration missing');
        }

        $phone = $order->customer_phone;

        // ✅ Dispatch a job for async request so page is fast
        dispatch(function () use ($baseUrl, $token, $order, $phone) {
            try {
                $response = Http::withHeaders([
                    'X-API-TOKEN' => $token,
                    'Accept'      => 'application/json',
                ])->post(rtrim($baseUrl, '/') . '/api/check-courier', [
                    'phone' => $phone
                ]);

                $result = $response->json();

                if (isset($result['status']) && $result['status'] == true) {
                    $order->update([
                        'customer_activity' => $result['data'] ?? null,
                    ]);
                }

            } catch (\Exception $e) {
                \Log::error("FraudChecker Error: ".$e->getMessage());
            }
        });

        // Return immediately without waiting
        return back()->with('success', 'Fraud check is being processed in the background.');
    }

    // pathaoWebhook
    public function pathaoWebhook(Request $request)
    {
        // Get RAW JSON
        $json = file_get_contents('php://input');
        $object = json_decode($json, true);

        // Invalid JSON means Pathao will not retry
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response('Unsupported Media Type', 415);
        }

        // Step 1: Handle Pathao webhook integration test
        if (isset($object['event']) && $object['event'] === 'webhook_integration') {
            return response('Accepted', 202)
                ->header(
                    'X-Pathao-Merchant-Webhook-Integration-Secret',
                    'f3992ecc-59da-4cbe-a049-a13da2018d51'
                );
        }

        // Step 2: Process all other events
        $pathaoService = new PathaoCourier;
        $pathaoService->webhook($object);

        // Step 3: Must return 202 for ALL Pathao webhooks
        return response('Accepted', 202)
            ->header(
                'X-Pathao-Merchant-Webhook-Integration-Secret',
                'f3992ecc-59da-4cbe-a049-a13da2018d51'
            );
    }

    public function steadfastWebhook(Request $request)
    {
        // Get RAW JSON
        $json = file_get_contents('php://input');
        $object = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            exit(header('HTTP/1.0 415 Unsupported Media Type'));
        }
        $steadfastService = new SteadfastCourier;
        $steadfastService->webhook($object);
    }

    public function redxWebhook(Request $request)
    {

        $token = $request->query('token');
        if ($token !== env('REDX_WEBHOOK_TOKEN')) {
            return response()->json(['error' => 'Invalid token'], 403);
        }
        $json = file_get_contents('php://input');
        $object = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            exit(header('HTTP/1.0 415 Unsupported Media Type'));
        }
        $steadfastService = new RedxCourier;
        $steadfastService->webhook($object);
    }
}
