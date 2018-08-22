<?php

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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//Landing Page
Route::get('/', function () {
    return view('dashboard.home');
})->name('home');

//About Us Page
Route::get('/about', function () {
    return view('dashboard.aboutus');
})->name('about_us');

//Careers Page
Route::get('/careers', function () {
    return view('dashboard.careers');
})->name('careers');

//Contact Us Page
Route::get('/contactus', function () {
    return view('dashboard.contact');
})->name('contact');

Route::post('/save-location', function (Request $request) {

    Session::put('location', $request->input('location'));
    Session::put('lat', $request->input('lat'));
    Session::put('lng', $request->input('lng'));
    return ['message' => 'success'];

})->name('save_location');

//Auth Routes

//Login and logout routes
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

//Register Routes
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');
Route::post('/pin_code','RentController@sendPinCodes')->name('get_pin_codes');

//Recover Account Routes
Route::get('/recover', 'Auth\RecoverAccountController@index')->name('recover_form');
Route::post('/recover', 'Auth\RecoverAccountController@recover')->name('recover');
Route::post('/recover/otp', 'Auth\RecoverAccountController@verifyOTP')->name('recover_verify_OTP');
Route::post('/recover/reset_password', 'Auth\RecoverAccountController@resetPassword')->name('reset_password');

//Register Routes
Route::get('/register/mobile_verfication', 'Auth\MobileVerificationController@showOTPForm')->name('show_OTP_form');
Route::get('/register/send_OTP', 'Auth\MobileVerificationController@sendOTP')->name('send_OTP');
Route::post('/register/verify_OTP', 'Auth\MobileVerificationController@verifyOTP')->name('verify_OTP');

//Account Routes
Route::get('/account/get_notification', 'Dashboard\HomeController@getNotification')->name('get_notification');
Route::post('/account/reply_notification', 'Dashboard\HomeController@replyNotification')->name('reply_notification');
Route::get('/account/{tab?}', 'Dashboard\HomeController@index')->name('account');

//Inventory Routes
Route::get('/account/inventory/products', 'Dashboard\HomeController@getInventory')->name('get_inventory');
Route::post('/account/inventory/update_availability', 'Dashboard\HomeController@updateAvailability')->name('update_availability');
Route::get('/account/inventory/{id}', 'Dashboard\LendController@getProductDetails')->name('inventory_product_details');
Route::post('/account/inventory/edit_product', 'Dashboard\LendController@editPost')->name('edit_product');

//Messages Routes
Route::get('/account/messages/get_messages', 'Dashboard\HomeController@getMessages')->name('get_messages');
Route::get('/account/messages/get_message_count', 'Dashboard\HomeController@getMessageCount')->name('get_message_count');
Route::post('/account/messages/answer_request', 'Dashboard\HomeController@answerRequest')->name('answer_request');
Route::post('/account/messages/reply_seen', 'Dashboard\HomeController@replySeen')->name('seen_message');
Route::post('/account/messages/get_contact', 'Dashboard\HomeController@getContact')->name('get_contact');

//Profile
Route::post('/profile', 'Dashboard\UpdateProfileController@update')->name('update_profile');
Route::get('/aadhaar_verification', 'Dashboard\HomeController@aadhaarVerification')->name('aadhaar_verification');


//Rent Routes
Route::get('/rent/product/{id}', 'RentController@showProductDetails')->name('product_details');
Route::get('/rent/category/{category_name}', 'RentController@subcategories')->name('rent_subcategories');
Route::get('/rent', 'RentController@index')->name('rent_categories');
Route::post('/rent/subcategory', 'RentController@subcategory_products')->name('get_subcategory_products');
Route::post('/rent/contact_owner', 'ProductsController@contactOwner')->name('contact_owner');
Route::post('/rent/get_unavailable_dates', 'RentController@getUnavailableDates')->name('get_unavailable_dates');
Route::post('/rent/check_availability', 'RentController@checkAvailability')->name('check_availability');
Route::post('/rent/check_request_placed', 'ProductsController@checkForPlacedRequest')->name('check_request_placed');
Route::get('/rent/check_location', 'RentController@checkSessionHasLocation')->name('check_location');
Route::get('/rent/get_location', 'RentController@getLocation')->name('get_location');
Route::get('/rent/category/{category_name}/get_count', 'RentController@getCountAndLocation')->name('get_location_and_count');

//Lend Routes
Route::post('/subcategories','RentController@sendSubcategories')->name('get_subcategories');
Route::get('/lend', function () {
    $data = [];
    $data['categories'] = DB::table('categories')->orderBy('name')->get();
    return view('lend.categories', $data);
})->name('lend_categories');
Route::get('/account/lend/{id}', 'Dashboard\LendController@showLendForm')->name('lend_form');
Route::post('/lend/submit', 'Dashboard\LendController@lend')->name('lend_form_processing');
Route::get('/test/transactions', function() {
    return \Illuminate\Support\Facades\Auth::user()->transactions();
});

