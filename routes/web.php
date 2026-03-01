<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\RoleController;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\FlashDealController;
use App\Http\Controllers\IpAddressController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\OrderAjaxController;
use App\Http\Controllers\ApiCourierController;
use App\Http\Controllers\DeviceTrackController;
use App\Http\Controllers\WebSettingsController;
use App\Http\Controllers\ComboProductController;
use App\Http\Controllers\PageSettingsController;
use App\Http\Controllers\AbandonedCartController;
use App\Http\Controllers\ShippingMethodController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\HomePageSettingController;
use App\Http\Controllers\PromotionalBannerController;
use App\Http\Controllers\AccountTransactionController;
use App\Http\Controllers\PrintSettingsController;
use Illuminate\Foundation\Application;

Route::get('/cc', function () {
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');

    // \Illuminate\Support\Facades\Artisan::call('config:cache');
    return 'Cleared!';
});

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/about', [\App\Http\Controllers\HomeController::class, 'aboutUs'])->name('about.us');

/*Route::get('/{any}', function () {
    return view('frontend');
})->where('any', '^(?!admin).*$');*/

// webhook pataho
Route::post('pathao-webhook', [AdminController::class, 'pathaoWebhook']);

// webhook steadfast
Route::post('steadfast-webhook', [AdminController::class, 'steadfastWebhook']);

