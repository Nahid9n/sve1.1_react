<?php

namespace App\Http\Controllers;

use App\FlashDeal;
use App\FlashDealProduct;
use App\Product;
use App\Theme;
use Illuminate\Http\Request;

class FlashDealController extends Controller
{
    public function index()
    {
        $deals = FlashDeal::orderBy('start_time', 'desc')->paginate(20);

        return view('backEnd.admin.flash-deals.index', compact('deals'));
    }

    public function create()
    {

        $products = Product::where([['status', 1], ['theme_id', activeThemeData()->id]])->get();

        return view('backEnd.admin.flash-deals.create', compact('products'));
    }

    public function store(Request $r)
    {

        // dd($r->all());
        $deal = FlashDeal::create([
            'title' => $r->title,
            'discount' => $r->discount,
            'discount_type' => $r->discount_type,
            'start_time' => $r->start_time,
            'end_time' => $r->end_time,
            'status' => $r->status,
        ]);

        if ($r->product_ids) {
            foreach ($r->product_ids as $id) {
                FlashDealProduct::create([
                    'flash_deal_id' => $deal->id,
                    'product_id' => $id,
                ]);
            }
        }

        return redirect()->route('admin.flash.deals.index')
            ->with('success', 'Flash Deal Created Successfully');
    }

    public function edit($id)
    {
        $deal = FlashDeal::with('products')->findOrFail($id);
        $products = Product::where([['status', 1], ['theme_id', activeThemeData()->id]])->get();

        return view('backEnd.admin.flash-deals.edit', compact('deal', 'products'));
    }

    public function update(Request $request, $id)
    {

        $deal = FlashDeal::findOrFail($id);
        $deal->update([
            'title' => $request->title,
            'discount' => $request->discount,
            'discount_type' => $request->discount_type,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => $request->status,
        ]);

        if ($request->product_ids) {
            FlashDealProduct::where('flash_deal_id', $deal->id)->delete();
            foreach ($request->product_ids as $id) {
                FlashDealProduct::create([
                    'flash_deal_id' => $deal->id,
                    'product_id' => $id,
                ]);
            }
        }

        return redirect()->route('admin.flash.deals.index')->with('success', 'Flash deal updated.');
    }

    public function toggleStatus($id)
    {
        $deal = FlashDeal::findOrFail($id);
        $deal->status = ! $deal->status;
        $deal->save();

        return redirect()->route('admin.flash.deals.index')->with('success', 'Flash deal status updated.');
    }

    public function destroy(FlashDeal $flashDeal)
    {
        // dd($flashDeal);
        $flashDeal->delete();

        return back()->with('success', 'Deleted.');
    }
}
