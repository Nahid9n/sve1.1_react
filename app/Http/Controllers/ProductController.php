<?php

namespace App\Http\Controllers;

use App\Attribute;
use App\AttributeItem;
use App\Category;
use App\Product;
use App\ProductVariant;
use App\ProductVariantItem;
use App\Theme;
use App\ThemeExtraField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    public function index(Request $request)
    {

        // $products = [
        //     [
        //         'sku' => 'GAD-001',
        //         'thumb' => null,
        //         'image' => null,
        //         'gallery_images' => null,
        //         'name' => 'Wireless Bluetooth Earbuds Pro',
        //         'slug' => 'wireless-bluetooth-earbuds-pro',
        //         'stock' => 50,
        //         'description' => 'High quality wireless earbuds with noise cancellation.',
        //         'purchase_price' => 1800,
        //         'regular_price' => 2500,
        //         'sale_price' => 2200,
        //         'status' => 1,
        //         'has_variant' => 0,
        //         'is_combo' => 0,
        //         'is_package' => 1,
        //         'package_qty' => null,
        //         'extra_fields' => null,
        //         'theme_id' => 1,
        //         'related_products' => null
        //     ],
        //     [
        //         'sku' => 'GAD-002',
        //         'thumb' => null,
        //         'image' => null,
        //         'gallery_images' => null,
        //         'name' => 'Smart Fitness Band X2',
        //         'slug' => 'smart-fitness-band-x2',
        //         'stock' => 40,
        //         'description' => 'Smart fitness band with heart rate and step tracking.',
        //         'purchase_price' => 1500,
        //         'regular_price' => 2200,
        //         'sale_price' => 1990,
        //         'status' => 1,
        //         'has_variant' => 0,
        //         'is_combo' => 0,
        //         'is_package' => 1,
        //         'package_qty' => null,
        //         'extra_fields' => null,
        //         'theme_id' => 1,
        //         'related_products' => null
        //     ],
        //     [
        //         'sku' => 'GAD-003',
        //         'thumb' => null,
        //         'image' => null,
        //         'gallery_images' => null,
        //         'name' => 'Fast Charging Power Bank 20000mAh',
        //         'slug' => 'fast-charging-power-bank-20000mah',
        //         'stock' => 60,
        //         'description' => '20000mAh power bank with fast charging support.',
        //         'purchase_price' => 2200,
        //         'regular_price' => 3000,
        //         'sale_price' => 2790,
        //         'status' => 1,
        //         'has_variant' => 0,
        //         'is_combo' => 0,
        //         'is_package' => 1,
        //         'package_qty' => null,
        //         'extra_fields' => null,
        //         'theme_id' => 1,
        //         'related_products' => null
        //     ],
        //     [
        //         'sku' => 'GAD-004',
        //         'thumb' => null,
        //         'image' => null,
        //         'gallery_images' => null,
        //         'name' => 'USB-C Multiport Hub Adapter',
        //         'slug' => 'usb-c-multiport-hub-adapter',
        //         'stock' => 35,
        //         'description' => 'USB-C hub with HDMI, USB, and Type-C ports.',
        //         'purchase_price' => 1700,
        //         'regular_price' => 2400,
        //         'sale_price' => 2190,
        //         'status' => 1,
        //         'has_variant' => 0,
        //         'is_combo' => 0,
        //         'is_package' => 1,
        //         'package_qty' => null,
        //         'extra_fields' => null,
        //         'theme_id' => 1,
        //         'related_products' => null
        //     ],
        //     [
        //         'sku' => 'GAD-005',
        //         'thumb' => null,
        //         'image' => null,
        //         'gallery_images' => null,
        //         'name' => 'Smart Watch Series S',
        //         'slug' => 'smart-watch-series-s',
        //         'stock' => 30,
        //         'description' => 'Smart watch with call, message and fitness tracking.',
        //         'purchase_price' => 3200,
        //         'regular_price' => 4200,
        //         'sale_price' => 3990,
        //         'status' => 1,
        //         'has_variant' => 0,
        //         'is_combo' => 0,
        //         'is_package' => 1,
        //         'package_qty' => null,
        //         'extra_fields' => null,
        //         'theme_id' => 1,
        //         'related_products' => null
        //     ],
        //     [
        //         'sku' => 'GAD-006',
        //         'thumb' => null,
        //         'image' => null,
        //         'gallery_images' => null,
        //         'name' => 'Portable Bluetooth Speaker Mini',
        //         'slug' => 'portable-bluetooth-speaker-mini',
        //         'stock' => 55,
        //         'description' => 'Mini portable speaker with deep bass.',
        //         'purchase_price' => 1200,
        //         'regular_price' => 1800,
        //         'sale_price' => 1600,
        //         'status' => 1,
        //         'has_variant' => 0,
        //         'is_combo' => 0,
        //         'is_package' => 1,
        //         'package_qty' => null,
        //         'extra_fields' => null,
        //         'theme_id' => 1,
        //         'related_products' => null
        //     ],
        //     [
        //         'sku' => 'GAD-007',
        //         'thumb' => null,
        //         'image' => null,
        //         'gallery_images' => null,
        //         'name' => 'Noise Cancelling Headphones',
        //         'slug' => 'noise-cancelling-headphones',
        //         'stock' => 45,
        //         'description' => 'Over-ear headphones with active noise cancellation.',
        //         'purchase_price' => 2800,
        //         'regular_price' => 3500,
        //         'sale_price' => 3200,
        //         'status' => 1,
        //         'has_variant' => 0,
        //         'is_combo' => 0,
        //         'is_package' => 1,
        //         'package_qty' => null,
        //         'extra_fields' => null,
        //         'theme_id' => 1,
        //         'related_products' => null
        //     ],
        //     [
        //         'sku' => 'GAD-008',
        //         'thumb' => null,
        //         'image' => null,
        //         'gallery_images' => null,
        //         'name' => 'Wireless Charging Pad',
        //         'slug' => 'wireless-charging-pad',
        //         'stock' => 70,
        //         'description' => 'Qi-enabled wireless charging pad for all smartphones.',
        //         'purchase_price' => 800,
        //         'regular_price' => 1200,
        //         'sale_price' => 999,
        //         'status' => 1,
        //         'has_variant' => 0,
        //         'is_combo' => 0,
        //         'is_package' => 1,
        //         'package_qty' => null,
        //         'extra_fields' => null,
        //         'theme_id' => 1,
        //         'related_products' => null
        //     ],
        //     [
        //         'sku' => 'GAD-009',
        //         'thumb' => null,
        //         'image' => null,
        //         'gallery_images' => null,
        //         'name' => 'HD Webcam with Microphone',
        //         'slug' => 'hd-webcam-with-microphone',
        //         'stock' => 25,
        //         'description' => '1080p HD webcam with built-in microphone.',
        //         'purchase_price' => 1500,
        //         'regular_price' => 2200,
        //         'sale_price' => 1990,
        //         'status' => 1,
        //         'has_variant' => 0,
        //         'is_combo' => 0,
        //         'is_package' => 1,
        //         'package_qty' => null,
        //         'extra_fields' => null,
        //         'theme_id' => 1,
        //         'related_products' => null
        //     ],
        //     [
        //         'sku' => 'GAD-010',
        //         'thumb' => null,
        //         'image' => null,
        //         'gallery_images' => null,
        //         'name' => 'Gaming Mouse RGB',
        //         'slug' => 'gaming-mouse-rgb',
        //         'stock' => 60,
        //         'description' => 'Ergonomic gaming mouse with RGB lights.',
        //         'purchase_price' => 1800,
        //         'regular_price' => 2500,
        //         'sale_price' => 2200,
        //         'status' => 1,
        //         'has_variant' => 0,
        //         'is_combo' => 0,
        //         'is_package' => 1,
        //         'package_qty' => null,
        //         'extra_fields' => null,
        //         'theme_id' => 1,
        //         'related_products' => null
        //     ],
        //     [
        //         'sku' => 'GAD-011',
        //         'thumb' => null,
        //         'image' => null,
        //         'gallery_images' => null,
        //         'name' => 'Mechanical Gaming Keyboard',
        //         'slug' => 'mechanical-gaming-keyboard',
        //         'stock' => 40,
        //         'description' => 'Mechanical keyboard with RGB backlight.',
        //         'purchase_price' => 3500,
        //         'regular_price' => 4500,
        //         'sale_price' => 3990,
        //         'status' => 1,
        //         'has_variant' => 0,
        //         'is_combo' => 0,
        //         'is_package' => 1,
        //         'package_qty' => null,
        //         'extra_fields' => null,
        //         'theme_id' => 1,
        //         'related_products' => null
        //     ],
        //     [
        //         'sku' => 'GAD-012',
        //         'thumb' => null,
        //         'image' => null,
        //         'gallery_images' => null,
        //         'name' => 'Action Camera 4K',
        //         'slug' => 'action-camera-4k',
        //         'stock' => 30,
        //         'description' => '4K action camera with waterproof case.',
        //         'purchase_price' => 5000,
        //         'regular_price' => 6500,
        //         'sale_price' => 5990,
        //         'status' => 1,
        //         'has_variant' => 0,
        //         'is_combo' => 0,
        //         'is_package' => 1,
        //         'package_qty' => null,
        //         'extra_fields' => null,
        //         'theme_id' => 1,
        //         'related_products' => null
        //     ],
        //     [
        //         'sku' => 'GAD-013',
        //         'thumb' => null,
        //         'image' => null,
        //         'gallery_images' => null,
        //         'name' => 'Smart Home WiFi Camera',
        //         'slug' => 'smart-home-wifi-camera',
        //         'stock' => 35,
        //         'description' => 'Smart WiFi camera with motion detection.',
        //         'purchase_price' => 3000,
        //         'regular_price' => 4200,
        //         'sale_price' => 3800,
        //         'status' => 1,
        //         'has_variant' => 0,
        //         'is_combo' => 0,
        //         'is_package' => 1,
        //         'package_qty' => null,
        //         'extra_fields' => null,
        //         'theme_id' => 1,
        //         'related_products' => null
        //     ],
        //     [
        //         'sku' => 'GAD-014',
        //         'thumb' => null,
        //         'image' => null,
        //         'gallery_images' => null,
        //         'name' => 'Laptop Cooling Pad',
        //         'slug' => 'laptop-cooling-pad',
        //         'stock' => 50,
        //         'description' => 'Cooling pad for laptops up to 17 inch.',
        //         'purchase_price' => 1200,
        //         'regular_price' => 1800,
        //         'sale_price' => 1600,
        //         'status' => 1,
        //         'has_variant' => 0,
        //         'is_combo' => 0,
        //         'is_package' => 1,
        //         'package_qty' => null,
        //         'extra_fields' => null,
        //         'theme_id' => 1,
        //         'related_products' => null
        //     ],
        //     [
        //         'sku' => 'GAD-015',
        //         'thumb' => null,
        //         'image' => null,
        //         'gallery_images' => null,
        //         'name' => 'Foldable Phone Stand Holder',
        //         'slug' => 'foldable-phone-stand-holder',
        //         'stock' => 80,
        //         'description' => 'Portable foldable stand for all smartphones.',
        //         'purchase_price' => 500,
        //         'regular_price' => 800,
        //         'sale_price' => 699,
        //         'status' => 1,
        //         'has_variant' => 0,
        //         'is_combo' => 0,
        //         'is_package' => 1,
        //         'package_qty' => null,
        //         'extra_fields' => null,
        //         'theme_id' => 1,
        //         'related_products' => null
        //     ],
        // ];

        // // Insert example
        // Product::insert($products);

        // dd($request->all());
        $products = Product::query();
        $status = null;
        if ($request->has('search')) {
            $products = $products->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->status != null) {
            $status = $request->status;
            $products = $products->where('status', $request->status);
        }
        $products = $products->with('get_variants')->where(['is_combo' => 0, 'theme_id' => activeThemeData()->id])->latest()->paginate(20);

        // dd($products);
        return view('backEnd.admin.product.index', compact('products', 'status'));
    }

    public function create()
    {
        $attributes = Attribute::with('items')->get();
        $categories = Category::where('status', 1)->Where('theme_id', activeThemeData()->id)->orderBy('category_name', 'asc')->get();
        $extraFields = ThemeExtraField::where('theme_path', activeThemeData()->path)
            ->select('field_name', 'field_label', 'field_type', 'is_required')
            ->where('model_type', 'App\Product')
            ->get()->toArray();

        $products = Product::where('theme_id', activeThemeData()->id)->latest()->get();

        return view('backEnd.admin.product.create', compact('attributes', 'categories', 'extraFields', 'products'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:products,name',
            'sku' => 'required|unique:products,sku',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('errors', $validator->getMessageBag()->toArray());
        }
        DB::transaction(function () use ($request) {
            $image_id = uploadFile($request->file('image') ?? null, 1000, 1000);
            $gallery_image_ids = uploadMultipleFile($request->file('gallery_image') ?? null, 1000, 1000);
            $input = array_merge(
                $request->all(),
                [
                    'thumb' => $image_id,
                    'image' => $image_id,
                    'gallery_images' => $gallery_image_ids,
                    'sku' => str_replace(' ', '-', strtolower($request->sku)),
                    'stock' => $request->stock ?? 0,
                    'has_variant' => $request->has('attribute') ? 1 : 0,
                    'is_package' => $request->package_qty > 1 ? 1 : 0,
                    'package_qty' => $request->package_qty,
                    'theme_id' => activeThemeData()->id,
                    'related_products' => $request->related_products ?? null,
                ]
            );
            $product = Product::create($input);
            if ($request->has('attribute')) {
                $attribute = $request->attribute ?? [];
                $attribute_values = array_values($attribute);
                // Cartesian combinations
                $combinations = [[]];
                foreach ($attribute_values as $values) {
                    $temp = [];
                    foreach ($combinations as $combo) {
                        foreach ($values as $val) {
                            $temp[] = array_merge($combo, [$val]);
                        }
                    }
                    $combinations = $temp;
                }

                $totalStock = 0;

                foreach ($combinations as $index => $combo) {
                    $variantKey = implode('-', $combo);
                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $request->variant_sku[$index] ?? null,
                        'purchase_price' => $request->variant_purchase_price[$index] ?? 0,
                        'regular_price' => $request->variant_regular_price[$index] ?? 0,
                        'sale_price' => $request->variant_sale_price[$index] ?? 0,
                        'stock' => $request->variant_stock[$index] ?? 0,
                        'variant' => $variantKey,
                        // 'image' => $request->variant_image[$index] ?? null
                    ]);
                    $totalStock += $request->variant_stock[$index] ?? 0;

                    foreach ($combo as $attr_index => $item_id) {
                        $attribute_id = array_keys($attribute)[$attr_index];
                        $item_name = AttributeItem::find($item_id)->name ?? null;
                        $img = $request->attribute_images[$attribute_id][$item_id] ?? null;
                        $img_id = uploadFile($img, 1000, 1000);
                        // dd($img_id);

                        ProductVariantItem::create([
                            'product_variant_id' => $variant->id,
                            'attribute_id' => $attribute_id,
                            'attribute_item_id' => $item_id,
                            'name' => $item_name,
                            'image' => $img_id ?? null,
                        ]);
                    }
                }

                $product->update(['stock' => $totalStock]);
            }
            foreach ($request->category_id as $cat) {
                DB::table('category_products')->insert([
                    'category_id' => $cat,
                    'product_id' => $product->id,
                ]);
            }

            $extraFields = ThemeExtraField::where('theme_path', activeThemeData()->path)
                ->where('model_type', 'App\Product')
                ->get();

            if ($request->hasFile('hover_image')) {
                $hover_image_id = uploadFile($request->file('hover_image') ?? null, 1000, 1000);
            }
            if ($extraFields->count() > 0) {
                $specialFields = [
                    'hover_image' => $hover_image_id ?? null,
                    'position' => 0,
                ];
                $extraData = [];
                foreach ($extraFields as $field) {
                    $key = $field->field_name;

                    if (array_key_exists($key, $specialFields)) {
                        $extraData[$key] = $specialFields[$key];

                        continue;
                    }
                    $extraData[$key] = $request->$key ?? null;
                }

                $product->update([
                    'extra_fields' => $extraData,
                ]);
            }
        });

        return redirect()->route('admin.product')->with('success', 'Product create successfully.');
    }

    public function edit($id)
    {
        // dd($id);
        $data = Product::with('get_variants', 'get_variant_items')->findOrFail($id);
        // dd($data->hover_image_url);
        $categories = Category::where(['status' => 1, 'theme_id' => activeThemeData()->id])->pluck('category_name', 'id');
        $p_c = $data->get_categories()->pluck('category_name', 'categories.id');
        $prod_cat = '';
        foreach ($p_c as $key => $item) {
            $prod_cat .= ',' . $key;
        }
        $prod_cat = substr($prod_cat, 1);
        $attributes = Attribute::with('items')->get();
        $extraFields = ThemeExtraField::where('theme_id', $data->theme_id)
            ->where('model_type', 'App\Product')
            ->get();

        $theme_id = activeThemeData()->id;
        $products = Product::where('theme_id', $theme_id)->latest()->get();

        return view('backEnd.admin.product.edit', compact('data', 'categories', 'prod_cat', 'attributes', 'extraFields', 'products'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:products,name,' . $id,
            'sku' => 'required|unique:products,sku,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
        DB::transaction(function () use ($request, $id) {
            $product = Product::findOrFail($id);
            if ($request->has('image')) {
                $image_id = uploadFile($request->file('image'), 1000, 1000);
                if ($product->image) {
                    deleteFile($product->image);
                }
            } else {
                $image_id = $request->image_old;
            }

            if ($request->has('gallery_image')) {
                if ($product->gallery_images) {
                    deleteMultipleFiles($product->gallery_images);
                }
                $gallery_image_ids = uploadMultipleFile($request->file('gallery_image'), 1000, 1000);
            } else {
                $gallery_image_ids = $request->gallery_images_old;
            }
            $input = array_merge(
                $request->all(),
                [
                    'thumb' => $image_id,
                    'image' => $image_id,
                    'gallery_images' => $gallery_image_ids,
                    'sku' => str_replace(' ', '-', strtolower($request->sku)),
                    'stock' => $request->stock ?? 0,
                    'has_variant' => $request->has('attribute') ? 1 : 0,
                    'is_package' => $request->package_qty > 1 ? 1 : 0,
                    'package_qty' => $request->package_qty,
                    'theme_id' => $product->theme_id,
                    'related_products' => $request->related_products,
                ]
            );
            $product->update($input);
            $product->get_categories()->sync($request->category_id);

            $product->get_variants()->each(function ($variant) {
                $variant->items()->delete();
                $variant->delete();
            });

            if ($request->has('attribute')) {
                $attribute = $request->attribute ?? [];
                $attribute_values = array_values($attribute);
                $combinations = [[]];

                foreach ($attribute_values as $values) {
                    $temp = [];
                    foreach ($combinations as $combo) {
                        foreach ($values as $val) {
                            $temp[] = array_merge($combo, [$val]);
                        }
                    }
                    $combinations = $temp;
                }

                $totalStock = 0;

                foreach ($combinations as $index => $combo) {
                    $variantKey = implode('-', $combo);
                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $request->variant_sku[$index] ?? null,
                        'purchase_price' => $request->variant_purchase_price[$index] ?? 0,
                        'regular_price' => $request->variant_regular_price[$index] ?? 0,
                        'sale_price' => $request->variant_sale_price[$index] ?? 0,
                        'stock' => $request->variant_stock[$index] ?? 0,
                        'variant' => $variantKey,
                    ]);

                    $totalStock += $request->variant_stock[$index] ?? 0;
                    foreach ($combo as $attr_index => $item_id) {
                        $attribute_id = array_keys($attribute)[$attr_index];
                        $item_name = AttributeItem::find($item_id)->name ?? null;

                        $img = $request->attribute_images[$attribute_id][$item_id] ?? null;
                        $img_old = $request->attribute_images_old[$attribute_id][$item_id] ?? null;

                        if ($img) {
                            $img_id = uploadFile($img, 1000, 1000);
                        } else {
                            $img_id = $img_old; // পুরনো image ধরে রাখা
                        }

                        ProductVariantItem::create([
                            'product_variant_id' => $variant->id,
                            'attribute_id' => $attribute_id,
                            'attribute_item_id' => $item_id,
                            'name' => $item_name,
                            'image' => $img_id ?? null,
                        ]);
                    }
                }

                $product->update(['stock' => $totalStock]);
            }

            $extraFields = ThemeExtraField::where('theme_id', $product->theme_id)
                ->where('model_type', 'App\Product')
                ->get();
            if ($extraFields->count() > 0) {
                if ($request->hasFile('hover_image')) {
                    $hover_image_id = uploadFile($request->file('hover_image') ?? null, 1000, 1000);
                }

                $currentExtra = $product->extra_fields ?? [];
                // dd($currentExtra);
                $extraData = [];
                foreach ($extraFields as $field) {
                    $key = $field->field_name;
                    if ($key == 'hover_image') {
                        $extraData[$key] = $hover_image_id ?? ($currentExtra['hover_image'] ?? null);

                        continue;
                    }
                    if ($key == 'position') {
                        $extraData[$key] = $currentExtra['position'];
                        // dd($extraData[$key]);
                    }

                    if ($request->has($key)) {
                        // dd($request->get($key));
                        $extraData[$key] = $request->get($key);
                    } else {
                        if ($key == 'position') {
                            $extraData[$key] = $currentExtra['position'] ?? 0;
                        }
                        // dd($extraData[$key]);
                        $extraData[$key] = $currentExtra[$key] ?? null;
                    }
                }
                // dd($extraData);
                $product->update([
                    'extra_fields' => $extraData,
                ]);
            }
        });

        return redirect()->route('admin.product')->with('success', 'Product updated successfully.');
    }

    public function status(Request $request, $id)
    {

        $status = Product::findOrFail($id);
        $status->update(['status' => ! $status->status]);

        return back()->with('success', 'Product status updated successfully.');
    }

    public function delete($id)
    {
        $product = Product::with('get_order_products')->findOrFail($id);
        if ($product->get_order_products->count() > 0) {
            return back()->with('warning', 'Product can\'t be deleted because it has been used in an order.');
        }
        $product->delete();

        return back()->with('success', 'Product deleted successfully.');
    }

    // bulk delete
    public function bulkDelete(Request $request)
    {
        // dd($request->all());
        $ids = explode(',', $request->all_delete_id);
        // if any product has been used in an order
        $products = Product::with('get_order_products')->whereIn('id', $ids)->get();
        if ($products->count() > 0) {
            foreach ($products as $product) {
                if ($product->get_order_products->count() > 0) {
                    return back()->with('warning', 'Product can\'t be deleted because it has been used in an order.');
                }
            }
        }
        Product::whereIn('id', $ids)->delete();

        return back()->with('success', 'Product deleted successfully.');
    }

    public function checkUniqueSku(Request $request)
    {
        $sku = str_replace(' ', '-', strtolower($request->sku));
        $query = Product::where('sku', $sku);
        if ($request->productId) {
            $query->whereNotIn('id', [$request->productId]);
        }
        $product = $query->first();

        if ($product) {
            return response()->json(['error' => 'Already exists!']);
        } else {
            return response()->json(['success' => 'Valid!']);
        }
    }

    public function ajaxGetCombinedAttributes(Request $request)
    {
        // dd($request->all());
        $arrays = $request->attribute;

        if ($arrays) {
            $result = [[]];

            foreach ($arrays as $attribute_id => $attribute_items) {
                $tmp = [];
                foreach ($result as $result_item) {
                    foreach ($attribute_items as $item_id) {

                        $attribute_name = Attribute::find($attribute_id)->name ?? '';
                        $item_name = AttributeItem::find($item_id)->name ?? '';

                        // Merge previous combinations + new attribute pair
                        $tmp[] = array_merge($result_item, [
                            $attribute_name => $item_name,
                        ]);
                    }
                }
                $result = $tmp;
            }

            $combinations = collect($result);
            $v_regular_price = $request->v_regular_price;
            $v_purchase_price = $request->v_purchase_price;
            $v_sale_price = $request->v_sale_price;
            $product_sku = $request->h_sku;

            if ($combinations->count()) {
                return view('backEnd.admin.product.partials.generate_combination', compact(
                    'combinations',
                    'v_regular_price',
                    'v_sale_price',
                    'v_purchase_price',
                    'product_sku'
                ));
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function ajaxGetCombinedAttributesEdit(Request $request)
    {
        $arrays = $request->attribute;
        $product = Product::find($request->id);

        if ($arrays) {
            $result = [[]];

            foreach ($arrays as $attribute_id => $attribute_items) {
                $tmp = [];
                foreach ($result as $result_item) {
                    foreach ($attribute_items as $item_id) {
                        $attribute_name = Attribute::find($attribute_id)->name ?? '';
                        $item_name = AttributeItem::find($item_id)->name ?? '';

                        $tmp[] = array_merge($result_item, [
                            $attribute_name => $item_name,
                        ]);
                    }
                }
                $result = $tmp;
            }

            $combinations = $result;
            $v_purchase_price = $request->v_purchase_price;
            $v_regular_price = $request->v_regular_price;
            $v_sale_price = $request->v_sale_price;
            $product_sku = $request->h_sku;
            $product_id = $request->id;

            if ($combinations) {
                return view('backEnd.admin.product.partials.generate_combination_edit', compact(
                    'combinations',
                    'product',
                    'v_purchase_price',
                    'v_regular_price',
                    'v_sale_price',
                    'product_sku',
                    'product_id'
                ));
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function ajaxGetColorImage(Request $request)
    {
        // dd($request->all());
        $colorImages = [];
        if ($request->has('attribute')) {
            foreach ($request->attribute as $key => $var) {
                $is_image = Attribute::where('id', $key)->value('is_image');
                // dd($is_image);
                if ($is_image) {
                    $colorImages[$key] = $var;
                }
            }
        }

        // dd($colorImages);
        return view('backEnd.admin.product.color-image.create', compact('colorImages'));
    }

    public function ajaxGetColorImageEdit(Request $request)
    {
        // dd($request->all());
        // product
        $product = Product::with('get_variants.items.item_image')->find($request->id);

        $existing_item_images = [];

        // Step 1: নতুনভাবে attribute থেকে structure বানানো
        if ($request->has('attribute')) {
            foreach ($request->attribute as $attribute_id => $item_ids) {
                $is_image = Attribute::where('id', $attribute_id)->value('is_image');
                if ($is_image) {
                    $existing_item_images[$attribute_id] = [];
                    foreach ($item_ids as $item_id) {
                        $item_name = AttributeItem::where('id', $item_id)->value('name');
                        $existing_item_images[$attribute_id][$item_id] = [
                            'name' => $item_name,
                            'image' => null, // নতুন image হলে set হবে
                            'image_old' => null, // পুরনো image থাকলে দেখাবে
                        ];
                    }
                }
            }
        }

        // Step 2: আগের product variant data merge করা (শুধু যেগুলো এখনো নির্বাচিত আছে)
        if ($product && $product->get_variants) {
            foreach ($product->get_variants as $variant) {
                foreach ($variant->items as $variantItem) {
                    $attribute_id = $variantItem->attribute_id;
                    $item_id = $variantItem->attribute_item_id;

                    // attribute + item request
                    if (isset($existing_item_images[$attribute_id][$item_id])) {
                        $existing_item_images[$attribute_id][$item_id]['image'] = $variantItem->item_image
                            ? $variantItem->item_image->file_url
                            : null;

                        $existing_item_images[$attribute_id][$item_id]['image_old'] = $variantItem->image;
                    }
                }
            }
        }

        // dd($existing_variants);

        //  dd($existing_variants);

        return view('backEnd.admin.product.color-image.edit', compact('existing_item_images'));
    }

    public function attributeItemStore(Request $request)
    {
        // dd($request->all());

        // check if variant item in exist in variant
        $variant_item = AttributeItem::where('name', strtolower($request->name))->where('attribute_id', $request->hidden_attribute_id)->first();
        if ($variant_item) {
            return response()->json(['status' => 'error', 'message' => 'Variant item already exist']);
        }

        $item = AttributeItem::create([
            'attribute_id' => $request->hidden_attribute_id,
            'name' => strtolower($request->name),
        ]);

        return response()->json(['status' => 'success', 'item' => $item]);
    }

    public function updateFlag(Request $request)
    {
        $product = Product::findOrFail($request->id);

        if ($product) {
            $flags = $product->extra_fields ?? [];
            // update single key
            $flags[$request->key] = $request->value;
            $product->extra_fields = $flags;
            $product->save();

            return response()->json(['success' => true]);
        }
    }

    public function updatePosition(Request $request)
    {
        // dd($request->all());
        $product = Product::findOrFail($request->id);
        $flags = $product->extra_fields ?? [];
        $flags[$request->key] = $request->position;
        $product->extra_fields = $flags;
        $product->save();

        return response()->json(['success' => true]);
    }

    public function checkSlug(Request $request)
    {
        $slug = $request->slug;
        $exists = Product::where('slug', $slug)->exists();

        return response()->json([
            'exists' => $exists,
        ]);
    }

    public function quickSlugUpdate(Request $request)
    {
        $product = Product::findOrFail($request->id);

        // slug unique check
        $exists = Product::where('slug', $request->slug)
            ->where('id', '!=', $product->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'This slug is already taken!',
            ]);
        }

        $product->update([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Saved successfully!',
        ]);
    }
    public function freeShipping(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'free_shipping' => 'required|in:0,1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $product->update([
            'free_shipping' => !$product->free_shipping
        ]);


        return response()->json([
            'status' => true,
            'free_shipping' => $product->free_shipping
        ]);
    }
}
