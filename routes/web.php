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

/* Admin Routes
 *
 * sub-domain -> admin
 * prefix     -> a
 *
 */

$group_parameters = array();
$group_parameters['middleware'] = ['auth', 'admin'];
if (!\Illuminate\Support\Facades\App::environment('local'))
    $group_parameters['domain'] = 'admin.rentinghood.com';
else
    $group_parameters['prefix'] = 'a';

Route::group($group_parameters, function () {

    //Admin Index Page
    Route::get('/', function () {
        return redirect()->route('users.index');
    })->name('admin.index');

    //Users Routes
    Route::get('/users/get-all', 'AdminController@getAllUsers')->name('users.get-all');
    Route::get('/users', 'AdminController@usersIndex')->name('users.index');
    Route::get('/users/{user}', 'AdminController@usersShow')->name('users.show');
    Route::put('/users/{user}', 'AdminController@usersUpdate')->name('users.update');
    Route::delete('/users/{user}', 'AdminController@usersDestroy')->name('users.destroy');
    Route::get('/users/{user}/notes', 'AdminController@notes')->name('notes.index');
    Route::post('/users/{user}/notes', 'AdminController@notesStore')->name('notes.store');
    Route::get('/users/{user}/inventory', 'AdminController@loadInventory')->name('users.get-inventory');

    //Products, New Posts Routes
    Route::get('/products/all', 'AdminController@productsAll')->name('admin.products.all');
    Route::get('/products/show-all', 'AdminController@productsGetAll')->name('admin.products.show-all');
    Route::get('/products/new', 'AdminController@productsNew')->name('admin.products.new');
    Route::get('/products/get-new', 'AdminController@productsGetNew')->name('admin.products.get-new');
    Route::get('/products/{product}/edit/{type}', 'AdminController@productsEdit')->name('admin.products.edit');
    Route::put('/products/{product}', 'AdminController@productsUpdate')->name('admin.products.update');
    Route::delete('/products/{product}', 'AdminController@productsDestroy')->name('admin.products.destroy');
    Route::post('/products/{product}/update-image', 'AdminController@updateDefaultImage')->name('admin.products.update-image');
    Route::post('/products/{product}/remove-image', 'AdminController@removeProductImage')->name('admin.products.remove-image');
    Route::post('/products/{product}/update-state', 'AdminController@changeProductState')->name('admin.products.update-state');

    //Category Routes
    Route::get('/categories', 'AdminController@categoriesIndex')->name('categories.index');
    Route::get('/categories/show-all', 'AdminController@getAllCategories')->name('categories.show-all');
    Route::post('/categories/{id}', 'AdminController@categoriesUpdate')->name('categories.update');
    Route::get('/categories/{id}/get-subcategories', 'AdminController@getSubcategories')->name('categories.subcategories');
    Route::get('/categories/{id}/change-availability/{value}', 'AdminController@categoriesUpdateAvailability')->name('categories.update-availability');
    Route::post('/categories', 'AdminController@categoriesStore')->name('categories.store');

    //Subcategory Routes
    Route::get('/subcategories', 'AdminController@subcategoriesIndex')->name('subcategories.index');
    Route::get('/subcategories/show-all', 'AdminController@getAllsubcategories')->name('subcategories.show-all');
    Route::post('/subcategories/{id}', 'AdminController@subcategoriesUpdate')->name('subcategories.update');
    Route::post('/subcategories', 'AdminController@subcategoriesStore')->name('subcategories.store');

    //Bulk Upload Routes
    Route::get('/products/bulk/{type}', 'ProductsController@productsBulk')->name('admin.products.bulk');
    Route::post('/products/bulk/{type}', 'ProductsController@productsUpload')->name('admin.products.upload');

});

/* Vendor Routes
 *
 * sub-domain -> vendor
 * prefix     -> vendor
 *
 */

//Logic for subdomains ->
$group_parameters = array();
$group_parameters['middleware'] = ['auth', 'vendor'];
if (!\Illuminate\Support\Facades\App::environment('local'))
    $group_parameters['domain'] = 'vendor.rentinghood.com';
else
    $group_parameters['prefix'] = 'vendor';

Route::get('/checkbox', 'VendorController@viewCheckbox')->name('vendor.checkbox');

Route::group($group_parameters, function () {

    //Vendor Index Page
    Route::get('/', function () {
        return redirect()->route('vendor.new-orders');
    })->name('vendor.index');

    //Bulk Upload Routes
    Route::get('/products/bulk/{type}', 'ProductsController@productsBulk')->name('vendor.products.bulk');
    Route::post('/products/bulk/{type}', 'ProductsController@productsUpload')->name('vendor.products.upload');

    //New Orders Routes
    Route::get('/products/new-orders', 'VendorController@newOrders')->name('vendor.new-orders');
    Route::get('/products/get-new-orders', 'VendorController@getNewOrders')->name('vendor.get-new-orders');
    Route::post('/products/answer-request', 'Dashboard\HomeController@answerRequest')->name('vendor.answer-request');
    Route::post('/products/update-availability', 'Dashboard\HomeController@updateAvailability')->name('vendor.update-availability');

    //Product/Inventory Routes
    Route::get('/products/{product}/edit/{type}', 'AdminController@productsEdit')->name('vendor.products.edit');
    Route::put('/products/{product}', 'AdminController@productsUpdate')->name('vendor.products.update');
    Route::delete('/products/{product}', 'AdminController@productsDestroy')->name('vendor.products.destroy');
    Route::post('/products/{product}/update-image', 'AdminController@updateDefaultImage')->name('vendor.products.update-image');
    Route::post('/products/{product}/remove-image', 'AdminController@removeProductImage')->name('vendor.products.remove-image');
    Route::post('/products/{product}/update-state', 'AdminController@changeProductState')->name('vendor.products.update-state');
    Route::get('/inventory', 'VendorController@inventory')->name('vendor.inventory');
    Route::get('/inventory/show-all', 'VendorController@loadInventory')->name('vendor.load-inventory');
    Route::get('/categories/{id}/get-subcategories', 'AdminController@getSubcategories')->name('vendor.subcategories');

    //Calendar Routes
    Route::get('/calendar', 'EventController@index')->name('vendor.calendar');
    Route::get('/calendar/show-all', 'EventController@eventsShowAll')->name('vendor.calendar.get-all');
    Route::post('/events/insert', 'EventController@insertIntoCalendar')->name('vendor.calendar.insert');
    Route::post('/events/store', 'EventController@store')->name('vendor.calendar.store');
    Route::put('/events/{event}', 'EventController@update')->name('vendor.calendar.update');
    Route::delete('/events/{event}', 'EventController@destroy')->name('vendor.calendar.delete');

});

