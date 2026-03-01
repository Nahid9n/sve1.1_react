<?php

namespace App\Http\Controllers;

use App\AbandonedCart;
use App\Attribute;
use App\Category;
use App\IpAddress;
use App\Order;
use App\Product;
use App\ProductVariant;
use App\PromotionalBanner;
use App\Review;
use App\ReviewImage;
use App\ShippingMethod;
use App\Slider;
use App\Theme;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ThemesFrontendController extends Controller
{
    // public function index($path)
    // {
    //     $categories = Category::where('status', 1)->get();
    //     $cat_count  = $categories->count();
    //     $products   = Product::with('get_thumb')->where('status', 1)->orderBy('id', 'desc')->paginate(25);
    //     $hot_deal_1 = Product::with('get_thumb')->where([['sale_price', '>', 0], ['status', 1]])->take(12)->get();
    //     $hot_deal_2 = Product::with('get_thumb')->where([['sale_price', '>', 0], ['status', 1]])->skip(12)->take(12)->get();
    //     $sliders    = Slider::with('get_img')->where('status', 1)->get();
    //     $promotion  = PromotionalBanner::first();
    //     $previewTheme    = Theme::where('path', $path)->first()->path;
    //     // dd($previewTheme);
    //     if ($previewTheme) {
    //         return view('frontEnd.' . $previewTheme . '.index', compact('categories', 'cat_count', 'products', 'hot_deal_1', 'hot_deal_2', 'sliders', 'promotion', 'previewTheme'));
    //     } else {
    //         // return view('frontEnd.index', compact('categories', 'cat_count', 'products', 'hot_deal_1', 'hot_deal_2', 'sliders', 'promotion'));
    //     }
    // }

    public function index(Request $request, $path)
    {
        // Session::forget('abandoned_cart_id');
        // dd(Session::get('abandoned_cart_id'));
        visitor()->visit();
        // dd(session()->all());
        $categories = Category::where('status', 1)->get();
        $cat_count = $categories->count();
        $products = Product::with('get_thumb')->where('status', 1)->orderBy('id', 'desc')->paginate(25);
        $hot_deal_1 = Product::with('get_thumb')->where([['sale_price', '>', 0], ['status', 1]])->take(12)->get();
        $hot_deal_2 = Product::with('get_thumb')->where([['sale_price', '>', 0], ['status', 1]])->skip(12)->take(12)->get();
        $sliders = Slider::with('get_img')->where('status', 1)->get();
        $promotion = PromotionalBanner::first();

        $themeUrl = Theme::where('path', $path)->first()->path;
        $themePreview['path'] = $themeUrl;
        $themePreview['type'] = 'preview';
        // dd($theme);
        if ($themeUrl) {
            return view('frontEnd.'.$themeUrl.'.index', compact('categories', 'cat_count', 'products', 'hot_deal_1', 'hot_deal_2', 'sliders', 'promotion', 'themePreview'));
        } else {
            // return view('frontEnd.index', compact('categories', 'cat_count', 'products', 'hot_deal_1', 'hot_deal_2', 'sliders', 'promotion'));
        }
    }

    public function aboutUs()
    {
        $data = DB::table('page_settings')->where('id', 1)->first();
        $activeTheme = Theme::where(['is_active' => 1, 'status' => 1])->first()->path;
        // dd($activeTheme);
        if ($activeTheme) {
            return view('frontEnd.'.$activeTheme.'.pages.about_us', compact('data', 'activeTheme'));
        }
    }

    public function returnPolicy()
    {
        visitor()->visit();
        $data = DB::table('page_settings')->where('id', 1)->first();
        $activeTheme = Theme::where(['is_active' => 1, 'status' => 1])->first()->path;
        // dd($activeTheme);
        if ($activeTheme) {
            return view('frontEnd.'.$activeTheme.'.pages.return_policy', compact('data', 'activeTheme'));
        }
    }

    public function deliveryPolicy()
    {
        visitor()->visit();
        $data = DB::table('page_settings')->where('id', 1)->first();
        $activeTheme = Theme::where(['is_active' => 1, 'status' => 1])->first()->path;
        // dd($activeTheme);
        if ($activeTheme) {
            return view('frontEnd.'.$activeTheme.'.pages.delivery_policy', compact('data', 'activeTheme'));
        }
    }

    public function getSingleCategory($path, $id)
    {
        // dd($id, $path);
        visitor()->visit();
        /*$data = Product::with('get_thumb')->where('category_id', $id)->paginate(42);
        $cat_name = Category::find($id)->category_name;*/
        $category = Category::with('get_products')->find($id);
        // dd($category);
        $data = $category->get_products()->with('get_thumb')->paginate(12);
        $cat_name = $category->category_name;
        $themeUrl = Theme::where('path', $path)->first()->path;
        $themePreview['path'] = $themeUrl;
        $themePreview['type'] = 'preview';
        // dd($theme);
        if ($themeUrl) {
            return view('frontEnd.'.$themeUrl.'.single_category', compact('data', 'cat_name', 'themePreview'));
        }
    }

    // all categories
    public function allCategories()
    {
        dd('here');
        visitor()->visit();
        $data = Category::where('status', 1)->paginate(42);
        $activeTheme = Theme::where(['is_active' => 1, 'status' => 1])->first()->path;
        // dd($activeTheme);
        if ($activeTheme) {
            return view('frontEnd.'.$activeTheme.'.categories', compact('data', 'activeTheme'));
        }
    }

    public function getSingleProduct(Request $request, $path, $slug)
    {
        // dd($path, $slug);
        $v = visitor()->visit();
        Session::put('visitor_id', $v->id);
        $data = Product::with([
            'get_gallery_images',
            'get_variants',
            'get_reviews',
            'get_thumb',
            'get_category_products',
        ])->where('slug', $slug)->first();
        // dd($data);

        $related_products = collect();

        if ($data && $data->get_category_products->isNotEmpty()) {
            $categoryId = $data->get_category_products[0]->category_id;

            $related_products = Category::with(['get_products' => function ($query) use ($data, $categoryId) {
                $query->where('category_id', $categoryId)
                    ->where('product_id', '!=', $data->id);
            }])->where('id', $categoryId)->get();
        }

        $reviews = $data->get_reviews()->latest()->paginate(2);
        // dd($related_products);
        $themeUrl = Theme::where('path', $path)->first()->path;
        $themePreview['path'] = $themeUrl;
        $themePreview['type'] = 'preview';
        // dd($themeUrl);
        if ($themeUrl) {
            if ($request->quick_view == 'true') {
                return view('frontEnd.'.$themeUrl.'.quick_product', compact('data'))->render();
            } else {
                return view('frontEnd.'.$themeUrl.'.single_product', compact('data', 'reviews', 'related_products', 'themePreview'));
            }
        }
    }

    public function allHotDeals()
    {
        $data = Product::with('get_thumb')->where([['sale_price', '>', 0], ['status', 1]])->paginate(42);
        $activeTheme = Theme::where(['is_active' => 1, 'status' => 1])->first()->path;
        // dd($activeTheme);
        if ($activeTheme) {
            return view('frontEnd.'.$activeTheme.'.all_hot_deals', compact('data', 'activeTheme'));
        }
    }

    public function addToWishlist(Request $request)
    {
        // dd($request->all());
        if (! Auth::guard('web')->check()) {
            return response()->json(['success' => 203, 'message' => 'Login required']);
        }

        $userId = Auth::guard('web')->id();
        $existing = Wishlist::where('sku', $request->sku)->where('user_id', $userId)->first();
        if ($existing) {
            return response()->json(['success' => 204, 'message' => 'Already in wishlist']);
        }

        $attr = null;
        if ($request->attribute_id && is_array($request->attribute_id)) {
            $attr = [];
            foreach ($request->attribute_id as $key => $attrId) {
                $variantName = strtolower(Attribute::find($attrId)->name ?? '');
                $attrItemId = $request->attribute_item_id[$key] ?? null;
                $attr[$variantName] = $attrItemId;
            }
            $attr = json_encode($attr);
        } elseif ($request->attribute_id_related && is_array($request->attribute_id_related)) {
            $attr = [];
            foreach ($request->attribute_id_related as $key => $attrId) {
                $variantName = strtolower(Attribute::find($attrId)->name ?? '');
                $attrItemId = $request->attribute_item_id_related[$key] ?? null;
                $attr[$variantName] = $attrItemId;
            }
            $attr = json_encode($attr);
        }

        Wishlist::create([
            'sku' => $request->sku,
            'product_id' => $request->id,
            'user_id' => $userId,
            'attributes' => $attr,
        ]);

        return response()->json(['success' => 202, 'message' => 'Product added to wishlist successfully']);
    }

    public function addCart(Request $request)
    {
        // dd($request->all());
        $quantity = $request->qty ?? 1;

        if ($request->product_id_related) {
            $product = Product::find($request->product_id_related);
        } else {
            $product = Product::find($request->id);
        }
        // dd($request->all());
        // $product = Product::with('get_variants')->find($request->id);

        if (! $product) {
            return response()->json(['success' => 404, 'message' => 'Product not found']);
        }

        // Determine variant key
        $variantKey = null;
        if ($request->attribute_item_id && is_array($request->attribute_item_id)) {
            $variantKey = strtolower(implode('-', $request->attribute_item_id));
        } elseif ($request->attribute_item_id_related && is_array($request->attribute_item_id_related)) {
            $variantKey = strtolower(implode('-', $request->attribute_item_id_related));
        }

        // Try to fetch ProductVariant
        $variant = null;
        if ($variantKey) {
            $variant = ProductVariant::where('product_id', $product->id)
                ->where('variant', $variantKey)
                ->first();
        }

        // Decide which data to use: variant or main product
        $sku = $variant->sku ?? $product->sku;
        $name = $variant->product->name ?? $product->name;
        $price = $variant
            ? ($variant->sale_price > 0 ? $variant->sale_price : $variant->regular_price)
            : ($product->sale_price > 0 ? $product->sale_price : $product->regular_price);

        // Add or update cart
        if (\Cart::get($sku)) {
            \Cart::update($sku, [
                'quantity' => ['relative' => false, 'value' => $quantity],
            ]);
        } else {
            \Cart::add([
                'id' => $sku,
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity,
                'attributes' => $variantKey ?? null,
                'associatedModel' => $product,
            ]);
        }

        return response()->json([
            'success' => 201,
            'message' => 'Product added to cart successfully',
        ]);
    }

    public function buyNow(Request $request)
    {
        // dd($request->all());
        $quantity = $request->qty ?? 1;

        if ($request->product_id_related) {
            $product = Product::find($request->product_id_related);
        } else {
            $product = Product::find($request->id);
        }
        // dd($request->all());
        // $product = Product::with('get_variants')->find($request->id);

        if (! $product) {
            return response()->json(['success' => 404, 'message' => 'Product not found']);
        }

        // Determine variant key
        $variantKey = null;
        if ($request->attribute_item_id && is_array($request->attribute_item_id)) {
            $variantKey = strtolower(implode('-', $request->attribute_item_id));
        } elseif ($request->attribute_item_id_related && is_array($request->attribute_item_id_related)) {
            $variantKey = strtolower(implode('-', $request->attribute_item_id_related));
        }

        // Try to fetch ProductVariant
        $variant = null;
        if ($variantKey) {
            $variant = ProductVariant::where('product_id', $product->id)
                ->where('variant', $variantKey)
                ->first();
        }

        // Decide which data to use: variant or main product
        $sku = $variant->sku ?? $product->sku;
        $name = $variant->product->name ?? $product->name;
        $price = $variant
            ? ($variant->sale_price > 0 ? $variant->sale_price : $variant->regular_price)
            : ($product->sale_price > 0 ? $product->sale_price : $product->regular_price);

        // Add or update cart
        if (\Cart::get($sku)) {
            \Cart::update($sku, [
                'quantity' => ['relative' => false, 'value' => $quantity],
            ]);
        } else {
            \Cart::add([
                'id' => $sku,
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity,
                'attributes' => $variantKey ?? null,
                'associatedModel' => $product,
            ]);
        }

        return response()->json([
            'success' => 200,
            'message' => 'Product ordered successfully',
        ]);
    }

    public function cartItemDelete(Request $request)
    {

        // dd($request->all());
        \Cart::remove($request->id);

        return response()->json(['success' => 204]);
    }

    public function cartItemPlus(Request $request)
    {
        // dd($request->all());
        $total_qty_price = $request->qty_price * $request->qty;
        // dd($total_qty_price);
        if (\Cart::getContent()->count() > 0) {
            \Cart::update($request->id, [
                'quantity' => 1,
            ]);

            return response()->json(['success' => 'success', 'subtotal' => \Cart::getTotal(), 'total_qty_price' => $total_qty_price]);
        } else {
            return back();
        }
    }

    public function cartItemMinus(Request $request)
    {
        //  dd($request->all());
        $total_qty_price = $request->qty_price * $request->qty;
        if (\Cart::getContent()->count() > 0) {
            // dd(5);
            \Cart::update($request->id, [
                'quantity' => -1,
            ]);

            return response()->json(['success' => 'success', 'subtotal' => \Cart::getTotal(), 'total_qty_price' => $total_qty_price]);
        } else {
            return back();
        }
    }

    public function cartClear()
    {
        // dd('cart clear');
        \Cart::clear();

        return response()->json([
            'success' => 200,
            'message' => 'Cart cleared successfully',
        ]);
    }

    public function getShippMeth(Request $request)
    {
        // dd($request->all());
        $amount = ShippingMethod::find($request->id)->amount;

        return response()->json($amount);
    }

    public function checkout($path)
    {
        visitor()->visit();
        // dd('checkout');
        $shipping_methods = ShippingMethod::where('status', 1)->get();
        $themeUrl = Theme::where('path', $path)->first()->path;
        $themePreview['path'] = $themeUrl;
        $themePreview['type'] = 'preview';
        // dd($themeUrl);
        if ($themeUrl) {
            // return view('frontEnd.' . $themeUrl . '.order_confirmed');
            return view('frontEnd.'.$themeUrl.'.checkout', compact('shipping_methods', 'themePreview'));
        }
    }

    public function placeOrder(Request $request)
    {
        // dd(Cart::getContent());
        $request->validate([
            'customer_name' => 'required',
            'customer_phone' => 'required | digits:11',
        ]);

        // ip address
        $ip_address = IpAddress::where('ip_address', $request->ip())->first();
        if ($ip_address) {
            if ($ip_address->status == 0) {
                return redirect()->route('home')->with('error', 'You are blocked');
            } elseif ($ip_address->status == 1) {
                $ip_address->increment('total_order');
            }
        } else {
            IpAddress::create([
                'ip_address' => $request->ip(),
                'total_order' => 1,
                'status' => 1,
            ]);
        }

        // check user block or not
        $user = User::where('phone', $request->customer_phone)->first();
        if ($user) {
            if ($user->status == 0) {
                return redirect()->route('home')->with('error', 'You are blocked');
            }
        }

        $carts = Cart::getContent();
        if ($carts->count() > 0) {

            // create customer account
            $check_cus = User::where('phone', $request->customer_phone)->first();
            if ($check_cus) {
                $customer_id = $check_cus;
            } else {
                $customer_id = User::create([
                    'name' => $request->customer_name,
                    'phone' => $request->customer_phone,
                    'address' => $request->customer_address,
                    'password' => Hash::make($request->customer_phone),
                ]);
            }

            Auth::login($customer_id);
            $order_input = array_merge($request->all(), [
                'invoice_id' => invoice_generate(),
                'order_date' => Carbon::now()->toDateString(),
                'customer_id' => $customer_id->id,
                'shipping_method' => $request->shipping_method_id,
                'sub_total' => \Cart::getSubTotal(),
                'total' => (\Cart::getTotal() + $request->shipping_cost),
                'status' => 1,
                'source' => 'direct',
                'ip_address' => $request->ip(),
                'due' => (\Cart::getTotal() + $request->shipping_cost),
            ]);
            $order_id = Order::create($order_input);

            // add product into order product table
            foreach ($carts as $item) {
                $quantity = $item->quantity;
                $attributes = $item->attributes[0] ?? null;
                $product = Product::find($item->associatedModel->id);
                $variant_name = null;

                if ($product && $product->has_variant == 1 && $attributes) {
                    $product_attr = ProductVariant::with('items')
                        ->where('sku', $item->id)
                        ->where('variant', $attributes)
                        ->first();

                    if ($product_attr) {
                        $variant_name = $product_attr->items->map(fn ($it) => $it->name)->implode(', ');
                        $product_attr->decrement('stock', $quantity);
                        $product->decrement('stock', $quantity);
                    }
                } elseif ($product) {
                    if ($product->is_package == 1) {
                        $product->decrement('stock', $quantity * $product->package_qty);
                    } elseif ($product->is_combo == 1) {
                        $comboItems = $product->comboItems()->get();
                        foreach ($comboItems as $comboItem) {
                            $comboItem->product->decrement('stock', $comboItem->quantity * $quantity);
                        }
                        $product->decrement('stock', $quantity);
                    } else {
                        $product->decrement('stock', $quantity);
                    }
                }

                $products[] = [
                    'product_id' => $product->id ?? $item->associatedModel->id,
                    'product_sku' => $item->id,
                    'qty' => $quantity,
                    'price' => $item->price,
                    'variant_name' => $variant_name,
                ];

                OrderProduct::create([
                    'order_id' => $order_id->id,
                    'product_id' => $product->id ?? $item->associatedModel->id,
                    'product_sku' => $item->id,
                    'qty' => $quantity,
                    'price' => $item->price,
                    'attributes' => $attributes,
                ]);
            }

            // abandoned cart delete
            if (session()->has('abandoned_cart_id')) {
                // update abandoned cart
                $abandoned = AbandonedCart::where('id', session()->get('abandoned_cart_id'))->first();
                $abandoned->delete();
                session()->forget('abandoned_cart_id');
            }

            // assign employee
            $b = Admin::with('get_orders')->where('is_order_assign', 1)->where('status', 1)->get();
            // dd($b);
            if ($b->count() > 0) {
                $a = $b->min('get_orders');
                // dd($a);
                if ($a->isNotEmpty()) {
                    $b = $a->pluck('employee_id')->toArray();
                    $assign = OrderAssign::create([
                        'order_id' => $order_id->id,
                        'employee_id' => $b[array_rand($b)],
                    ]);
                } else {
                    $i = $b->random()->id;
                    OrderAssign::create([
                        'order_id' => $order_id->id,
                        'employee_id' => $i,
                    ]);
                }
            }

            // customer note
            if ($request->customer_note) {
                OrderNote::create([
                    'order_id' => $order_id->id,
                    'note' => $request->customer_note,
                    'user_id' => $customer_id->id,
                    'user_type' => 'customer',
                ]);
            }

            $old_data = array_merge($order_input, [
                'products' => $products,
            ]);
            $order_history = [
                'order_id' => $order_id->id,
                'user_type' => 'Customer',
                'activity_type' => 1,
                'created_by' => $customer_id->id,
                'text' => strtr(config('order_activities.new_order'), [
                    '{user_name}' => $customer_id->name,
                ]),
                'comment' => null,
                'old_order' => $old_data,
            ];
            order_activities($order_history);

            // clear the cart
            \Cart::clear();

            return redirect()->route('confirm.order')->with('success', 'Order Placed Successfully');
        } else {
            return redirect()->route('home')->with('error', 'Please Select Products');
        }
    }

    public function confirmOrder()
    {
        $activeTheme = Theme::where(['is_active' => 1, 'status' => 1])->first()->path;
        // dd($activeTheme);
        if ($activeTheme) {
            return view('frontEnd.'.$activeTheme.'.order_confirmed');
        }
    }

    public function search(Request $request)
    {
        // dd($request->all());
        $data = Product::query();
        if ($request->input('query')) {
            $query = $request->input('query');
            $data = Product::with('get_thumb')->where('name', 'LIKE', "%{$query}%")->paginate(35);
        }
        $activeTheme = Theme::where(['is_active' => 1, 'status' => 1])->first()->path;
        // dd($activeTheme);
        if ($activeTheme) {
            return view('frontEnd.'.$activeTheme.'.searched_products', compact('data', 'query'));
        }
        // dd($data);

    }

    public function ajaxGetAttributes(Request $request)
    {
        // dd($request->all());
        $productId = $request->product_id;
        $selectedItemIds = $request->attribute_item_id;

        if (! $productId || ! $selectedItemIds || ! is_array($selectedItemIds)) {
            return response()->json([
                'success' => 400,
                'message' => 'Invalid request data.',
            ]);
        }

        // Product সহ তার Variants লোড করা
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

        // Selected attribute item ids থেকে variant key বানানো
        // (যেমন: [2,5,7] => "2-5-7")
        $variantKey = implode('-', $selectedItemIds);

        // Variant খুঁজা
        $matchedVariant = $product->get_variants->firstWhere('variant', $variantKey);

        if (! $matchedVariant) {
            return response()->json([
                'success' => 404,
                'message' => 'Variant not found for selected attributes.',
            ]);
        }

        // Response
        return response()->json([
            'success' => 200,
            'message' => 'Variant found successfully.',
            'data' => [
                'sku' => $matchedVariant->sku,
                'regular_price' => $matchedVariant->regular_price,
                'sale_price' => $matchedVariant->sale_price,
                'stock' => $matchedVariant->stock,
                'variant_items' => $matchedVariant->items->map(function ($item) {
                    return [
                        'attribute_item' => $item->attribute_item->name ?? null,
                    ];
                }),
            ],
        ]);
    }

    public function ajaxGetAttributeRelated(Request $request)
    {
        // dd($request->all());
        $productId = $request->product_id_related;
        $selectedItemIds = $request->attribute_item_id_related;

        if (! $productId || ! $selectedItemIds || ! is_array($selectedItemIds)) {
            return response()->json([
                'success' => 400,
                'message' => 'Invalid request data.',
            ]);
        }

        // Product সহ তার Variants লোড করা
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

        // Selected attribute item ids থেকে variant key বানানো
        // (যেমন: [2,5,7] => "2-5-7")
        $variantKey = implode('-', $selectedItemIds);

        // Variant খুঁজা
        $matchedVariant = $product->get_variants->firstWhere('variant', $variantKey);

        if (! $matchedVariant) {
            return response()->json([
                'success' => 404,
                'message' => 'Variant not found for selected attributes.',
            ]);
        }

        // Response
        return response()->json([
            'success' => 200,
            'message' => 'Variant found successfully.',
            'data' => [
                'sku' => $matchedVariant->sku,
                'regular_price' => $matchedVariant->regular_price,
                'sale_price' => $matchedVariant->sale_price,
                'stock' => $matchedVariant->stock,
                'variant_items' => $matchedVariant->items->map(function ($item) {
                    return [
                        'attribute_item' => $item->attribute_item->name ?? null,
                    ];
                }),
            ],
        ]);
    }

    public function reviewStore(Request $request)
    {
        // dd($request->all());
        $request->validate(
            [
                'img' => 'array|max:6',
                'img.*' => 'mimes:jpg,jpeg,png,bmp,tiff|max:5120',
                'rating' => 'required',
            ]
        );

        // store review data
        $review = Review::create($request->all());

        // store review images
        $rev_img = $request->file('img');
        if ($rev_img) {
            foreach ($rev_img as $file) {
                $file_name = uniqid().'.'.$file->getClientOriginalExtension();
                $destinationPath = public_path('uploads/review_images');
                $file->move($destinationPath, $file_name);

                $url = 'uploads/review_images/'.$file_name;

                ReviewImage::create([
                    'review_id' => $review->id,
                    'file_url' => $url,
                ]);
            }
        }

        return response()->json(['success' => 200]);
    }

    public function abandonedCart(Request $request)
    {

        $carts = Cart::getContent();
        $total = 0;
        $subtotal = 0;
        foreach ($carts as $key => $item) {
            $abandoned_item[$key] = [
                'product_id' => $item->associatedModel->id,
                'qty' => $item->quantity,
                'price' => $item->price,
                'sku' => $item->id,
                'variant' => $item->attributes->count() > 0 ? $item->attributes[0] : null,
            ];
            $total += $item->quantity * $item->price;
            $subtotal += $item->quantity * $item->price;
        }
        $abandoned_item = json_encode($abandoned_item);
        $input = [
            'customer_name' => $request->data['name'],
            'customer_phone' => $request->data['phone'],
            'customer_address' => $request->data['address'],
            'shipping_cost' => $request->data['shipping_cost'] ?? 0,
            'total' => $total + $request->data['shipping_cost'] ?? 0,
            'subtotal' => $subtotal,
            'abandoned_item' => $abandoned_item,
        ];
        // dd(session()->get('abandoned_cart_id'));
        // dd(session()->get('abandoned_cart_id'));
        if (session()->has('abandoned_cart_id')) {
            $abandoned = AbandonedCart::where('id', session()->get('abandoned_cart_id'))->first();
            if ($abandoned) {
                $abandoned->update($input);
            } else {
                $id = AbandonedCart::create($input);
                session()->put('abandoned_cart_id', $id->id);
            }
        } else {
            $id = AbandonedCart::create($input);
            session()->put('abandoned_cart_id', $id->id);
        }
    }

    // track order
    public function trackOrder(Request $request)
    {
        $order = '';
        if ($request->invoice_id) {
            $order = Order::with('get_products')->where('invoice_id', $request->invoice_id)->first();

            return view('frontEnd.pages.track_order', compact('order'));
        } else {

            return view('frontEnd.pages.track_order', compact('order'));
        }
    }

    // newsletter
    public function newsletter(Request $request)
    {
        $request->validate([
            'email' => 'required | email',
        ]);

        $check = DB::table('subscribes')->where('email', $request->email)->first();
        if ($check) {
            return response()->json(['success' => 201]);
        } else {
            DB::table('subscribes')->insert(['email' => $request->email]);

            return response()->json(['success' => 200]);
        }
    }
}
