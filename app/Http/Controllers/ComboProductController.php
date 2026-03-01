<?php

namespace App\Http\Controllers;

use App\Category;
use App\ComboProduct;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ComboProductController extends Controller
{
    public function index()
    {
        $products = Product::where('is_combo', 1)->latest()->paginate(20);

        return view('backEnd.admin.combo-products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('status', 1)->get();
        $products = Product::where(['is_combo' => 0, 'status' => 1])->get();

        return view('backEnd.admin.combo-products.create', compact('categories', 'products'));
    }

    // AJAX: SKU unique check
    public function checkSku(Request $request)
    {
        // dd($request->all());
        // $sku = $request->get('sku');
        // $exists = Product::where('sku', $sku)->exists();
        // return response()->json(['exists' => $exists]);
    }

    // AJAX: fetch selected products details
    public function fetchProductsDetails(Request $request)
    {
        $ids = $request->get('ids', []);
        $products = Product::whereIn('id', $ids)->get(['id', 'name', 'purchase_price', 'regular_price', 'sale_price', 'sku', 'stock', 'package_qty']);

        // dd($products);
        return response()->json(['products' => $products]);
    }

    public function store(Request $request)
    {

        // dd($request->all());

        DB::beginTransaction();
        try {

            $image_id = uploadFile($request->file('image') ?? null, 1000, 1000);
            $gallery_image_ids = uploadMultipleFile($request->file('gallery_image') ?? null, 1000, 1000);
            $stocks = $request->combo_product_ids;
            $stocks = collect($stocks)->map(function ($item) {
                // sum the quantity of each product
                return Product::find($item)->stock;
            })->sum();
            // dd($stocks);
            $input = array_merge(
                $request->all(),
                [
                    'thumb' => $image_id,
                    'image' => $image_id,
                    'gallery_images' => $gallery_image_ids,
                    'slug' => Str::slug($request->name),
                    'sku' => str_replace(' ', '-', strtolower($request->sku)),
                    'stock' => $stocks,
                    'has_variant' => 0,
                    'is_combo' => 1,
                ]
            );

            $product = Product::create($input);
            if ($request->has('category_id')) {
                foreach ($request->category_id as $cat) {
                    DB::table('category_products')->insert([
                        'category_id' => $cat,
                        'product_id' => $product->id,
                    ]);
                }
            }

            foreach ($request->combo_product_ids as $key => $product_id) {
                ComboProduct::create([
                    'combo_product_id' => $product->id,
                    'product_id' => $product_id,
                    'purchase_price' => $request->cp_purchase[$key],
                    'regular_price' => $request->cp_regular[$key],
                    'sale_price' => $request->cp_sale[$key],
                    'quantity' => $request->cp_qty[$key],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.combo-products.index')->with('success', 'Combo product created.');
        } catch (\Exception $e) {
            // dd($e);
            DB::rollBack();

            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $product = Product::with('comboItems')->findOrFail($id);
        $selectedProducts = $product->comboItems->pluck('product_id')->toArray();
        $cats = $product->get_category_products->pluck('category_id')->toArray();
        $categories = Category::where('status', 1)->get();
        // available child products (exclude combo products)
        $products = Product::where(['is_combo' => 0, 'status' => 1])->get();

        return view('backEnd.admin.combo-products.edit', compact('product', 'categories', 'products', 'cats', 'selectedProducts'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            if ($request->has('image')) {
                $image_id = uploadFile($request->file('image'), 1000, 1000);
                if ($product->image) {
                    deleteFile($product->image);
                }
            } else {
                $image_id = $product->image;
            }

            if ($request->has('gallery_image')) {
                if ($product->gallery_images) {
                    deleteMultipleFiles($product->gallery_images);
                }
                $gallery_image_ids = uploadMultipleFile($request->file('gallery_image'), 1000, 1000);
            } else {
                $gallery_image_ids = $product->gallery_images;
            }

            $stocks = $request->combo_product_ids;
            $stocks = collect($stocks)->map(function ($item) {
                // sum the quantity of each product
                return Product::find($item)->stock;
            })->sum();

            $input = array_merge(
                $request->all(),
                [
                    'thumb' => $image_id,
                    'image' => $image_id,
                    'gallery_images' => $gallery_image_ids,
                    'slug' => Str::slug($request->name),
                    'sku' => str_replace(' ', '-', strtolower($request->sku)),
                    'stock' => $stocks,
                    'has_variant' => 0,
                    'is_combo' => 1,
                ]
            );
            $product->update($input);
            $product->get_categories()->sync($request->category_id);

            // ✅ Sync combo product items
            ComboProduct::where('combo_product_id', $product->id)->delete();

            foreach ($request->combo_product_ids as $key => $comboId) {
                ComboProduct::create([
                    'combo_product_id' => $product->id,
                    'product_id' => $comboId,
                    'purchase_price' => $request->cp_purchase[$key],
                    'regular_price' => $request->cp_regular[$key],
                    'sale_price' => $request->cp_sale[$key],
                    'quantity' => $request->cp_qty[$key],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.combo-products.index')
                ->with('success', 'Combo product updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        // deleting product will cascade to combo_products due to FK
        $product->delete();

        return redirect()->route('admin.combo-products.index')->with('success', 'Combo deleted.');
    }
}