/*
 * Public Website
 */

$group_parameters = array();
Route::group($group_parameters, function () {
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

    //Social Login Routes
    Route::get('auth/{provider}', 'Auth\LoginController@redirectToProvider')->name('social-login');
    Route::get('auth/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('social-login-callback');

    //Register Routes
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register');
    Route::post('/pin_code', 'RentController@sendPinCodes')->name('get_pin_codes');

    //Register Routes
    Route::get('/register/mobile_verfication', 'Auth\MobileVerificationController@showOTPForm')->name('otp.form');
    Route::post('/register/send_OTP', 'Auth\MobileVerificationController@sendOTP')->name('otp.send');
    Route::post('/register/verify_OTP', 'Auth\MobileVerificationController@verifyOTP')->name('otp.verify');

    //Account Routes
    Route::get('/account/get_notification', 'Dashboard\HomeController@getNotification')->name('get_notification');
    Route::post('/account/reply_notification', 'Dashboard\HomeController@replyNotification')->name('reply_notification');
    Route::get('/account/{tab?}', 'Dashboard\HomeController@index')->name('account');

    //Rent Routes
    Route::get('/rent/product/{id}', 'RentController@showProductDetails')->name('product_details');
    Route::get('/rent/category/{category_name}', 'RentController@subcategories')->name('rent_subcategories');
    Route::get('/rent', 'RentController@index')->name('rent_categories');
    Route::post('/rent/subcategory', 'RentController@subcategory_products')->name('get_subcategory_products');
    Route::post('/rent/get_unavailable_dates', 'RentController@getUnavailableDates')->name('get_unavailable_dates');
    Route::post('/rent/check_availability', 'RentController@checkAvailability')->name('check_availability');
    Route::get('/rent/check_location', 'RentController@checkSessionHasLocation')->name('check_location');
    Route::get('/rent/get_location', 'RentController@getLocation')->name('get_location');
    Route::get('/rent/category/{category_name}/get_count', 'RentController@getCountAndLocation')->name('get_location_and_count');

    //Lend Routes
    Route::get('/lend', 'Dashboard\LendController@index')->name('lend_categories');

    Route::group(['middleware' => ['auth', 'mobile_auth', 'verified']], function () {

        //Profile
        Route::post('/profile', 'Dashboard\UpdateProfileController@update')->name('update_profile');
        Route::get('/aadhaar_verification', 'Dashboard\HomeController@aadhaarVerification')->name('aadhaar_verification');

        //Lend Routes
        Route::post('/subcategories', 'RentController@sendSubcategories')->name('get_subcategories');
        Route::get('/account/lend/{id}', 'Dashboard\LendController@showLendForm')->name('lend_form');
        Route::post('/lend/submit', 'Dashboard\LendController@lend')->name('lend_form_processing');

        //Recover Account Routes
        Route::get('/recover', 'Auth\RecoverAccountController@index')->name('recover_form');
        Route::post('/recover', 'Auth\RecoverAccountController@recover')->name('recover');
        Route::post('/recover/otp', 'Auth\RecoverAccountController@verifyOTP')->name('recover_verify_OTP');
        Route::post('/recover/reset_password', 'Auth\RecoverAccountController@resetPassword')->name('reset_password');

        Route::post('/rent/contact_owner', 'ProductsController@contactOwner')->name('contact_owner');
    });

    Route::group(['middleware' => ['auth']], function() {

        //Email Verification Routes
        Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
        Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
        Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

        //Messages Routes
        Route::get('/account/messages/get_messages', 'Dashboard\HomeController@getMessages')->name('get_messages');
        Route::get('/account/messages/get_message_count', 'Dashboard\HomeController@getMessageCount')->name('get_message_count');
        Route::post('/account/messages/answer_request', 'Dashboard\HomeController@answerRequest')->name('answer_request');
        Route::post('/account/messages/reply_seen', 'Dashboard\HomeController@replySeen')->name('seen_message');
        Route::post('/account/messages/get_contact', 'Dashboard\HomeController@getContact')->name('get_contact');

        //Inventory Routes
        Route::get('/account/inventory/products', 'Dashboard\HomeController@getInventory')->name('get_inventory');
        Route::post('/account/inventory/update_availability', 'Dashboard\HomeController@updateAvailability')->name('update_availability');
        Route::get('/account/inventory/{id}', 'Dashboard\LendController@getProductDetails')->name('inventory_product_details');
        Route::post('/account/inventory/edit_product', 'Dashboard\LendController@editPost')->name('edit_product');

        Route::post('/rent/check_request_placed', 'ProductsController@checkForPlacedRequest')->name('check_request_placed');
    });
});