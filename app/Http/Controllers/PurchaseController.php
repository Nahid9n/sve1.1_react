<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountTransaction;
use App\Product;
use App\ProductVariant;
use App\Purchase;
use App\PurchaseItem;
use App\Supplier;
use App\Theme;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        $data = Purchase::with('get_supplier')->latest()->paginate(20);

        return view('backEnd.admin.purchase.index', compact('data'));
    }

    public function create()
    {
        $products = Product::where(['is_combo' => 0, 'is_package' => 0, 'status' => 1, 'theme_id' => activeThemeData()->id])->pluck('name', 'id');
        $suppliers = Supplier::where('status', 1)->pluck('name', 'id');
        $accounts = Account::where('status', 1)->get();

        return view('backEnd.admin.purchase.create', compact('products', 'suppliers', 'accounts'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'supplier_id' => 'required',
            'purchase_date' => 'required',
            'status' => 'required',
            'account_id' => 'required',
            'paid_amount' => 'required',
            'product_id' => 'required',
            'subtotal' => 'required',
            'total' => 'required',
        ]);

        $purchase = Purchase::create($request->all() + [
            'purchase_date' => date('Y-m-d', strtotime($request->purchase_date)),
        ]);

        foreach ($request->product_id as $key => $product_id) {
            $product = Product::with('get_variants')->findOrFail($product_id);
            if (count($product->get_variants) > 0) {
                $product_attribute = ProductVariant::where('sku', $request->sku[$key])->first();
                $product_attribute->stock = $product_attribute->stock + $request->purchase_quantity[$key];
                $product_attribute->purchase_price = $request->purchase_cost[$key] ?? $product_attribute->purchase_price;
                $product_attribute->sale_price = $request->sell_price[$key] ?? $product_attribute->sale_price;
                $product_attribute->regular_price = $request->regular_price[$key] ?? $product_attribute->regular_price;
                $product_attribute->update();
                $product->stock = $product->stock + $request->purchase_quantity[$key];
                $product->purchase_price = $request->purchase_cost[$key] ?? $product->purchase_price;
                $product->sale_price = $request->sell_price[$key] ?? $product->sale_price;
                $product->regular_price = $request->regular_price[$key] ?? $product->regular_price;
                $product->update();
            } else {
                $product->stock = $product->stock + $request->purchase_quantity[$key];
                $product->purchase_price = $request->purchase_cost[$key] ?? $product->purchase_price;
                $product->sale_price = $request->sell_price[$key] ?? $product->sale_price;
                $product->regular_price = $request->regular_price[$key] ?? $product->regular_price;
                $product->update();
            }

            PurchaseItem::create([
                'product_id' => $product_id,
                'purchase_id' => $purchase->id,
                'sku' => $request->sku[$key],
                'product_quantity' => $request->purchase_quantity[$key],
                'purchase_cost' => $request->purchase_cost[$key],
                'regular_price' => $request->regular_price[$key],
                'sell_price' => $request->sell_price[$key],
                'total' => $request->single_purchase_total[$key],
            ]);
        }
        Account::find($request->account_id)->decrement('balance', $request->paid_amount);

        AccountTransaction::create([
            'purchase_id' => $purchase->id,
            'account_id' => $request->account_id,
            'amount' => $request->paid_amount,
            'transaction_type' => 1,
            'purpose' => $request->note,
        ]);

        return redirect()->route('admin.purchase')->with('success', 'Product purchase successfully');
    }

    public function edit($id)
    {
        $data = Purchase::with('get_purchase_items')->findOrFail($id);
        $products = Product::where(['is_combo' => 0, 'is_package' => 0, 'status' => 1, 'theme_id' => activeThemeData()->id])->pluck('name', 'id');
        $suppliers = Supplier::where('status', 1)->pluck('name', 'id');
        $accounts = Account::where('status', 1)->get();

        return view('backEnd.admin.purchase.edit', compact('data', 'products', 'suppliers', 'accounts'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        // $request->validate([
        //     'supplier_id'       => 'required',
        //     'purchase_date'     => 'required',
        //     'status'            => 'required',
        //     'payment_method_id' => 'required',
        //     'paid_amount'       => 'required',
        //     'product_id'        => 'required',
        //     'subtotal'          => 'required',
        //     'total'             => 'required',
        // ]);

        // dd($request->all());

        PurchaseItem::where('purchase_id', $id)->delete();

        $purchase = Purchase::findOrFail($id);
        $purchase->update($request->all() + [
            'purchase_date' => date('Y-m-d', strtotime($request->purchase_date)),
        ]);
        foreach ($request->product_id as $key => $product_id) {
            $product = Product::with('get_variants')->findOrFail($product_id);

            if (count($product->get_variants) > 0) {
                $product_attribute = ProductVariant::where('sku', $request->sku[$key])->first();
                $product_attribute->stock = $product_attribute->stock + $request->purchase_quantity[$key];
                $product_attribute->purchase_price = $request->purchase_cost[$key] ?? $product_attribute->purchase_price;
                $product_attribute->sale_price = $request->sell_price[$key] ?? $product_attribute->sale_price;
                $product_attribute->regular_price = $request->regular_price[$key] ?? $product_attribute->regular_price;
                $product_attribute->update();
                $product->stock = $product->stock + $request->purchase_quantity[$key];
                $product->purchase_price = $request->purchase_cost[$key] ?? $product->purchase_price;
                $product->sale_price = $request->sell_price[$key] ?? $product->sale_price;
                $product->regular_price = $request->regular_price[$key] ?? $product->regular_price;
                $product->update();
            } else {
                $product->stock = $product->stock + $request->purchase_quantity[$key];
                $product->purchase_price = $request->purchase_cost[$key] ?? $product->purchase_price;
                $product->sale_price = $request->sell_price[$key] ?? $product->sale_price;
                $product->regular_price = $request->regular_price[$key] ?? $product->regular_price;
                $product->update();
            }

            PurchaseItem::create([
                'product_id' => $product_id,
                'purchase_id' => $id,
                'sku' => $request->sku[$key],
                'product_quantity' => $request->purchase_quantity[$key],
                'purchase_cost' => $request->purchase_cost[$key],
                'regular_price' => $request->regular_price[$key],
                'sell_price' => $request->sell_price[$key],
                'total' => $request->single_purchase_total[$key],
            ]);
        }
        Account::find($request->account_id)->decrement('balance', $request->paid_amount);

        AccountTransaction::create([
            'purchase_id' => $purchase->id,
            'account_id' => $request->account_id,
            'amount' => $request->paid_amount,
            'transaction_type' => 1,
            'purpose' => $request->note,
        ]);

        return redirect()->route('admin.purchase')->with('success', 'Product purchase update successfully.');
    }

    // status
    public function status(Request $request, $id)
    {
        // dd($request->all());
        $data = Purchase::find($id);
        $data->status = $request->status;
        $data->update();

        return back()->with('success', 'Status update successfully.');
    }

    public function delete(Request $request, $id)
    {
        Purchase::find($id)->delete();
        PurchaseItem::where('purchase_id', $id)->delete();

        return back()->with('success', 'Purchase delete successfully.');
    }

    public function ajaxGetPurchaseProduct(Request $request)
    {
        $product = Product::with('get_variants')->findOrFail($request->id);

        return view('backEnd.admin.purchase.partials.ajax_get_purchase_product', compact('product'))->render();
    }
}
