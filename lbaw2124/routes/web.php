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
// Home
Route::get('/', 'Auth\LoginController@home');


Route::get('personalinfo', 'ProfileController@showPI')->name('personalinfo');
Route::get('addresses', 'ProfileController@listAddresses');
Route::get('wishlist', 'ProfileController@showWishList');
Route::get('notifications', 'ProfileController@showNotifications');
Route::get('reviews', 'ProfileController@showReviews');
Route::get('orders', 'OrderController@index')->name('orders');

Route::get('add_address', 'ProfileController@add_adress_form');
Route::post('add_address', 'ProfileController@addAddress');

Route::get('edit_address/{id}', 'ProfileController@edit_adress_form');
Route::put('edit_address/{id}', 'ProfileController@editAddress')->name('edit_address');


Route::put('personalinfo','ProfileController@update');
Route::get('delete_address/{id}', 'ProfileController@deleteAddress');


// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('/password/reset/{token}', 'Auth\ResetPasswordController@reset');
Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

//Products
Route::get('products', 'ProductController@index');
Route::get('products/search','ProductController@searchProducts');
Route::get('products/{id}','ProductController@show')->name('product.details');
Route::get('products/price/{id}', 'ProductController@getPrice');

Route::get('order/{id}', 'OrderController@show')->name('order.details');
Route::delete('delete_order/{id}', 'OrderController@cancelOrder');

//Shopping cart and checkout
Route::get('shoppingcart', 'ShoppingCartInfoController@index')->name('shoppingcart');
Route::delete('shoppingcart/{id}', 'ShoppingCartInfoController@destroy');
Route::post('shoppingcart/{id}', 'ShoppingCartInfoController@addToCart');
Route::put('shoppingcart/{id}', 'ShoppingCartInfoController@update');
Route::delete('shoppingcart', 'ShoppingCartInfoController@emptyCart');
Route::get('checkout', 'CheckoutController@index');
Route::post('checkout', 'CheckoutController@show')->name('show_checkout');
Route::put('checkout', 'CheckoutController@placeOrder')->name('checkout');

//Admin
Route::get('admin', 'AdminController@index');
Route::put('admin', 'AdminController@update')->name('update');
Route::get('dashboard', 'AdminController@showDashboard');

Route::get('users', 'AdminController@showUsers');
Route::get('users/search', 'AdminController@searchUser');
Route::delete('delete_account/{id}','ProfileController@delete_user');
Route::get('users/orders/{id}', 'AdminController@showUserOrders');
Route::get('history', 'AdminController@showHistory');

Route::get('all_orders', 'AdminController@showOrders');
Route::get('orders/search', 'AdminController@searchOrders');

Route::get('/user/{id}/edit', 'AdminController@viewUser')->name('view');
Route::put('/user/{id}/edit', 'AdminController@editUser')->name('edit');

Route::get('/order/{id}/edit_status', 'AdminController@editOrderForm');
Route::put('/order/{id}/edit_status', 'AdminController@editOrder');

Route::get('users/create', 'AdminController@createUserForm');
Route::post('users/create', 'AdminController@createUser')->name('create');

Route::put('users/{id}/block', 'AdminController@blockUser')->name('block');
Route::put('users/{id}/unblock', 'AdminController@unblockUser')->name('unblock');
Route::post('appeal_block', 'ProfileController@appeal_unblock');

Route::get('manage_products', 'AdminController@manageProducts');
Route::get('add_product', 'AdminController@addProductForm');
Route::post('add_product', 'AdminController@addProduct')->name('addproduct');
Route::get('/product/{id}/edit', 'AdminController@editProductForm')->name('editproductform');
Route::put('/product/{id}/edit', 'AdminController@editProduct')->name('editproduct');

Route::get('manage_categories', 'AdminController@manageCategories');
Route::post('add_category', 'AdminController@addCategory')->name('addcategory');
Route::post('remove_category', 'AdminController@removeCategory')->name('removecategory');


//WishList
Route::get('/wishlist', 'UserWishlistController@index')->name('wishlist');
Route::put('/api/wishlist/addProduct/{id}', 'UserWishlistController@store');
Route::delete('/api/wishlist/emptyWishlist', 'UserWishlistController@emptyWishlist');
Route::delete('/api/wishlist/removeProduct/{id}', 'UserWishlistController@destroy');


Route::delete('/review/{id}', 'ReviewController@destroy');
Route::post('/review/{product_id}', 'ReviewController@create');
Route::put('/review/{id}', 'ReviewController@edit');
Route::post('/api/review/report/{id}', 'ReviewController@report');


Route::get('/contacts', 'StaticPageController@showContacts');
Route::get('/aboutus', 'StaticPageController@showAboutUs');
Route::get('/faq', 'StaticPageController@showFAQ');
