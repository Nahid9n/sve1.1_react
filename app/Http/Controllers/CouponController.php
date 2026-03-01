<?php

namespace App\Http\Controllers;

use App\Coupon;
use App\Product;
use App\Theme;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->get();

        return view('backEnd.admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $products = Product::where([['status', 1], ['theme_id', activeThemeData()->id]])->get();

        return view('backEnd.admin.coupons.create', compact('products'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'code' => 'required|unique:coupons,code',
            'type' => 'required|in:fixed,percentage',
            'amount' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'apply_on' => 'required|in:cart,product,shipping,payment,first_time',
            'product_ids' => 'nullable|array',
            'payment_method' => 'nullable|string',
            'usage_limit' => 'nullable|integer|min:0',
            'per_user_limit' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|boolean',
        ]);

        Coupon::create($request->all());

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        $products = Product::where([['status', 1], ['theme_id', activeThemeData()->id]])->get();

        return view('backEnd.admin.coupons.edit', compact('coupon', 'products'));
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'code' => 'required|unique:coupons,code,' . $id,
            'type' => 'required|in:fixed,percentage',
            'amount' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'apply_on' => 'required|in:cart,product,shipping,payment,first_time',
            'product_ids' => 'nullable|array',
            'payment_method' => 'nullable|string',
            'usage_limit' => 'nullable|integer|min:0',
            'per_user_limit' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|boolean',
        ]);

        $coupon->update($request->all());

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        // dd($flashDeal);
        $coupon->delete();

        return back()->with('success', 'Deleted.');
    }

    public function toggleStatus($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->status = ! $coupon->status;
        $coupon->save();

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon status updated.');
    }
}
