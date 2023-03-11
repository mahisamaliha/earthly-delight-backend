<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductDetailsController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AuthController;

use App\Http\Controller\WishListController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AddProductController;
use App\Http\Controllers\AuctionController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
//auth user

// Route::get('/admin', 'AdminController@index');
// Route::post('/admin/add-category', 'AdminController@addCategory');


Route::get('/app/auth_user', [AuthController::class, 'authUser'])->middleware('jwt.verify');

Route::prefix('/app')->group(function(){
    // Route::get('/auth_user', [AuthController::class, 'authUser']);
    //Landing page

    Route::get('/auction', [AuctionController::class, 'checkIsWorking']);
    
    Route::get('/landing_page/banners', [HomeController::class, 'getBanner']);
    Route::get('/landing_page/hotdeals', [HomeController::class, 'getHotDeals']);
    Route::get('/landing_page/categories', [HomeController::class, 'getLandingPageCategories']);
    Route::get('/landing_page/products', [HomeController::class, 'getLandingPageProducts']);
    Route::get('/landing_page/mailing_list', [HomeController::class, 'getMailingList']);
    Route::get('/landing_page/articles', [HomeController::class, 'getArticles']);
    Route::post('/landing_page/send_mail', [HomeController::class, 'sendMail']);

    //Shop Page
    Route::get('/shop/product/list', [ShopController::class, 'productList']);
    Route::get('/shop/categories', [ShopController::class, 'getCategories']);
    Route::get('/shop/tags', [ShopController::class, 'getTags']);
    Route::get('/shop/price_range', [ShopController::class, 'priceRange']);

    //Product Details Page
    Route::get('/product_details/{id}', [ProductDetailsController::class, 'productDetails']);
    Route::post('/getVariableProduct',[ProductDetailsController::class,'getVariableProduct']);
    Route::get('/reviews/{id}', [ProductDetailsController::class, 'reviews']);
    Route::post('/addreview',[ProductDetailsController::class,'addReview']);
    Route::post('/wishList',[HomeController::class,'addWishList']);

    // Model Product
    // Route::get('/all_main_product',[AddProductController::class,'allMainProducts']);
    // Route::get('/main_product/{id}',[AddProductController::class,'show']);
    // Route::post('/updateProductImages',[AddProductController::class,'updateProductImages']);
    // Route::post('/main_productupdate/{id}',[AddProductController::class,'updateIsNewProduct']);
    // Route::post('/main_product/{id}',[AddProductController::class,'update']);
    // Route::post('/main_product/{id}',[AddProductController::class,'destroy']);

    Route::get('/group',[AddProductController::class,'getGroup']);
    Route::get('/category',[AddProductController::class,'getCategory']);
    Route::post('/upload',[AddProductController::class,'upload']);
    Route::post('/delete_image', [AddProductController::class, 'deleteImage']);
    Route::post('/main_product',[AddProductController::class,'store']);
    Route::get('/main_product',[AddProductController::class,'index']);
    Route::post('/main_product/{id}',[AddProductController::class,'destroy']);


    //For Reset password
    Route::post('/send_reset_password_otp', [ResetPasswordController::class, 'sendResetPassOtp']);
    Route::post('/submit_reset_password_otp', [ResetPasswordController::class, 'submitResetPassOtp']);
    Route::post('/reset_password', [ResetPasswordController::class, 'resetPassword']);

    //wishlist
    Route::get('/get_wishlist',[HomeController::class,'getWishlist']);
    Route::post('/remove_wishlist/{id}', [HomeController::class, 'removeWishList']);

    //cart
    Route::get('/get_cart',[CartController::class,'getCart']);
    Route::post('/add_cart',[CartController::class,'addCart']);
    Route::post('/update_cart',[CartController::class,'updateCart']);
    Route::post('/remove_cart', [CartController::class, 'removeCart']);
    // giftVoucher
    Route::post('/checkGiftVoucherCode',[OrderController::class,'checkGiftVoucherCode']);
    // referralCode
    Route::post('/checkReferralCode',[OrderController::class,'checkReferralCode']);
    //coupon
    Route::post('/checkCoupon',[OrderController::class,'checkCoupon']);
    //contact us
    Route::post('/contact_message',[ContactUsController::class, 'contactMessage']);
    // settings
    Route::get('/settings',[DashboardController::class,'getCompanySetting']);
    //dashboard
    Route::get('/get_profile_info/{id}', [DashboardController::class, 'getProfileInfo']);
    Route::post('/user/edit', [DashboardController::class, 'saveProfileInfo']);

    Route::get('/get_orders', [DashboardController::class, 'getOrdersInfo']);
    Route::get('/get_order_details/{id}', [DashboardController::class, 'getOrderDetails']);
    Route::get('/get_pre_orders/{id}', [DashboardController::class, 'getPreOrdersInfo']);
    Route::post('/order',[OrderController::class,'storeOrder']);

    Route::get('/get_customer_info', [DashboardController::class, 'getCustomerInfo']);
    Route::get('/get_cities', [DashboardController::class, 'getCities']);
    Route::get('/get_zones', [DashboardController::class, 'getZone']);
    Route::get('/get_areas/{id}', [DashboardController::class, 'getArea']);

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/verify_email', [AuthController::class, 'verifyEmail']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::get('/auctions', 'AuctionController@index');
    Route::get('/auctions/create', 'AuctionController@create');
    Route::post('/auctions', 'AuctionController@store');
    Route::get('/auctions/{id}', 'AuctionController@show');
    Route::get('/auctions/{id}/edit', 'AuctionController@edit');
    Route::put('/auctions/{id}', 'AuctionController@update');
    Route::delete('/auctions/{id}', 'AuctionController@destroy');


    Route::get('/auctions', function () {
        $auctions = Auction::all();
    
        return response()->json($auctions);
    });

    Route::get('/accesories', function () {
        $auctions = Accesorie::all();
    
        return response()->json($accesories);
    });

});

//Auction


