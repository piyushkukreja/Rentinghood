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

//Admin Routes
Route::group(['middleware' => ['auth', 'admin']], function() {
    Route::get('/a', 'AdminController@index')->name('admin');
    Route::get('/a/users/get-all', 'AdminController@getAllUsers')->name('users.get-all');
    Route::get('/a/users', 'AdminController@usersIndex')->name('users.index');
    Route::get('/a/users/{user}', 'AdminController@usersShow')->name('users.show');
    Route::put('/a/users/{user}', 'AdminController@usersUpdate')->name('users.update');
    Route::delete('/a/users/{user}', 'AdminController@usersDestroy')->name('users.destroy');
    Route::get('/a/users/{user}/notes', 'AdminController@notes')->name('notes.index');
    Route::post('/a/users/{user}/notes', 'AdminController@notesStore')->name('notes.store');
    Route::get('/a/users/{user}/inventory', 'AdminController@loadInventory')->name('users.get-inventory');
    Route::get('/a/products/new', 'AdminController@productsNew')->name('products.new');
    Route::get('/a/products/get-new', 'AdminController@productsGetNew')->name('products.get-new');
    Route::get('/a/products/{user}/edit', 'AdminController@productsEdit')->name('products.edit');
    Route::put('/a/products/{product}', 'AdminController@productsUpdate')->name('products.update');
    Route::delete('/a/products/{product}', 'AdminController@productsDestroy')->name('products.destroy');
    Route::post('/a/products/{product}/update-image', 'AdminController@updateDefaultImage')->name('products.update-image');
    Route::post('/a/products/{product}/remove-image', 'AdminController@removeProductImage')->name('products.remove-image');
    Route::post('/a/products/{product}/update-state', 'AdminController@changeProductState')->name('products.update-state');
    Route::get('/a/categories','AdminController@categoriesIndex')->name('categories.index');
    Route::get('/a/categories/show-all','AdminController@getAllCategories')->name('categories.show-all');
    Route::post('/a/categories/{id}','AdminController@categoriesUpdate')->name('categories.update');
    Route::post('/a/categories','AdminController@categoriesStore')->name('categories.store');
    Route::get('/a/subcategories','AdminController@subcategoriesIndex')->name('subcategories.index');
    Route::get('/a/subcategories/show-all','AdminController@getAllsubcategories')->name('subcategories.show-all');
    Route::post('/a/subcategories/{id}','AdminController@subcategoriesUpdate')->name('subcategories.update');
    Route::post('/a/subcategories','AdminController@subcategoriesStore')->name('subcategories.store');
    Route::get('/a/products/bulk', 'ProductsController@adminProductsBulk')->name('products.bulk');
    Route::post('/a/products/bulk', 'ProductsController@productsUpload')->name('products.upload');
});

//Vendor Routes
Route::group(['middleware' => ['auth', 'vendor']], function() {
    Route::get('/vendor', 'VendorController@index')->name('vendor');
    Route::get('/vendor/products/bulk', 'ProductsController@vendorProductsBulk')->name('products.bulk');
    Route::post('/vendor/products/bulk', 'ProductsController@productsUpload')->name('products.upload');
});