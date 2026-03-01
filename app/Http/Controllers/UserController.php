<?php

namespace App\Http\Controllers;

use App\Order;
use App\Theme;
use App\User;
use App\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('get_orders');

        // search filter
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $data = $query->orderByDesc('id')->paginate(20)
            ->withQueryString(); // pagination e search thakbe

        return view('backEnd.admin.customers.index', compact('data'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|unique:users,phone',
        ]);
        $input = $request->all();
        $input['password'] = Hash::make($request->password);
        User::create($input);

        return back()->with('success', 'Customer added successfully.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'phone' => 'required|unique:users,phone,' . $request->id,
        ]);

        $input = $request->only('name', 'phone', 'email', 'status');
        if ($request->has('password')) {
            $input['password'] = Hash::make($request->password);
        }
        User::findOrFail($request->id)->update($input);

        return back()->with('success', 'Customer updated successfully.');
    }

    public function status($id, $status)
    {
        User::findOrFail($id)->update(['status' => $status]);

        return back()->with('success', 'Customer status updated successfully.');
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();

        return back()->with('success', 'Customer deleted successfully.');
    }

    public function customerOrder(Request $request)
    {
//        dd($request->all());
        $data = User::with('get_orders')->where('id', $request->id)->first();
        $orders = $data->get_orders;
        return view('backEnd.admin.customers.orders', compact('orders'))->render();
    }

    // frontend customer

    public function customerDashboard(Request $request, Theme $theme)
    {
        // dd(Auth::guard('web')->user()->id);
        // dd($theme);
        $data['orders'] = Order::query();
        // total orders count
        $data['total_orders'] = $data['orders']->where('customer_id', Auth::guard('web')->user()->id)->count();
        $data['total_orders_amount'] = $data['orders']->where([['customer_id', Auth::guard('web')->user()->id]])->sum('total');
        // dd($data['total_orders_amount']);

        // orders
        $data['orders'] = $data['orders']->with('get_products')->where([['customer_id', Auth::guard('web')->user()->id]])->orderBy('id', 'desc')->paginate(10);
        // dd($theme);

        // $currentTheme = $theme;
        return view('frontEnd.' . $theme->path . '.customer.index', compact('data', 'theme'));
    }

    public function customerOrders(Request $request, $theme)
    {
        $data = Order::with('get_products')->where([['customer_id', Auth::guard('web')->user()->id], ['status', 8]])->orderBy('id', 'desc')->paginate(10);
        // dd($data);
        $currentTheme = $theme;

        return view('frontEnd.theme-1.customer.orders', compact('data', 'currentTheme'));
    }

    public function customerProfile(Request $request, Theme $theme)
    {

        $customer = User::where('id', Auth::guard('web')->user()->id)->first();

        return view('frontEnd.' . $theme->path . '.customer.profile', compact('customer', 'theme'));
    }

    public function customerProfileUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        $data = User::find(Auth::guard('web')->user()->id);
        $data->update($request->all());

        return back()->with('success', 'Profile Updated Successfully');
    }

    // change password
    public function customerPasswordChange(Request $request, Theme $theme)
    {
        return view('frontEnd.' . $theme->path . '.customer.change_password', compact('theme'));
    }

    public function customerPasswordChangePost(Request $request, Theme $theme)
    {
        $request->validate([
            'current_password' => 'required',
            'email' => 'required|email',
            'new_password' => 'required|min:6',
        ]);

        $user = Auth::guard('web')->user();
        // dd($user);

        // current password check
        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect',
            ]);
        }

        // OTP generate
        $otp = rand(100000, 999999);

        $customer = User::updateOrCreate(
            ['id' => $user->id],
            [
                'otp_code' => $otp,
                'email' => $request->email,
                'otp_expires_at' => Carbon::now()->addMinutes(5),
            ]
        );

        // dd($customer);

        // Mail send
        Mail::raw("Your OTP for password change is: $otp", function ($message) use ($customer) {
            $message->to($customer->email)
                ->subject('Password Change OTP');
        });

        session([
            'new_password' => $request->new_password,
        ]);

        if ($theme->is_active == 1) {
            return redirect()->route('customer.password.otp');
        } else {
            return redirect()->route('theme.customer.password.otp', ['path' => $theme->path]);
        }
    }

    // customerPasswordOtp
    public function customerPasswordOtp(Request $request, Theme $theme)
    {
        return view('frontEnd.' . $theme->path . '.customer.password-otp', compact('theme'));
    }

    public function customerPasswordOtpVerify(Request $request, Theme $theme)
    {
        // dd($request->all());
        $request->validate([
            'otp' => 'required',
        ]);

        $user = Auth::guard('web')->user();
        // dd($user);

        $otpData = User::where('id', $user->id)
            ->where('otp_code', $request->otp)
            ->where('otp_expires_at', '>=', now())
            ->first();
        // dd($otpData);

        if (! $otpData) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP']);
        }

        $user->update([
            'password' => Hash::make(session('new_password')),
        ]);

        // cleanup
        // User::where('id', $user->id)->delete();
        session()->forget('new_password');

        if ($theme->is_active == 1) {
            return redirect()->route('customer.login')->with('success', 'Password changed successfully!');
        } else {
            return redirect()->route('theme.customer.login', ['path' => $theme->path])->with('success', 'Password changed successfully!');
        }
    }

    // customer register
    public function customerRegister(Request $request, Theme $theme)
    {
        // dd($theme);
        return view('frontEnd.' . $theme->path . '.customer.register', compact('theme'));
    }

    public function customerRegisterPost(Request $request, Theme $theme)
    {
        // Validation rules
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            $user = User::create([
                'name' => $validatedData['name'],
                'phone' => $validatedData['phone'],
                'password' => Hash::make($validatedData['password']),
            ]);

            Auth::guard('web')->login($user);

            return redirect()
                ->route('customer.dashboard', ['path' => $theme->path])
                ->with('success', 'Registration Successful!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Registration Failed. Try again!');
        }
    }

    // customer login
    public function customerLogin(Request $request, Theme $theme)
    {
        // dd($theme);
        return view('frontEnd.' . $theme->path . '.customer.login', compact('theme'));
    }

    public function customerLoginPost(Request $request, Theme $theme)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            $loginInput = trim($request->login);
            $loginField = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
            // dd($loginField);

            if (Auth::guard('web')->attempt([
                $loginField => $request->login,
                'password' => $request->password,
            ])) {
                return redirect()->intended(
                    $theme->is_active == 1
                        ? route('customer.dashboard')
                        : route('theme.customer.dashboard', ['path' => $theme->path])
                );
            }

            return back()
                ->withInput($request->only('login'))
                ->with('error', 'Invalid Credentials');
        } catch (\Exception $e) {
            return back()
                ->withInput($request->only('login'))
                ->with('error', 'Something went wrong. Try again.');
        }
    }

    public function customerLogout(Request $request, Theme $theme)
    {
        // dd($theme);
        Auth::guard('web')->logout();

        // $request->session()->invalidate();

        // $request->session()->regenerateToken();

        if ($theme->is_active == 1) {
            return redirect()->route('customer.login');
        } else {
            return redirect()->route('theme.customer.login', ['path' => $theme->path]);
        }

        // if ($response = $this->loggedOut($request)) {
        //     return $response;
        // }

    }

    // wishlist
    public function wishlist(Request $request, Theme $theme)
    {
        // visitor()->visit();
        if (Auth::guard('web')->check()) {
            $data = Wishlist::with('get_product')->where('user_id', Auth::guard('web')->user()->id)->orderBy('id', 'desc')->latest()->get();
        } else {
            if ($theme->is_active == 1) {
                return redirect()->route('customer.login');
            } else {
                return redirect()->route('theme.customer.login', ['path' => $theme->path]);
            }
        }

        // dd($data);
        return view('frontEnd.' . $theme->path . '.customer.wishlist', compact('data', 'theme'));
    }

    public function wishlistTocart(Request $request)
    {
        // dd($request->all());
        $data = Wishlist::with('get_product')->where([['id', $request->id], ['user_id', Auth::guard('web')->user()->id]])->first();
        // dd($data);
        \Cart::add([
            'id' => $data->sku,
            'name' => $data->get_product->name,
            'price' => $data->get_product->sale_price > 0 ? $data->get_product->sale_price : $data->get_product->price,
            'quantity' => 1,
            'attributes' => $data->attributes,
            'associatedModel' => $data->get_product,
        ]);
        // dd(\Cart::getContent());
        $data->delete();

        return response()->json(['success' => 200]);
    }

    // wishlistDelete
    public function wishlistDelete(Request $request)
    {
        $data = Wishlist::where([['id', $request->id], ['user_id', Auth::guard('web')->user()->id]])->first();
        $data->delete();

        return response()->json(['success' => 200]);
    }

    //instructor.view
    public function instructionView(Request $request, Theme $theme)
    {
        return view('frontEnd.instruction.index', compact('theme'));
    }
}
