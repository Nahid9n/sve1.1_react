<?php

namespace App\Http\Controllers;

use App\AbandonedCart;
use App\Category;
use App\IpAddress;
use App\LandingCategory;
use App\LandingPage;
use App\LandingTheme;
use App\Order;
use App\OrderProduct;
use App\Product;
use App\ProductVariant;
use App\User;
use Carbon\Carbon;
use Cart;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class LandingPageController extends Controller
{
    // INDEX PAGE
    public function index()
    {
        $pages = LandingPage::with('theme')->orderBy('id', 'DESC')->get();

        return view('backEnd.admin.landing-pages.index', compact('pages'));
    }

    // CREATE PAGE
    public function create()
    {
        $themes = LandingTheme::orderBy('title')->get();
        $products = Product::orderBy('name')->where('status', true)->get();
        $categories = LandingCategory::orderBy('title')->with('themes')->where('status', true)->get();

        return view('backEnd.admin.landing-pages.create', compact('themes', 'products', 'categories'));
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'theme_id' => 'required|integer',
        ]);

        LandingPage::create($request->all());

        return redirect()->route('admin.landing.pages.index')->with('success', 'Page Created Successfully');
    }

    // EDIT PAGE
    public function edit($id)
    {
        $page = LandingPage::findOrFail($id);

        $products = Product::orderBy('name')->get();
        $categories = LandingCategory::with('themes')->orderBy('title')->get();

        // Determine active category tab
        $activeCategoryId = $page->theme ? $page->theme->category_id : $categories->first()->id;

        return view('backEnd.admin.landing-pages.edit', compact('page', 'products', 'categories', 'activeCategoryId'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $page = LandingPage::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'theme_id' => 'required|integer',
        ]);

        $page->update($request->all());

        return redirect()->route('admin.landing.pages.index')->with('success', 'Page Updated Successfully');
    }

    // DELETE
    public function delete($id)
    {
        LandingPage::where('id', $id)->delete();

        return back()->with('success', 'Page Deleted Successfully');
    }

    // CUSTOMIZE
    public function customize($slug)
    {
        $landingPage = LandingPage::where('slug', $slug)->firstOrFail();
        // Ensure the Blade file exists
        $this->createTemplate($landingPage->theme);

        $enableEdit = true;
        $landingTheme = $landingPage->theme;

        return view('landing-pages.'.$landingPage->theme->slug, compact('landingPage', 'landingTheme', 'enableEdit'));
    }

    // THEME PREVIEW
    public function preview($slug)
    {
        $landingTheme = LandingTheme::where('slug', $slug)->firstOrFail();
        // Ensure the Blade file exists
        $this->createTemplate($landingTheme);

        return view('landing-pages.'.$landingTheme->slug, compact('landingTheme'));
    }

    /**______________________________________________________________________________________________
     *_______________________________________________________________________________________________
     *Category
     *_______________________________________________________________________________________________
     *_______________________________________________________________________________________________
     */

    public function indexCategory()
    {
        $categories = LandingCategory::latest()->get();

        return view('backEnd.admin.landing-pages.landing-category', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:landing_categories,title',
            'status' => 'required',
        ]);

        LandingCategory::create($request->all());

        return redirect()->back()->with('success', 'Category Created Successfully');
    }

    public function updateCategory(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:landing_categories,title,'.$request->id,
            'status' => 'required',
        ]);

        $category = LandingCategory::find($request->id);

        $category->update($request->all());

        return redirect()->back()->with('success', 'Category Updated Successfully');
    }

    public function destroyCategory($id)
    {
        LandingCategory::where('id', $id)->delete();

        return back()->with('success', 'Page Deleted Successfully');
    }

    /**______________________________________________________________________________________________
     *_______________________________________________________________________________________________
     * Theme
     *_______________________________________________________________________________________________
     *_______________________________________________________________________________________________
     */
    public function indexTheme()
    {
        $themes = LandingTheme::with('category')->latest()->get();
        $categories = LandingCategory::where('status', 1)->get();

        return view('backEnd.admin.landing-pages.landing-theme', compact('themes', 'categories'));
    }

    public function storeTheme(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:landing_themes,title',
            'category_id' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $image_id = null;

        if ($request->hasFile('image')) {
            // Example: Resize to 600x400 (or whatever you want)
            $image_id = uploadFile($request->file('image'), 600, 400);
        }

        $theme = LandingTheme::create([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'image' => $image_id,
        ]);
        // File Create
        $this->createTemplate($theme);

        return back()->with('success', 'Theme Created Successfully');
    }

    public function updateTheme(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:landing_themes,title,'.$request->id,
            'category_id' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $theme = LandingTheme::findOrFail($request->id);

        $image_id = $theme->image; // keep old image

        if ($request->hasFile('image')) {
            // upload new file
            $image_id = uploadFile($request->file('image'), 600, 400);
        }

        $theme->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'image' => $image_id,
        ]);
        // File Create
        $this->createTemplate($theme);

        return back()->with('success', 'Theme Updated Successfully');
    }

    private function createTemplate(LandingTheme $theme)
    {

        $themeViewPath = resource_path('views/landing-pages/'.$theme->slug.'.blade.php');

        if (! file_exists($themeViewPath)) {
            $defaultContent = <<<BLADE
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Landing Page - {$theme->title}</title>
                <link rel="stylesheet" href="{{ asset('landing-page/{$theme->slug}/css/style.css') }}">
            </head>
            <body>
                <div class="container">
                    <h1><strong>Theme Name:</strong> {$theme->title}</h1>
                </div>
            </body>
            </html>
            BLADE;

            // Ensure directory exists
            if (! is_dir(dirname($themeViewPath))) {
                mkdir(dirname($themeViewPath), 0755, true);
            }
            // Create the file
            file_put_contents($themeViewPath, $defaultContent);
        }

        // Public Path folder Create
        $publicFolder = public_path('landing-page/'.$theme->slug);
        if (! is_dir($publicFolder)) {
            mkdir($publicFolder, 0755, true);
        }
    }

    /**______________________________________________________________________________________________
     *_______________________________________________________________________________________________
     * CONTENT SAVE
     *_______________________________________________________________________________________________
     *_______________________________________________________________________________________________
     */
    public function save(Request $request, $id)
    {
        try {
            // $request->validate([
            //     'content' => 'required|array',
            //     'style' => 'required|array',
            //     'section_status' => 'nullable|array',
            // ]);

            $landingPage = LandingPage::findOrFail($id);

            $currentContent = json_decode($landingPage->content ?? '{}', true);
            $currentStyle = json_decode($landingPage->style ?? '{}', true);

            $newContent = array_merge($currentContent, $request->content);
            $newStyle = array_merge($currentStyle, $request->style);

            if ($request->has('section_status')) {
                $newContent['section_status'] = $request->section_status;
            }

            $landingPage->update([
                'content' => json_encode($newContent, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
                'style' => json_encode($newStyle, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Landing page saved successfully!',
                'data' => [
                    'id' => $landingPage->id,
                    'updated_at' => $landingPage->updated_at,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save landing page: '.$e->getMessage(),
            ], 500);
        }
    }

    public function uploadImage(Request $request)
    {
        try {
            // $request->validate([
            //     'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            //     'image_id' => 'required|integer',
            // ]);

            $image = $request->file('image');
            $imageId = $request->image_id;

            $destinationPath = 'uploads/landing-page/images';
            $absolutePath = public_path($destinationPath);
            $filename = 'lp-'.$imageId.'-'.time().'.'.$image->getClientOriginalExtension();

            $image->move($absolutePath, $filename);

            $publicUrl = asset($destinationPath.'/'.$filename);

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully!',
                'path' => $publicUrl,
            ]);

        } catch (\Exception $e) {
            // Log::error('Image Upload failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Upload failed: '.$e->getMessage(),
            ], 500);
        }
    }

    /**______________________________________________________________________________________________
     *_______________________________________________________________________________________________
     * FRONTEND SITE
     *_______________________________________________________________________________________________
     *_______________________________________________________________________________________________
     */

    /**
     * Display landing page
     */
    public function homePage($slug)
    {
        $landingPage = LandingPage::where('slug', $slug)->firstOrFail();
        $landingTheme = $landingPage->theme;
        $products = Product::with('get_thumb', 'get_gallery_images', 'get_variants')->whereIn('id', $landingPage->product_ids)->get();

        // dd(\Cart::getContent());
        // \Cart::clear();
        return view('landing-pages.'.$landingPage->theme->slug, compact('landingPage', 'landingTheme', 'products'));
    }

    public function addToCart(Request $request)
    {
        $quantity = (int) ($request->qty ?? 1);
        $product = Product::findOrFail($request->id);

        // Default values
        $sku = $product->sku ?? $product->id;
        $price = $product->sale_price > 0 ? $product->sale_price : $product->regular_price;
        $variantKey = null;

        /* ---------- VARIANT HANDLING ---------- */
        if ($request->has('attribute_item_id') && is_array($request->attribute_item_id)) {
            // example attribute_item_id: ["18-2", "19-5"]
            $variantKey = implode('-', $request->attribute_item_id);
            $variant = ProductVariant::where('product_id', $product->id)->where('variant', $variantKey)->first();

            if ($variant) {
                $price = $variant->sale_price > 0 ? $variant->sale_price : $variant->regular_price;
                // optional
                $sku = $variant->sku ?? $sku;
            }
        }

        /* ---------- CHECK IF ITEM EXISTS ---------- */
        $existingItem = \Cart::get($sku);

        // Check same variant (attributes match)
        // $isSameVariant = ($existingItem && $existingItem->attributes == $variantKey);

        if ($existingItem) {
            // ✅ Update quantity
            \Cart::update($sku, [
                'price' => (float) $price,
                'quantity' => [
                    'relative' => false,
                    'value' => $quantity,
                ],
                'attributes' => $variantKey,
            ]);

        } else {

            // ✅ Add new item
            \Cart::add([
                'id' => $sku,
                'name' => $product->name,
                'price' => (float) $price,
                'quantity' => $quantity,
                'attributes' => $variantKey,
                'associatedModel' => $product,
            ]);
        }

        return response()->json([
            'success' => true,
            'cart_id' => $sku,
            'total_price' => number_format($price * $quantity, 2),
            'message' => 'Cart updated successfully',
        ]);
    }

    /**
     * Remove product from cart (AJAX)
     */
    public function removeFromCart(Request $request)
    {
        Cart::remove($request->id);

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart',
            'cart_count' => Cart::getContent()->count(),
        ]);
    }

    /**
     * Update cart quantity (AJAX)
     */
    public function updateCartQuantity(Request $request)
    {
        Cart::update($request->id, [
            'quantity' => [
                'relative' => false,
                'value' => (int) $request->qty,
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Quantity updated',
            'cart_count' => Cart::getContent()->count(),
        ]);
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'customer_name' => ['required', 'string'],
            'customer_phone' => ['required', 'digits:11'],
            'customer_address' => ['required', 'string', 'min:10'], // min 10 characters
        ], [
            'customer_name.required' => 'আপনার পুরো নাম লিখুন।',
            'customer_name.regex' => 'নামে শুধুমাত্র অক্ষর এবং স্পেস ব্যবহার করা যাবে।',
            'customer_phone.required' => 'আপনার মোবাইল নাম্বার লিখুন।',
            'customer_phone.digits' => 'মোবাইল নাম্বার অবশ্যই ঠিক ১১ সংখ্যার হতে হবে।',
            'customer_address.required' => 'আপনার ঠিকানা লিখুন।',
            'customer_address.min' => 'ঠিকানাটি কমপক্ষে ১০ অক্ষরের হতে হবে।',
        ]);

        // 1. Check IP address (Security Block)
        $ip_address = IpAddress::where('ip_address', $request->ip())->first();
        if ($ip_address && $ip_address->status == 0) {
            return redirect()->back()->with('error', 'You are blocked from placing orders.');
        }

        $cartItems = \Cart::getContent();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        // Use a Transaction
        return DB::transaction(function () use ($request, $cartItems) {

            // 2. Create or find customer
            $customer = User::where('phone', $request->customer_phone)->first();
            if (! $customer) {
                $customer = User::create([
                    'name' => $request->customer_name,
                    'phone' => $request->customer_phone,
                    'address' => $request->customer_address,
                    'password' => Hash::make($request->customer_phone),
                    'status' => 1, // Active
                ]);
            }

            if ($customer && $customer->status == 0) {
                return redirect()->route('home')->with('error', 'You are blocked');
            }

            // 3. Create order
            $order_input = array_merge($request->all(), [
                'invoice_id' => invoice_generate(),
                'theme_id' => activeTheme()->id,
                'order_date' => Carbon::now()->toDateString(),
                'customer_id' => $customer->id,
                'shipping_method' => $request->shipping_method_id,
                'sub_total' => \Cart::getSubTotal(),
                'total' => \Cart::getTotal(),
                'paid' => 0,
                'due' => \Cart::getTotal(),
                'status' => 1,
                'source' => 'lp',
                'ip_address' => $request->ip(),
            ]);
            $order = Order::create($order_input);

            // 4. Add order products & Handle Inventory
            foreach ($cartItems as $item) {
                $quantity = $item->quantity;
                $attributes = $item->attributes[0] ?? null;
                $product = Product::find($item->associatedModel->id);

                if (! $product) {
                    continue;
                } // Safety check

                $variant_name = null;
                $purchase_price = $product->purchase_price;

                // 1. Handle Variants
                if ($product->has_variant == 1 && $attributes) {
                    $product_attr = ProductVariant::with('items')
                        ->where('sku', $item->id)
                        ->where('variant', $attributes)
                        ->first();

                    if ($product_attr) {
                        $purchase_price = $product_attr->purchase_price;
                        $variant_name = $product_attr->items->pluck('name')->implode(', ');

                        // Decrement both variant and main product stock
                        $product_attr->decrement('stock', $quantity);
                        $product->decrement('stock', $quantity);
                    }
                }
                // 2. Handle Non-Variant Products (Package, Combo, Simple)
                else {
                    if ($product->is_package == 1) {
                        $product->decrement('stock', $quantity * $product->package_qty);
                    } elseif ($product->is_combo == 1) {
                        foreach ($product->comboItems as $comboItem) {
                            $comboItem->product->decrement('stock', $comboItem->quantity * $quantity);
                        }
                        $product->decrement('stock', $quantity);
                    } else {
                        $product->decrement('stock', $quantity);
                    }
                }

                // 3. Create Order Records
                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_sku' => $item->id,
                    'qty' => $quantity,
                    'price' => $item->price,
                    'purchase_price' => $purchase_price,
                    'attributes' => $attributes,
                ]);

                // 4. Track for response if needed
                $products[] = [
                    'product_id' => $product->id,
                    'product_sku' => $item->id,
                    'qty' => $quantity,
                    'price' => $item->price,
                    'variant_name' => $variant_name,
                ];
            }

            // 5. Cleanup Abandoned Cart
            $abandonedId = session('abandoned_cart_id');
            if ($abandonedId) {
                AbandonedCart::where('id', $abandonedId)->delete();
                session()->forget('abandoned_cart_id');
            } else {
                AbandonedCart::where('customer_phone', $request->customer_phone)->delete();
            }

            // 6. Log Order Activity
            $old_data = array_merge($order_input, [
                'products' => $products,
            ]);
            $order_history = [
                'order_id' => $order->id,
                'user_type' => 'Customer',
                'activity_type' => 1,
                'created_by' => $customer->id,
                'text' => strtr(config('order_activities.new_order'), [
                    '{user_name}' => $customer->name,
                ]),
                'comment' => null,
                'old_order' => $old_data,
            ];
            order_activities($order_history);

            // 7. Finalize
            \Cart::clear();

            return redirect()->route('order.confirmation', ['order' => $order->invoice_id])->with('success', 'Order Placed Successfully!');
        });
    }

    public function orderConfirmation($order)
    {
        // Retrieve the order with its products
        $order = Order::with('get_products')->where('invoice_id', $order)->firstOrFail();

        return view('landing-pages.order_confirmation', compact('order'));
    }

    public function abandonedCart(Request $request)
    {
        $carts = Cart::getContent();
        if ($carts->isEmpty()) {
            return response()->json(['message' => 'Empty'], 200);
        }

        $subtotal = 0;
        $items = [];

        foreach ($carts as $item) {
            $subtotal += ($item->quantity * $item->price);
            $items[] = [
                'product_id' => $item->associatedModel->id,
                'qty' => $item->quantity,
                'price' => $item->price,
                'sku' => $item->id,
                'variant' => $item->attributes->first() ?? null,
            ];
        }

        $shipping = $request->data['shipping_cost'] ?? 0;

        $input = [
            'customer_name' => $request->data['name'],
            'customer_phone' => $request->data['phone'],
            'customer_address' => $request->data['address'],
            'shipping_cost' => $shipping,
            'subtotal' => $subtotal,
            'total' => $subtotal + $shipping,
            'abandoned_item' => json_encode($items),
        ];

        // Updates if session ID exists, otherwise creates new
        $abandoned = AbandonedCart::updateOrCreate(
            ['id' => session('abandoned_cart_id')],
            $input
        );

        session(['abandoned_cart_id' => $abandoned->id]);

        return response()->json(['status' => 'success']);
    }

    /**
     * Get product attributes (AJAX)
     */
    public function getAttributes(Request $request)
    {
        $productId = $request->product_id;
        $selectedItemIds = $request->attribute_item_id;

        if (! $productId || ! $selectedItemIds || ! is_array($selectedItemIds)) {
            return response()->json([
                'success' => 400,
                'message' => 'Invalid request data.',
            ]);
        }

        $product = Product::with([
            'get_variants.items.attribute',
            'get_variants.items.attribute_item',
            'get_variants.items.item_image',
        ])->find($productId);

        if (! $product) {
            return response()->json([
                'success' => 404,
                'message' => 'Product not found.',
            ]);
        }

        $variantKey = implode('-', $selectedItemIds);
        $matchedVariant = $product->get_variants->firstWhere('variant', $variantKey);

        if (! $matchedVariant) {
            return response()->json([
                'success' => 404,
                'message' => 'Variant not found for selected attributes.',
            ]);
        }

        return response()->json([
            'success' => 200,
            'message' => 'Variant found successfully.',
            'data' => [
                'sku' => $matchedVariant->sku,
                'regular_price' => $matchedVariant->regular_price,
                'sale_price' => $matchedVariant->sale_price,
                'stock' => $matchedVariant->stock,
                'variant_key' => $matchedVariant->variant,
                'selected_ids' => $selectedItemIds,
                'variant_items' => $matchedVariant->items->map(function ($item) {
                    return [
                        'attribute_item' => $item->attribute_item->name ?? null,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Generate invoice ID
     */
    private function generateInvoiceId()
    {
        return 'INV-'.date('Ymd').'-'.str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }
}
