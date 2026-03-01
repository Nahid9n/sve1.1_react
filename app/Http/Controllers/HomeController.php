<?php

namespace App\Http\Controllers;

use App\AbandonedCart;
// use session;
use App\Admin;
use App\Attribute;
use App\Category;
use App\IpAddress;
use App\Order;
use App\OrderAssign;
use App\OrderNote;
use App\OrderProduct;
use App\Product;
use App\ProductVariant;
use App\PromotionalBanner;
use App\Review;
use App\ReviewImage;
use App\ShippingMethod;
use App\Slider;
use App\User;
use App\Variant;
use App\Wishlist;
use Carbon\Carbon;
// use Attribute;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('HomePage');
    }
    public function aboutUs(Request $request)
    {
        return Inertia::render('About');
    }

}