// webhook redx
Route::post('redx-webhook', [AdminController::class, 'redxWebhook']);


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['middleware' => 'admin.guest'], function () {
    Route::get('/admin-login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin-login', [AdminLoginController::class, 'login']);
});
Route::post('/admin-logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
// Route::post('/customer-logout', [Auth\AdminLoginController::class, 'logout'])->name('customer.logout');

Route::group(['middleware' => 'admin.auth'], function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.home');

    // combo product route resoure controller
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('combo-products', ComboProductController::class);
        // AJAX helpers
        Route::get('combo-products/check-sku', [ComboProductController::class, 'checkSku'])->name('combo-products.check-sku');
        Route::post('combo-products/fetch-products', [ComboProductController::class, 'fetchProductsDetails'])->name('combo-products.fetch-products');
    });
    // combo product route resoure controller
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('themes', ThemeController::class);
        // preview
        Route::get('themes/activate/{id}/{status}', [ThemeController::class, 'activate'])->name('themes.activate');
        // status
        Route::get('themes/status/{id}', [ThemeController::class, 'status'])->name('themes.status');
    });

    // device
    Route::get('/admin/device', [DeviceTrackController::class, 'index'])->name('admin.device');
    Route::get('/admin/device/{id}/{status}', [DeviceTrackController::class, 'status'])->name('admin.customer.device.status');

    // newsletter
    Route::get('/admin-newsletter', [AdminController::class, 'newsletter'])->name('admin.newsletter');
    // delete
    Route::delete('/admin-newsletter/{id}', [AdminController::class, 'newsletterDelete'])->name('admin.newsletter.delete');

    // review route
    Route::get('/admin/reviews', [ReviewController::class, 'index'])->name('admin.reviews');
    Route::get('/admin/review-delete/{id}', [ReviewController::class, 'delete'])->name('admin.review.delete');
    Route::get('/admin/review-status/{id}/{status}', [ReviewController::class, 'status'])->name('admin.review.status');
    Route::delete('/admin/review/{id}', [ReviewController::class, 'destroy'])->name('admin.review.destroy');
    // report
    Route::get('/admin/report-profit-loss', [ReportController::class, 'profit_loss'])->name('admin.report.profit.loss');
    Route::get('/admin/report-account-trans', [ReportController::class, 'account_trans'])->name('admin.report.account.trans');
    Route::get('/admin/report-product-stock', [ReportController::class, 'productStock'])->name('admin.report.product.stock');
    //   Route::get('/report-employee', [ReportController::class, 'employeeReport'])->name('report.employee');
    Route::get('/admin/report-courier', [ReportController::class, 'courierReport'])->name('admin.report.courier');
    Route::get('/admin/report-product', [ReportController::class, 'productReport'])->name('admin.report.product');

    // admin.report.sales
    Route::get('/admin/report-sales-orders', [ReportController::class, 'salesOrderReport'])->name('admin.report.sales.order');
    Route::get('/admin/report-sales-products', [ReportController::class, 'salesProductReport'])->name('admin.report.sales.product');

    // expense category
    Route::get('/admin/expense-categories', [ExpenseCategoryController::class, 'index'])->name('admin.expense.category.index');
    Route::post('/admin/expense-category-store', [ExpenseCategoryController::class, 'store'])->name('admin.expense.category.store');
    Route::post('/admin/expense-category-update', [ExpenseCategoryController::class, 'update'])->name('admin.expense.category.update');
    Route::get('/admin/expense-category-status/{id}/{status}', [ExpenseCategoryController::class, 'status'])->name('admin.expense.category.status');
    Route::delete('/admin/expense-category-delete/{id}', [ExpenseCategoryController::class, 'delete'])->name('admin.expense.category.delete');

    // expense
    Route::get('/admin/expenses', [ExpenseController::class, 'index'])->name('admin.expense.index');
    Route::post('/admin/expense-store', [ExpenseController::class, 'store'])->name('admin.expense.store');
    Route::post('/admin/expense-update', [ExpenseController::class, 'update'])->name('admin.expense.update');
    Route::get('/admin/expense-status/{id}/{status}', [ExpenseController::class, 'status'])->name('admin.expense.status');
    Route::delete('/admin/expense-delete/{id}', [ExpenseController::class, 'delete'])->name('admin.expense.delete');

    // account
    Route::get('/admin/accounts', [AccountController::class, 'index'])->name('admin.account.index');
    Route::post('/admin/account-store', [AccountController::class, 'store'])->name('admin.account.store');
    Route::post('/admin/account-add-balance', [AccountController::class, 'addBalance'])->name('admin.account.add.balance');
    Route::post('/admin/account-update', [AccountController::class, 'update'])->name('admin.account.update');
    Route::get('/admin/account-default-status/{id}/{status}', [AccountController::class, 'setDefaultAccount'])->name('admin.account.default.status');
    Route::get('/admin/account-status/{id}/{status}', [AccountController::class, 'status'])->name('admin.account.status');
    Route::delete('/admin/account-delete/{id}', [AccountController::class, 'delete'])->name('admin.account.delete');

    // account transaction
    Route::get('/admin/account-transactions', [AccountTransactionController::class, 'index'])->name('admin.account.transaction.index');

    // abandoned cart
    Route::get('/admin/abandoned-cart', [AbandonedCartController::class, 'index'])->name('admin.abandoned.cart');
    Route::get('/admin/abandoned-cart/{id}/delete', [AbandonedCartController::class, 'delete'])->name('admin.abandoned.cart.delete');
    // create order from abandoned cart
    Route::get('/admin/abandoned-cart/{id}/create-order', [AbandonedCartController::class, 'createOrder'])->name('admin.abandoned.cart.create.order');
    Route::post('/admin/abandoned-cart/bulk-create', [AbandonedCartController::class, 'bulkCreate'])->name('admin.abandoned.cart.bulk.create.order');
    Route::post('/admin/abandoned-cart/update-note', [AbandonedCartController::class, 'updateNote'])->name('admin.abandoned.cart.update.note');
    Route::post('/admin/abandoned-cart/update-field', [AbandonedCartController::class, 'updateField'])->name('admin.abandoned.cart.update.field');
    // staff
    Route::get('/admin/staffs', [StaffController::class, 'index'])->name('admin.staff.index');
    Route::get('/admin/staff-create', [StaffController::class, 'create'])->name('admin.staff.create');
    Route::post('/admin/staff-store', [StaffController::class, 'store'])->name('admin.staff.store');
    Route::get('/admin/staff-edit/{id}', [StaffController::class, 'edit'])->name('admin.staff.edit');
    Route::post('/admin/staff-update', [StaffController::class, 'update'])->name('admin.staff.update');
    Route::get('/admin/staff-delete/{id}', [StaffController::class, 'delete'])->name('admin.staff.delete');
    Route::get('/admin/staff-status/{id}/{status}', [StaffController::class, 'status'])->name('admin.staff.status');
    // roles
    Route::get('/admin/roles', [RoleController::class, 'index'])->name('admin.role.index');
    Route::get('/admin/role-create', [RoleController::class, 'create'])->name('admin.role.create');
    Route::post('/admin/role-store', [RoleController::class, 'store'])->name('admin.role.store');
    Route::get('/admin/role-edit/{id}', [RoleController::class, 'edit'])->name('admin.role.edit');
    Route::post('/admin/role-update/{id}', [RoleController::class, 'update'])->name('admin.role.update');
    Route::get('/admin/role-delete/{id}', [RoleController::class, 'delete'])->name('admin.role.delete');

    // Route::get('/roles', [RoleController::class, 'index'])->name('role.index')->middleware('permission:role_list');
    // Route::get('/role-create', [RoleController::class, 'create'])->name('role.create')->middleware('permission:role_create');
    // Route::post('/role-store', [RoleController::class, 'store'])->name('role.store');
    // Route::get('/role-edit/{id}', [RoleController::class, 'edit'])->name('role.edit')->middleware('permission:role_edit');
    // Route::post('/role-update/{id}', [RoleController::class, 'update'])->name('role.update');
    // Route::get('/role-delete/{id}', [RoleController::class, 'delete'])->name('role.delete')->middleware('permission:role_delete');

    // ip address
    Route::get('/admin-ip-address', [IpAddressController::class, 'index'])->name('admin.ip.address');
    Route::delete('admin-ip-address/delete/{id}', [IpAddressController::class, 'delete'])->name('admin.ip.address.delete');
    Route::get('admin-ip-address/{id}/status', [IpAddressController::class, 'status'])->name('admin.ip.address.status');

    // visitor
    Route::get('/admin-visitor', [VisitorController::class, 'index'])->name('admin.visitor.index');
    Route::get('/admin-visitor-filter', [VisitorController::class, 'visitorFilter'])->name('admin.visitor.filter');
    Route::delete('/admin-visitor/{id}/delete', [VisitorController::class, 'delete'])->name('admin.visitor.delete');
    // admin.visitor.unique.list
    Route::get('/admin-visitor-unique-list', [VisitorController::class, 'uniqueVisitorList'])->name('admin.visitor.unique.list');

    // purchase
    Route::get('/admin-purchase', [PurchaseController::class, 'index'])->name('admin.purchase');
    Route::get('/admin-purchase/create', [PurchaseController::class, 'create'])->name('admin.purchase.create');
    Route::post('/admin-purchase/store', [PurchaseController::class, 'store'])->name('admin.purchase.store');
    Route::get('/admin-purchase/{id}/edit', [PurchaseController::class, 'edit'])->name('admin.purchase.edit');
    Route::post('/admin-purchase/{id}/update', [PurchaseController::class, 'update'])->name('admin.purchase.update');
    Route::delete('/admin-purchase/delete/{id}', [PurchaseController::class, 'delete'])->name('admin.purchase.delete');
    Route::get('/admin-purchase/{id}/status', [PurchaseController::class, 'status'])->name('admin.purchase.status');
    Route::post('/admin-ajax-get-purchase-product', [PurchaseController::class, 'ajaxGetPurchaseProduct'])->name('admin.ajax.get.purchase.product');

    // supplier
    Route::get('/admin-supplier', [SupplierController::class, 'index'])->name('admin.supplier');
    Route::post('/admin-supplier/store', [SupplierController::class, 'store'])->name('admin.supplier.store');
    Route::post('/admin-supplier/update', [SupplierController::class, 'update'])->name('admin.supplier.update');
    Route::get('/admin-supplier/delete/{id}', [SupplierController::class, 'delete'])->name('admin.supplier.delete');
    Route::get('/admin-supplier-status', [SupplierController::class, 'status'])->name('admin.supplier.status');

    // promotional banner
    // Route::get('/admin-promotional-banner', [PromotionalBannerController::class, 'index'])->name('admin.promotional.banner');
    // Route::post('/admin-promotional-banner/update', [PromotionalBannerController::class, 'update'])->name('admin.promotional.banner.update');

    Route::get('/admin-promotional-banner', [PromotionalBannerController::class, 'edit'])->name('admin.promotional.banner.edit');
    Route::post('/admin-promotional-banner/update', [PromotionalBannerController::class, 'updateOrCreate'])->name('admin.promotional.banner.updateOrCreate');



    // api config
    Route::get('/admin-api-pathao', [ApiCourierController::class, 'pathao'])->name('admin.api.pathao');
    Route::post('/admin-pathao-api-update', [ApiCourierController::class, 'pathaoUpdate'])->name('admin.pathao.api.update');
    Route::post('/admin-api-pathao-genereate', [ApiCourierController::class, 'pathaoGenerate'])->name('admin.api.pathao.generate');
    Route::get('/admin-api-redx', [ApiCourierController::class, 'redx'])->name('admin.api.redx');
    Route::post('/admin-redx-api-update', [ApiCourierController::class, 'redxUpdate'])->name('admin.redx.api.update');
    Route::get('/admin-api-steadfast', [ApiCourierController::class, 'steadfast'])->name('admin.api.steadfast');
    Route::post('/admin-steadfast-api-update', [ApiCourierController::class, 'steadfastUpdate'])->name('admin.steadfast.api.update');
    Route::get('/admin-api-carrybee', [ApiCourierController::class, 'carrybee'])->name('admin.api.carrybee');
    Route::post('/admin-carrybee-api-update', [ApiCourierController::class, 'carrybeeUpdate'])->name('admin.carrybee.api.update');




    // ckeditor upload
    Route::post('/admin-ckeditor-upload', [AdminController::class, 'ckeditorUpload'])->name('admin.ckeditor.upload');

    // page settings
    Route::get('/admin-settings-page', [PageSettingsController::class, 'index'])->name('admin.settings.page');
    Route::post('/admin-settings-page', [PageSettingsController::class, 'update'])->name('admin.settings.page.update');

    // web settings
    Route::get('/admin-settings-web', [WebSettingsController::class, 'index'])->name('admin.settings.web');
    Route::post('/admin-settings-web', [WebSettingsController::class, 'update'])->name('admin.settings.web.update');

    // Print settings
    Route::get('/admin-settings-print', [PrintSettingsController::class, 'index'])->name('admin.settings.print');
    Route::post('/admin-settings-print', [PrintSettingsController::class, 'update'])->name('admin.settings.print.update');

    // attribute settings
    Route::get('/admin-settings-attribute', [WebSettingsController::class, 'attribute'])->name('admin.settings.attribute');
    Route::post('/admin-settings-attribute/store', [WebSettingsController::class, 'attributeStore'])->name('admin.settings.attribute.store');
    Route::post('/admin-settings-attribute/update', [WebSettingsController::class, 'attributeUpdate'])->name('admin.settings.attribute.update');
    Route::get('/admin-settings-attribute/{id}/delete', [WebSettingsController::class, 'attributeDelete'])->name('admin.settings.attribute.delete');
    // attribute item settings
    Route::post('/admin-settings-attribute_item/store', [WebSettingsController::class, 'attributeItemStore'])->name('admin.settings.attribute_item.store');
    Route::post('/admin-settings-attribute_item/update', [WebSettingsController::class, 'attributeItemUpdate'])->name('admin.settings.attribute_item.update');
    Route::get('/admin-settings-attribute_item/{id}/delete', [WebSettingsController::class, 'attributeItemDelete'])->name('admin.settings.attribute_item.delete');

    //admin home page settings
    Route::get('/admin-settings-home-page', [HomePageSettingController::class, 'index'])->name('admin.settings.home.page');
    Route::post('/admin-settings-home-page', [HomePageSettingController::class, 'update'])->name('admin.home.page.setting.update');

    // admin.settings.conversion-api
    Route::get('/admin-marketing-api', [MarketingController::class, 'index'])->name('admin.marketing.api');
    Route::post('/admin-marketing-api', [MarketingController::class, 'update'])->name('admin.marketing.api.update');


    // change password
    Route::get('/admin-change_pass', [AdminController::class, 'change_pass'])->name('admin.change_pass');
    Route::post('/admin-change_pass', [AdminController::class, 'update_pass'])->name('admin.update_pass');

    // edit profile
    Route::get('/admin-edit_profile', [AdminController::class, 'edit_profile'])->name('admin.edit_profile');
    Route::post('/admin-edit_profile', [AdminController::class, 'update_profile'])->name('admin.update_profile');

    // fraud checker
    Route::get('/admin-fraud-checker/{id}', [AdminController::class, 'fraudChecker'])->name('admin.fraud.checker');

    // customers
    Route::get('/admin-customers', [UserController::class, 'index'])->name('admin.customers');
    Route::post('/admin-customer/store', [UserController::class, 'store'])->name('admin.customer.store');
    Route::post('/admin-customer/update', [UserController::class, 'update'])->name('admin.customer.update');
    Route::delete('/admin-customer/delete/{id}', [UserController::class, 'delete'])->name('admin.customer.delete');
    Route::get('/admin-status/{id}/{status}', [UserController::class, 'status'])->name('admin.customer.status');
    Route::get('/admin-customer-order', [UserController::class, 'customerOrder'])->name('admin.customer.order');

    // media
    Route::get('/admin-media', [MediaController::class, 'index'])->name('admin.media');
    Route::post('/admin-media/store', [MediaController::class, 'store'])->name('admin.media.store');
    Route::post('/admin-media/update', [MediaController::class, 'update'])->name('admin.media.update');
    Route::get('/admin-media/delete/{id}', [MediaController::class, 'delete'])->name('admin.media.delete');

    // product
    Route::get('/admin-product', [ProductController::class, 'index'])->name('admin.product');
    Route::get('/admin-product/create', [ProductController::class, 'create'])->name('admin.product.create');
    Route::post('/admin-product/store', [ProductController::class, 'store'])->name('admin.product.store');
    Route::get('/admin-product/{id}/edit', [ProductController::class, 'edit'])->name('admin.product.edit');
    Route::post('/admin-product/{id}/update', [ProductController::class, 'update'])->name('admin.product.update');
    Route::get('/admin-product/{id}/delete', [ProductController::class, 'delete'])->name('admin.product.delete');
    Route::post('/admin-product/sku_check', [ProductController::class, 'skuCheck'])->name('admin.product.sku_check');
    Route::get('/admin/check-unique-product-slug', [ProductController::class, 'checkUniqueslug'])->name('admin.check.product.unique.slug');
    Route::post('/admin-product/free-shipping', [ProductController::class, 'freeShipping'])->name('admin.free.shipping');
    Route::get('/admin-product/{id}/status', [ProductController::class, 'status'])->name('admin.product.status');

    Route::post('/admin/product-bulk-delete', [ProductController::class, 'bulkDelete'])->name('admin.product.bulk.delete');

    Route::post('/admin/product/ajax-get-color-image', [ProductController::class, 'ajaxGetColorImage'])->name('admin.product.ajax.get.color.image');
    Route::post('/admin/product/ajax-get-color-image-edit', [ProductController::class, 'ajaxGetColorImageEdit'])->name('admin.product.ajax.get.color.image.edit');
    Route::post('/admin/product/ajax-get-combined-attributes', [ProductController::class, 'ajaxGetCombinedAttributes'])->name('admin.product.ajax.get.combined.attributes');
    Route::post('/admin/product/ajax-get-combined-attributes-edit', [ProductController::class, 'ajaxGetCombinedAttributesEdit'])->name('admin.product.ajax.get.combined.attributes.edit');

    // product attribute item create routes
    Route::post('admin/product-attribute-item-store', [ProductController::class, 'attributeItemStore'])->name('admin.product.attribute.item.store');

    // check unique product sku
    Route::get('/check-unique-product-sku', [ProductController::class, 'checkUniqueSku'])->name('admin.check.product.unique.sku');

    // check unique product slug
    Route::get('/check-product-slug', [ProductController::class, 'checkSlug'])->name('product.check.slug');

    Route::post('/product/quick-slug-update', [ProductController::class, 'quickSlugUpdate'])
        ->name('admin.product.quickSlugUpdate');

    // get landing page attribute
    Route::post('/ajax-get-landing-page-attributes', [ProductController::class, 'ajaxGetLandingPageVariant'])->name('admin.ajax.get.landing.page.attribute');

    // get landing page attribute for edit
    Route::post('/ajax-get-landing-page-attributes-edit', [ProductController::class, 'ajaxGetLandingPageVariantEdit'])->name('admin.ajax.get.landing.page.attribute.edit');

    // create new attribute item
    Route::post('/attribute-item-create', [ProductController::class, 'createVariantItem'])->name('admin.product.create.attribute.item');
    Route::post('/ajax-get-attribute-item', [ProductController::class, 'ajaxGetVariantItem'])->name('admin.ajax.get.attribute.item');

    // get attribute combination
    Route::post('ajax-get-product-attribute-combination', [ProductController::class, 'attributeCombination'])->name('admin.ajax.get.product.attribute.combination');
    Route::post('ajax-get-product-attribute-combination-edit', [ProductController::class, 'attributeCombinationEdit'])->name('admin.ajax.get.product.attribute.combination.edit');

    // product attribute
    Route::get('/admin-get-product-attribute', [ProductController::class, 'getProductAttribute'])->name('admin.ajax.get.attribute');
    Route::get('/admin-get-product-attribute-item-combination', [ProductController::class, 'getProductAttributeItemCombination'])->name('admin.ajax.get.attribute.item.combination');

    Route::post('/product/update-flag', [ProductController::class, 'updateFlag'])->name('product.updateFlag');
    Route::post('/product/update-position', [ProductController::class, 'updatePosition'])->name('product.updatePosition');

    // category
    Route::get('/admin-category', [CategoryController::class, 'index'])->name('admin.category');
    Route::post('/admin-category/store', [CategoryController::class, 'store'])->name('admin.category.store');
    Route::post('/admin-category/update', [CategoryController::class, 'update'])->name('admin.category.update');
    Route::get('/admin-category/delete/{id}', [CategoryController::class, 'delete'])->name('admin.category.delete');
    Route::get('/admin-category/status/{id}/status', [CategoryController::class, 'status'])->name('admin.category.status');
    Route::get('category/slug-check', [CategoryController::class, 'slugCheck'])
        ->name('admin.category.slugCheck');
    Route::post('/category/slug-update', [CategoryController::class, 'slugUpdate'])
        ->name('admin.category.slug.update');
    Route::post('/category/update-position', [CategoryController::class, 'updatePosition'])->name('category.update.position');

    // sliders
    Route::get('/admin-sliders', [SliderController::class, 'index'])->name('admin.sliders');
    Route::post('/admin-sliders/store', [SliderController::class, 'store'])->name('admin.sliders.store');
    Route::post('/admin-sliders/update', [SliderController::class, 'update'])->name('admin.sliders.update');
    Route::get('/admin-sliders/delete/{id}', [SliderController::class, 'delete'])->name('admin.sliders.delete');
    Route::get('/admin-sliders/status/{id}/status', [SliderController::class, 'status'])->name('admin.slider.status');
    Route::post('/admin-slider/update-position', [SliderController::class, 'updatePosition'])->name('admin.slider.update.position');

    // shipping_methods
    Route::get('/admin-shipping_methods', [ShippingMethodController::class, 'index'])->name('admin.shipping_methods');
    Route::post('/admin-shipping_methods/store', [ShippingMethodController::class, 'store'])->name('admin.shipping_methods.store');
    Route::post('/admin-shipping_methods/update', [ShippingMethodController::class, 'update'])->name('admin.shipping_methods.update');
    Route::get('/admin-shipping_methods/delete/{id}', [ShippingMethodController::class, 'delete'])->name('admin.shipping_methods.delete');
    Route::get('/admin-shipping_methods/status/{id}/status', [ShippingMethodController::class, 'status'])->name('admin.shipping.methods.status');

    // courier
    Route::get('/admin-courier', [CourierController::class, 'index'])->name('admin.courier');
    Route::post('/admin-courier/store', [CourierController::class, 'store'])->name('admin.courier.store');
    Route::post('/admin-courier/update', [CourierController::class, 'update'])->name('admin.courier.update');
    Route::get('/admin-courier/delete/{id}', [CourierController::class, 'delete'])->name('admin.courier.delete');
    Route::post('/admin-courier-ajax_get_c_charge', [CourierController::class, 'ajaxGetCCharge'])->name('admin.courier.ajax.get.c_charge');
    // status
    Route::get('/admin-courier/status/{id}/status', [CourierController::class, 'status'])->name('admin.courier.status');

    // courier city
    Route::get('/admin-courier-city', [CourierController::class, 'cityIndex'])->name('admin.courier.city');
    Route::post('/admin-courier-city/store', [CourierController::class, 'cityStore'])->name('admin.courier.city.store');
    Route::post('/admin-courier-city/update', [CourierController::class, 'cityUpdate'])->name('admin.courier.city.update');
    Route::get('/admin-courier-city/delete/{id}', [CourierController::class, 'cityDelete'])->name('admin.courier.city.delete');
    Route::post('/admin-courier-ajax_get_cities', [CourierController::class, 'ajaxGetCities'])->name('admin.courier.ajax.get.cities');
    // status
    Route::get('/admin-courier-city/status/{id}/status', [CourierController::class, 'cityStatus'])->name('admin.courier.city.status');

    // courier zone
    Route::get('/admin-courier-zone', [CourierController::class, 'zoneIndex'])->name('admin.courier.zone');
    Route::post('/admin-courier-zone/store', [CourierController::class, 'zoneStore'])->name('admin.courier.zone.store');
    Route::post('/admin-courier-zone/update', [CourierController::class, 'zoneUpdate'])->name('admin.courier.zone.update');
    Route::get('/admin-courier-zone/delete/{id}', [CourierController::class, 'zoneDelete'])->name('admin.courier.zone.delete');
    Route::post('/admin-courier-ajax_get_zones', [CourierController::class, 'ajaxGetZones'])->name('admin.courier.ajax.get.zones');
    // status
    Route::get('/admin-courier-zone/status/{id}/status', [CourierController::class, 'zoneStatus'])->name('admin.courier.zone.status');

    // orders
    // Route::get('/admin-p_orders', 'OrderController@indexP')->name('admin.orders.p');
    Route::get('/admin-orders', [OrderController::class, 'index'])->name('admin.orders');
    Route::get('/admin-orders/create', [OrderController::class, 'create'])->name('admin.orders.create');
    Route::post('/admin-orders/store', [OrderController::class, 'store'])->name('admin.orders.store');
    Route::get('/admin-orders/{id}/edit', [OrderController::class, 'edit'])->name('admin.orders.edit');
    Route::post('/admin-orders/{id}/update', [OrderController::class, 'update'])->name('admin.orders.update');
    Route::get('/admin-orders/delete/{id}', [OrderController::class, 'delete'])->name('admin.orders.delete');
    Route::get('/admin-orders/{id}/{status}/status', [OrderController::class, 'statusChange'])->name('admin.orders.status');
    Route::post('/admin-orders/all-status', [OrderController::class, 'allStatusChange'])->name('admin.orders.all.status');
    Route::get('/admin-orders/payment/{id}/{status}', [OrderController::class, 'paymentStatus'])->name('admin.orders.payment');
    Route::post('/admin-orders/send-to-courier', [OrderController::class, 'sendToCourier'])->name('admin.orders.send.to.courier');
    Route::post('/admin-orders/send-to-courier-store', [OrderController::class, 'sendToCourierStore'])->name('admin.orders.send.to.courier.store');
    Route::post('/admin-orders/bulk-send-to-courier', [OrderController::class, 'bulkSendToCourier'])->name('admin.orders.bulk.send.to.courier');
    Route::post('/admin-orders/bulk-send-to-courier-store', [OrderController::class, 'bulkSendToCourierStore'])->name('admin.orders.bulk.send.to.courier.store');
    Route::post('/admin-orders/courier-zone', [OrderController::class, 'courierZone'])->name('admin.orders.courier.zone');
    Route::post('/admin-ajax-check-stock', [OrderController::class, 'ajaxCheckStock'])->name('admin.ajax.check.stock');
    Route::post('/admin-orders/activies', [OrderController::class, 'activies'])->name('admin.orders.activies');
    Route::post('/admin.orders.view.more', [OrderController::class, 'viewMore'])->name('admin.orders.view.more');
    Route::get('/admin.orders.view.more/{id}', [OrderController::class, 'viewActivity'])->name('admin.order.activity.view');
    // trash orders
    Route::get('/admin-orders/trash', [TrashController::class, 'orderTrash'])->name('admin.orders.trash');
    Route::get('/admin-orders/trash/{id}/restore', [TrashController::class, 'restore'])->name('admin.orders.restore');
    Route::get('/admin-orders/trash/{id}/force-delete', [TrashController::class, 'forceDelete'])->name('admin.orders.force.delete');
    Route::post('/admin-orders/bulk-assign', [OrderController::class, 'bulkAssign'])->name('admin.orders.bulk.assign');

    // customer note and staff note update two route
    Route::post('/admin-orders/customer-note', [OrderController::class, 'customerNote'])->name('admin.orders.customer.note');
    Route::post('/admin-orders/customer-note-update', [OrderController::class, 'customerNoteUpdate'])->name('admin.orders.customer.note.update');
    Route::post('/admin-orders/staff-note', [OrderController::class, 'staffNote'])->name('admin.orders.staff.note');
    Route::post('/admin-orders/staff-note-update', [OrderController::class, 'staffNoteUpdate'])->name('admin.orders.staff.note.update');
    Route::post('/admin-orders/notes', [OrderController::class, 'notes'])->name('admin.orders.notes');

    // admin.ajax.get.courier.cities
    Route::post('/admin.ajax.get.courier.cities', [OrderController::class, 'getCities'])->name('admin.ajax.get.courier.cities');

    // admin.ajax.get.courier.pathao.zones
    Route::post('/admin.ajax.get.courier.zones', [OrderController::class, 'getZones'])->name('admin.ajax.get.courier.zones');

    Route::get('/admin-orders/send-to-courier', [OrderController::class, 'sendToCourierOrderIds'])->name('admin.orders.bulk-ids.send.to.courier');

    Route::post('/admin-order-courier/send-row', [OrderController::class, 'sendToCourierItems'])->name('admin.ordercourier.send.row');

    //

    // Route::post('/admin/ajax-get-products', [OrderController::class, 'ajaxGetProducts'])->name('admin.ajax.get.products');
    // Route::post('/admin/ajax-get-products/modal', [OrderController::class, 'ajaxGetProductAttributeModal'])->name('admin.ajax.get.product.modal');
    // Route::post('admin/ajax-get-products/modal-edit', [OrderController::class, 'ajaxGetProductAttributeModalEdit'])->name('admin.ajax.get.product.modal.edit');
    // Route::post('admin/ajax-get-modal-attribute', [OrderController::class, 'ajaxGetModalChoiceAttributes'])->name('admin.ajax.get.modal.attribute');
    // Route::post('admin/ajax-get-attribute', [OrderController::class, 'ajaxGetChoiceAttributes'])->name('admin.ajax.get.attribute');

    // orders by status
    Route::get('/admin-orders/status/pending', [OrderController::class, 'orderStatusPending'])->name('admin.orders.status.pending');
    Route::get('/admin-orders/status/confirm', [OrderController::class, 'orderStatusConfirm'])->name('admin.orders.status.confirm');
    Route::get('/admin-orders/status/processing', [OrderController::class, 'orderStatusProcessing'])->name('admin.orders.status.processing');
    Route::get('/admin-orders/status/hold', [OrderController::class, 'orderStatusHold'])->name('admin.orders.status.hold');
    Route::get('/admin-orders/status/printed', [OrderController::class, 'orderStatusPrinted'])->name('admin.orders.status.printed');
    Route::get('/admin-orders/status/packaging', [OrderController::class, 'orderStatusPackaging'])->name('admin.orders.status.packaging');
    Route::get('/admin-orders/status/on-delivery', [OrderController::class, 'orderStatusOnDelivery'])->name('admin.orders.status.on.delivery');
    Route::get('/admin-orders/status/delivered', [OrderController::class, 'orderStatusDelivered'])->name('admin.orders.status.delivered');
    Route::get('/admin-orders/status/cancelled', [OrderController::class, 'orderStatusCancelled'])->name('admin.orders.status.cancelled');
    Route::get('/admin-orders/status/returned', [OrderController::class, 'orderStatusReturned'])->name('admin.orders.status.returned');
    // order ajax calls
    // Route::post('/admin-ajax-get-products', [OrderController::class, 'ajaxGetProducts'])->name('admin.ajax.get.products');
    Route::post('/admin-orders/bulk-print', [OrderController::class, 'printBulkInvoice'])->name('admin.orders.bulk.print');
    Route::post('/admin-orders/print', [OrderController::class, 'printInvoice'])->name('admin.orders.print');
    // courier courier_csv
    Route::post('/admin-orders/courier_csv', [OrderController::class, 'courierCsv'])->name('admin.orders.courier_csv');

    // roles
    // Route::get('/admin-roles', 'RoleController@index')->name('admin.roles');
    // Route::post('/admin-roles/store', 'RoleController@store')->name('admin.roles.store');
    // Route::post('/admin-roles/update', 'RoleController@update')->name('admin.roles.update');
    // Route::get('/admin-roles/{id}/{role}/delete', 'RoleController@delete')->name('admin.roles.delete');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::post('ajax/get/products', [OrderAjaxController::class, 'getProducts'])->name('ajax.get.products');
        Route::post('ajax/get/product/modal', [OrderAjaxController::class, 'getProductModal'])->name('ajax.get.product.modal');
        Route::post('ajax/get/modal/variant', [OrderAjaxController::class, 'getModalVariant'])->name('ajax.get.modal.variant');
        Route::post('ajax/get/variant', [OrderAjaxController::class, 'getVariant'])->name('ajax.get.variant');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');
        Route::get('/coupons/create', [CouponController::class, 'create'])->name('coupons.create');
        Route::post('/coupons/store', [CouponController::class, 'store'])->name('coupons.store');
        Route::get('/coupons/{id}/edit', [CouponController::class, 'edit'])->name('coupons.edit');
        Route::put('/coupons/{id}', [CouponController::class, 'update'])->name('coupons.update');
        Route::delete('/coupons/{coupon}', [CouponController::class, 'destroy'])->name('coupons.destroy');
        Route::post('/coupons/{id}/toggle', [CouponController::class, 'toggleStatus'])->name('coupons.toggle');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/flash-deals', [FlashDealController::class, 'index'])->name('flash.deals.index');
        Route::get('/flash-deal/create', [FlashDealController::class, 'create'])->name('flash.deal.create');
        Route::post('/flash-deal/store', [FlashDealController::class, 'store'])->name('flash.deal.store');
        Route::get('/flash-deal/{id}/edit', [FlashDealController::class, 'edit'])->name('flash.deal.edit');
        Route::put('/flash-deal/{id}', [FlashDealController::class, 'update'])->name('flash.deal.update');
        Route::delete('/flash-deal/{flashDeal}', [FlashDealController::class, 'destroy'])->name('flash.deal.destroy');
        Route::post('/flash-deal/{id}/toggle', [FlashDealController::class, 'toggleStatus'])->name('flash.deal.toggle');
    });

    Route::post('/summernote/upload', [\App\Http\Controllers\SummerNoteFileController::class, 'upload'])->name('summernote.upload');
    Route::post('/summernote/delete', [\App\Http\Controllers\SummerNoteFileController::class, 'delete'])->name('summernote.delete');


    Route::get('/sync-permissions', function () {

        $permissions = config('all_permission', []);
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $role = Role::firstOrCreate(
            ['name' => 'super-admin'],
            ['guard_name' => 'admin']
        );
        Permission::whereNotIn('name', $permissions)->delete();
        $user = Admin::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'role_id' => $role->id,
                'role' => 'super-admin',
                'password' => Hash::make('admin12345'),
            ]
        );

        if (! $user->hasRole($role)) {
            $user->assignRole($role);
        }

        return '✅ Permissions and Super-admin synced successfully!';
    });
});

require __DIR__.'/landing.php';
require __DIR__.'/auth.php';