//Admin Routes
Route::group(['middleware' => ['auth', 'admin']], function() {

    //Admin Index Page
    Route::get('/a', function () {
        return redirect()->route('users.index');
    })->name('admin.index');

    //Users Routes
    Route::get('/a/users/get-all', 'AdminController@getAllUsers')->name('users.get-all');
    Route::get('/a/users', 'AdminController@usersIndex')->name('users.index');
    Route::get('/a/users/{user}', 'AdminController@usersShow')->name('users.show');
    Route::put('/a/users/{user}', 'AdminController@usersUpdate')->name('users.update');
    Route::delete('/a/users/{user}', 'AdminController@usersDestroy')->name('users.destroy');
    Route::get('/a/users/{user}/notes', 'AdminController@notes')->name('notes.index');
    Route::post('/a/users/{user}/notes', 'AdminController@notesStore')->name('notes.store');
    Route::get('/a/users/{user}/inventory', 'AdminController@loadInventory')->name('users.get-inventory');

    //Products, New Posts Routes
    Route::get('/a/products/all', 'AdminController@productsAll')->name('admin.products.all');
    Route::get('/a/products/show-all', 'AdminController@productsGetAll')->name('admin.products.show-all');
    Route::get('/a/products/new', 'AdminController@productsNew')->name('admin.products.new');
    Route::get('/a/products/get-new', 'AdminController@productsGetNew')->name('admin.products.get-new');
    Route::get('/a/products/{user}/edit', 'AdminController@productsEdit')->name('admin.products.edit');
    Route::put('/a/products/{product}', 'AdminController@productsUpdate')->name('admin.products.update');
    Route::delete('/a/products/{product}', 'AdminController@productsDestroy')->name('admin.products.destroy');
    Route::post('/a/products/{product}/update-image', 'AdminController@updateDefaultImage')->name('admin.products.update-image');
    Route::post('/a/products/{product}/remove-image', 'AdminController@removeProductImage')->name('admin.products.remove-image');
    Route::post('/a/products/{product}/update-state', 'AdminController@changeProductState')->name('admin.products.update-state');

    //Category Routes
    Route::get('/a/categories','AdminController@categoriesIndex')->name('categories.index');
    Route::get('/a/categories/show-all','AdminController@getAllCategories')->name('categories.show-all');
    Route::post('/a/categories/{id}','AdminController@categoriesUpdate')->name('categories.update');
    Route::get('/a/categories/{id}/change-availability/{value}','AdminController@categoriesUpdateAvailability')->name('categories.update-availability');
    Route::post('/a/categories','AdminController@categoriesStore')->name('categories.store');

    //Subcategory Routes
    Route::get('/a/subcategories','AdminController@subcategoriesIndex')->name('subcategories.index');
    Route::get('/a/subcategories/show-all','AdminController@getAllsubcategories')->name('subcategories.show-all');
    Route::post('/a/subcategories/{id}','AdminController@subcategoriesUpdate')->name('subcategories.update');
    Route::post('/a/subcategories','AdminController@subcategoriesStore')->name('subcategories.store');

    //Bulk Upload Routes
    Route::get('/a/products/bulk/{type}', 'ProductsController@productsBulk')->name('admin.products.bulk');
    Route::post('/a/products/bulk/{type}', 'ProductsController@productsUpload')->name('admin.products.upload');
});

//Vendor Routes
Route::group(['middleware' => ['auth', 'vendor']], function() {

    //Vendor Index Page
    Route::get('/vendor', function () {
        return redirect()->route('vendor.new-orders');
    })->name('vendor.index');

    //Bulk Upload Routes
    Route::get('/vendor/products/bulk/{type}', 'ProductsController@productsBulk')->name('vendor.products.bulk');
    Route::post('/vendor/products/bulk/{type}', 'ProductsController@productsUpload')->name('vendor.products.upload');

    //New Orders Routes
    Route::get('/vendor/products/new-orders', 'VendorController@newOrders')->name('vendor.new-orders');
    Route::get('/vendor/products/get-new-orders', 'VendorController@getNewOrders')->name('vendor.get-new-orders');

    //Product/Inventory Routes
    Route::get('/vendor/products/{user}/edit', 'AdminController@productsEdit')->name('vendor.products.edit');
    Route::put('/vendor/products/{product}', 'AdminController@productsUpdate')->name('vendor.products.update');
    Route::delete('/vendor/products/{product}', 'AdminController@productsDestroy')->name('vendor.products.destroy');
    Route::post('/vendor/products/{product}/update-image', 'AdminController@updateDefaultImage')->name('vendor.products.update-image');
    Route::post('/vendor/products/{product}/remove-image', 'AdminController@removeProductImage')->name('vendor.products.remove-image');
    Route::post('/vendor/products/{product}/update-state', 'AdminController@changeProductState')->name('vendor.products.update-state');
    Route::get('/vendor/inventory', 'VendorController@inventory')->name('vendor.inventory');
    Route::get('/vendor/inventory/show-all', 'VendorController@loadInventory')->name('vendor.load-inventory');

    //Calendar Routes
    Route::get('/vendor/calendar', 'EventController@index')->name('vendor.calendar');
    Route::get('/vendor/calendar/show-all', 'EventController@events')->name('vendor.calendar.get-all');
    Route::post('/vendor/events/insert', 'EventController@insertIntoCalendar')->name('vendor.calendar.insert');
    /*Route::get('/account/{tab?}', 'Dashboard\HomeController@vendorIndex')->name('vendor.inventory');*/
});