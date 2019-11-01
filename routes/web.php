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

Route::get('/install', function(){
    if(App\Install::first() == null){
        Illuminate\Support\Facades\Artisan::call('migrate:fresh');
        Illuminate\Support\Facades\Artisan::call('db:seed');
        Illuminate\Support\Facades\Artisan::call('storage:link');
        $install = new App\Install;
        $install->installed = 1;
        $install->save();
        return redirect('/login');
    }else{
        return redirect('/login');
    }
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('home')->middleware('web');
Route::get('/home', 'HomeController@index')->name('home')->middleware('web');

Route::resource('/availability', 'RoomController')->middleware('web');

Route::resource('/check-ins', 'CheckInController')->middleware('web');

Route::resource('/expense-categories', 'ExpenseCategoryController')->middleware('web');

Route::resource('/expenses', 'ExpenseController')->middleware('web');

Route::post('/expenses/search', 'ExpenseController@search')->middleware('web');

Route::post('/check-ins/check-availability', 'CheckInController@checkAvailability')->middleware('web');

Route::get('/checkout/{id}', 'CheckInController@checkOut')->middleware('web');

Route::get('/bookings', 'CheckInController@index')->middleware('web');

Route::resource('/guests', 'GuestController')->middleware('web');

Route::resource('/planner', 'EventController')->middleware('web');

Route::resource('/room-types', 'PackagesController')->middleware('web');

Route::get('/room-service', 'RoomServiceController@index')->middleware('web');

Route::get('/room-service/{id}', 'RoomServiceController@show')->middleware('web');

Route::resource('/categories', 'CategoryController')->middleware('web');

Route::resource('/items', 'ItemController')->middleware('web');

Route::resource('/orders', 'OrderController')->middleware('web');

Route::resource('/utilities', 'UtilityController')->middleware('web');

Route::resource('/cart', 'CartController')->middleware('web');

Route::post('/order/invoice', 'InvoiceController@order')->middleware('web');

Route::post('/booking/invoice', 'InvoiceController@booking')->middleware('web');

Route::post('/restuarent/order/invoice', 'InvoiceController@restuarent')->middleware('web');

Route::get('/users', 'UsersController@index')->middleware('web');

Route::get('/users/create', 'UsersController@create')->middleware('web');

Route::post('/users', 'UsersController@store')->middleware('web');

Route::delete('/users/{id}', 'UsersController@destroy')->middleware('web');

Route::get('/users/change-password/{id}', 'UsersController@changePassword')->middleware('web');

Route::put('/users/{id}', 'UsersController@change')->middleware('web');

Route::get('/reports', 'ReportsController@index')->middleware('web');

Route::post('/reports/users', 'ReportsController@users')->name('reports.users')->middleware('web');

Route::post('/reports/bookings', 'ReportsController@bookings')->name('reports.bookings')->middleware('web');

Route::post('/reports/orders', 'ReportsController@orders')->name('reports.orders')->middleware('web');

Route::post('/reports/restuarent/orders', 'ReportsController@restuarent')->name('reports.restuarent')->middleware('web');

Route::post('/reports/room-revenue', 'ReportsController@roomRevenue')->middleware('web');

Route::post('/reports/expense', 'ReportsController@expense')->middleware('web');

Route::post('/reports/inventory', 'ReportsController@inventory')->middleware('web');

Route::post('/search', 'GuestController@search')->middleware('web');

Route::get('/guest-search', 'GuestController@guest_search')->middleware('web');

Route::get('/guest-details', 'GuestController@guest_details')->middleware('web');

Route::post('/restaurent/store', 'RestuarentController@store')->middleware('web');

Route::get('/restuarent/cart/{id}', 'RestuarentController@show')->middleware('web');

Route::put('/restuarent/update/{id}', 'RestuarentController@update')->middleware('web');

Route::delete('/restuarent/delete/{id}', 'RestuarentController@destroy')->middleware('web');

Route::post('/restuarent/order', 'RestuarentController@order')->middleware('web');

Route::get('/restaurants/orders', 'RestuarentController@list')->middleware('web');

Route::put('/restuarents/set-status/{id}', 'RestuarentController@setStatus')->middleware('web');

Route::get('/restuarent/orders/{id}/edit', 'RestuarentController@edit')->middleware('web');

Route::get('/settings', 'SettingsController@index')->middleware('web');

Route::post('/settings', 'SettingsController@create')->middleware('web');

Route::put('/settings/{id}', 'SettingsController@update')->middleware('web');

Route::get('/settings/{id}/edit', 'SettingsController@edit')->middleware('web');