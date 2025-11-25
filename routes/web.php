<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopProductPriceController;

use App\Models\Product;

Route::get('/', function () {
    $products = Product::with('category')->orderBy('name')->get();
    return view('welcome', compact('products'));
}); view('under-development');
Route::post('/trade-enquiry', [CustomerController::class, 'store'])
    ->name('trade.enquiry.store');


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.dashboard');
    // return 'customer Page';
});

Route::middleware(['auth', 'role:seller'])->group(function () {
    Route::get('/dashboard', [SellerController::class, 'index'])->name('seller.dashboard');
    Route::get('/seller/product-report', [SellerController::class, 'productReport'])
    ->name('seller.product-report');
    Route::get('/seller/products-report-pdf', [SellerController::class, 'productsReportPdf'])
    ->name('seller.products.report.pdf');

    Route::get('/category', [CategoryController::class, 'index'])->name('category');
    Route::get('/categories.create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories.store', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::PUT('/categories.update', [CategoryController::class, 'update'])->name('categories.update');
    Route::DELETE('/categories.destroy', [CategoryController::class, 'destroy'])->name('categories.destroy');


    Route::get('/subcatogory/{catid}', [SubCustomerController::class, 'index'])->name('subcatogory');
    Route::get('/subcategories/create/{catid}', [SubCustomerController::class, 'create'])->name('subcategories.create');
    Route::post('/subcategories/store', [SubCustomerController::class, 'store'])->name('subcategories.store');
    Route::get('/subcategories/edit/{id}', [SubCustomerController::class, 'edit'])->name('subcategories.edit');
    Route::put('subcategories/update/{id}', [SubCustomerController::class, 'update'])->name('subcategories.update');
    Route::delete('/subcategories//delete/{id}', [SubCustomerController::class, 'destroy'])->name('subcategories.destroy');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/update/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/destroy/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/get-subcategories/{category_id}', [ProductController::class, 'getSubcategories'])->name('get.subcategories');



    Route::get('/shops', [shopController::class, 'index'])->name('shops.index');
    Route::get('/shops/create', [shopController::class, 'create'])->name('shops.create');
    Route::post('/shops/store', [shopController::class, 'store'])->name('shops.store');
    Route::get('/shops/edit/{id}', [shopController::class, 'edit'])->name('shops.edit');
    // Route::put('/shops/update/{shop}', [shopController::class, 'update'])->name('shops.update');
    Route::put('/shops/update/{id}', [shopController::class, 'update'])->name('shops.update');
    Route::delete('/shops/destroy/{shop}', [ShopController::class, 'destroy'])->name('shops.destroy');
    // shop for UI
    Route::get('/shop', [OrderController::class, 'index'])->name('shop.index');
    Route::get('/shop/{id}', [OrderController::class, 'show'])->name('shop.show');
    Route::get('/orders/create/{shop_id}', [OrderController::class, 'productorder'])->name('orders.create');
    Route::post('/checkout/{shopid}', [OrderController::class, 'placeOrder'])->name('checkout.place');
    Route::get('/orders/{id}', [OrderController::class, 'orderDetails'])->name('order.details');
    Route::get('/orders/{id}/export', [OrderController::class, 'exportOrder'])->name('orders.export');
    Route::get('/orders/{id}/invoice', [OrderController::class, 'generateInvoice'])->name('orders.invoice');
    Route::get('/orders/{id}/send-email', [OrderController::class, 'sendEmail'])->name('orders.sendEmail');
    Route::get('/orders/{id}/manage', [OrderController::class, 'manageOrder'])->name('order.manage');
    Route::post('/orders/{id}/update-status', [OrderController::class, 'updatePaymentStatus'])->name('order.updateStatus');
    Route::post('/orders/{id}/upload-invoice', [OrderController::class, 'uploadInvoice'])->name('order.uploadInvoice');
    Route::post('/orders/{id}/add-product', [OrderController::class, 'addProduct'])->name('order.addProduct');
    Route::post('/orders/{order}/update-item', [OrderController::class, 'updateItem'])->name('order.updateItem');
    Route::get('/orders/{order}/send-whatsapp-group', [CartController::class, 'sendToWhatsappGroup'])
    ->name('orders.sendWhatsappGroup');
// Remove product
// Route::delete('/orders/{id}/remove-product/{productId}', [OrderController::class, 'removeProductFromOrder'])->name('order.removeProduct');
Route::delete('/orders/{order}/remove-product/{product}', [OrderController::class, 'removeProductFromOrder'])
    ->name('order.removeProductFromOrder'); // optional
    // cart
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkout/{shopid}', [CartController::class, 'checkout'])->name('checkout');

    Route::get('/seller/shop-prices/create', [ShopProductPriceController::class, 'create'])
    ->name('seller.shop-prices.create');

Route::post('/seller/shop-prices', [ShopProductPriceController::class, 'store'])
    ->name('seller.shop-prices.store');


});
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner', [OwnerController::class, 'index'])->name('owner.dashboard');

    return 'owner Page';
});

require __DIR__.'/auth.php';
