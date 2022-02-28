<?php

use Illuminate\Support\Facades\Route;

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


Auth::routes();

Route::group(['middleware' => 'auth'], function () {



    Route::get('/', function () {
        return view('welcome');
    });
    Route::get('/logout', 'Auth\LoginController@logout');
    Route::get('/mail', 'InvoiceController@mail');

    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('/search', 'CustomerController@search')->name('autocomplete');


    Route::prefix('api')->group(function () {

        Route::get('/search/customer', 'CustomerController@search');
        Route::get('/search/invoices/{customer}', 'invoiceController@search');
        Route::get('/invoices/all/{id}', 'CustomerController@AllInvoice');
        Route::get('/ledger/{id}', 'LedgerController@show');

        Route::get('/invoice-mail/{id}', 'InvoiceController@sendInvoice');

        Route::resource('invoice/amount', 'InvoiceAmountsController');
        Route::resource('/customer', 'CustomerController');
        Route::resource('/receive/invoice', 'ReceiveController');
        Route::resource('/dashboard', 'dashboardController');
        Route::resource('/invoices', 'invoiceController');
        Route::resource('/company', 'CompanyController');




    });
});
