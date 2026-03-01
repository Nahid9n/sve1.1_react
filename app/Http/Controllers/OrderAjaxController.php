<?php

namespace App\Http\Controllers;

use App\Attribute;
use App\AttributeItem;
use App\Product;
use Illuminate\Http\Request;

class OrderAjaxController extends Controller
{
    // Non-variant product row
    public function getProducts(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $price = $product->sale_price ?? $product->regular_price;
        $row = view('backEnd.admin.orders.partials.product_row', compact('product', 'price'))->render();

        return response($row);
    }

    public function getProductModal(Request $request)
    {
        $product = Product::with('get_variants.items')->findOrFail($request->id);
        $selected = collect($request->choice_variants ?? [])->map(fn ($i) => (int) $i)->toArray();
        $html = view('backEnd.admin.orders.partials.variant_modal_body', compact('product', 'selected'))->render();

        return response($html);
    }

    public function getModalVariant(Request $request)
    {
        // dd($request->all());
        $product = Product::findOrFail($request->id);
        $choice = $request->choice_variants ?? [];
        $qty = max(1, (int) ($request->qty ?? 1));

        $variants = $product->get_variants()->where('variant', implode('-', $choice))->with('items')->first();
        // dd($variants);
        if ($variants) {
            $price = $variants->sale_price ?? $variants->regular_price ?? $product->regular_price;
            $sku = $variants->sku;
            $stock = $variants->stock;
            $variant_name = $variants->items->map(fn ($it) => $it->name)->implode(', ');
            $variant_choice = implode(',', $choice);

            $row = view('backEnd.admin.orders.partials.product_row_variant', compact(
                'product',
                'price',
                'sku',
                'stock',
                'qty',
                'variant_name',
                'variant_choice'
            ))->render();

            return response($row);
        }
    }

    // Used to fetch price/sku when selecting attribute checkboxes (live update)
    // public function getVariant(Request $request)
    // {
    //     //dd($request->all());
    //     $choice = $request->choice_variants ?? [];
    //     $product = Product::findOrFail($request->id);
    //     $variants = $product->get_variants()->where('variant', implode('-', $choice))->with('items')->first();

    //     if ($variants) {
    //         $price =  $variants->sale_price ??  $variants->regular_price ?? $product->regular_price;
    //         $sku =  $variants->sku;
    //         $stock =  $variants->stock;
    //     }

    //     return response()->json([
    //         'sku' => null,
    //         'price' => $product->sale_price ?? $product->regular_price,
    //         'subtotal' => ($product->sale_price ?? $product->regular_price) * max(1, (int)$request->quantity),
    //         'stock' => 0,
    //         'html' => ''
    //     ]);
    // }

    // protected function buildVariantLabel($choice)
    // {
    //     $items = AttributeItem::whereIn('id', $choice)->get()->pluck('name')->toArray();
    //     return implode('-', $items);
    // }
}
