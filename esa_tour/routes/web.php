<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

// Auth::routes();

Route::middleware('auth')->group(function () {
    Route::namespace('App\Http\Controllers\Backend')->name('backend.')->group(function () {
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');
        Route::get('/get-total-invoice', 'DashboardController@total_invoice')->name('dashboard.total_invoice');

        Route::group(['prefix' => 'invoice'], function () {
            Route::get('/', 'InvoiceController@index')->name('invoice.index');
            Route::get('/data', 'InvoiceController@data')->name('invoice.data');
            Route::get('/create', 'InvoiceController@form')->name('invoice.create');
            Route::get('/edit/{id}', 'InvoiceController@form')->name('invoice.edit');
            Route::delete('/destroy/{id}', 'InvoiceController@destroy')->name('invoice.destroy');
            Route::post('/store', 'InvoiceController@store')->name('invoice.store');
            Route::get('/show/{id}', 'InvoiceController@show')->name('invoice.show');
            Route::post('/update-detail/{id}', 'InvoiceController@update_details')->name('invoice.update_details');
            Route::get('/get-city/{province_id}', 'InvoiceController@get_city')->name('invoice.get_city');
            Route::get('/get-district/{city_id}', 'InvoiceController@get_district')->name('invoice.get_district');
            Route::post('/customer-store', 'InvoiceController@customer_store')->name('invoice.customer_store');
        });

        Route::group(['prefix' => 'customer'], function () {
            Route::get('/', 'CustomerController@index')->name('customer.index');
            Route::get('/data', 'CustomerController@data')->name('customer.data');
            Route::get('/create', 'CustomerController@form')->name('customer.create');
            Route::post('/store', 'CustomerController@store')->name('customer.store');
            Route::get('/edit/{id}', 'CustomerController@form')->name('customer.edit');
            Route::delete('/destroy/{id}', 'CustomerController@destroy')->name('customer.destroy');

            Route::get('/get-city/{province_id}', 'CustomerController@get_city')->name('customer.get_city');
            Route::get('/get-district/{city_id}', 'CustomerController@get_district')->name('customer.get_district');
        });

        Route::group(['prefix' => 'product'], function () {
            Route::get('/', 'ProductController@index')->name('product.index');
            Route::get('/data', 'ProductController@data')->name('product.data');
            Route::get('/create', 'ProductController@form')->name('product.create');
            Route::post('/store', 'ProductController@store')->name('product.store');
            Route::get('/edit/{id}', 'ProductController@form')->name('product.edit');
            Route::delete('/destroy/{id}', 'ProductController@destroy')->name('product.destroy');
        });

        Route::group(['prefix' => 'bank'], function () {
            Route::get('/', 'BankController@index')->name('bank.index');
            Route::get('/data', 'BankController@data')->name('bank.data');
            Route::get('/create', 'BankController@form')->name('bank.create');
            Route::post('/store', 'BankController@store')->name('bank.store');
            Route::get('/edit/{id}', 'BankController@form')->name('bank.edit');
            Route::delete('/destroy/{id}', 'BankController@destroy')->name('bank.destroy');
            Route::get('/history/data', 'BankController@history_data')->name('bank.history_data');
            Route::get('/history/{id}', 'BankController@history')->name('bank.history');
            Route::post('/transfer', 'BankController@transfer')->name('bank.transfer');
        });

        Route::group(['prefix' => 'physical-invoice'], function () {
            Route::get('/', 'PhysicalInvoiceController@index')->name('physical_invoice.index');
            Route::get('/data', 'PhysicalInvoiceController@data')->name('physical_invoice.data');
            Route::get('/create', 'PhysicalInvoiceController@form')->name('physical_invoice.create');
            Route::post('/store', 'PhysicalInvoiceController@store')->name('physical_invoice.store');
            Route::get('/edit/{id}', 'PhysicalInvoiceController@form')->name('physical_invoice.edit');
            Route::delete('/destroy/{id}', 'PhysicalInvoiceController@destroy')->name('physical_invoice.destroy');
        });

        Route::group(['prefix' => 'credit-debit'], function () {
            Route::get('/', 'CreditDebitController@index')->name('credit_debit.index');
            Route::get('/data', 'CreditDebitController@data')->name('credit_debit.data');
            Route::get('/create', 'CreditDebitController@form')->name('credit_debit.create');
            Route::post('/store', 'CreditDebitController@store')->name('credit_debit.store');
            Route::get('/edit/{id}', 'CreditDebitController@form')->name('credit_debit.edit');
            Route::delete('/destroy/{id}', 'CreditDebitController@destroy')->name('credit_debit.destroy');
        });

        Route::group(['prefix' => 'asset'], function () {
            Route::get('/', 'AssetController@index')->name('asset.index');
            Route::get('/data', 'AssetController@data')->name('asset.data');
            Route::get('/create', 'AssetController@form')->name('asset.create');
            Route::post('/store', 'AssetController@store')->name('asset.store');
            Route::get('/edit/{id}', 'AssetController@form')->name('asset.edit');
            Route::delete('/destroy/{id}', 'AssetController@destroy')->name('asset.destroy');
        });

        Route::group(['prefix' => 'expense'], function () {
            Route::get('/', 'ExpenseController@index')->name('expense.index');
            Route::get('/data', 'ExpenseController@data')->name('expense.data');
            Route::get('/create', 'ExpenseController@form')->name('expense.create');
            Route::post('/store', 'ExpenseController@store')->name('expense.store');
            Route::get('/edit/{id}', 'ExpenseController@form')->name('expense.edit');
            Route::delete('/destroy/{id}', 'ExpenseController@destroy')->name('expense.destroy');
            Route::post('/category-store', 'ExpenseController@category_store')->name('expense.category_store');
            Route::delete('/destroy/{id}', 'ExpenseController@destroy')->name('expense.destroy');
        });

        Route::group(['prefix' => 'deposit'], function () {
            Route::get('/', 'DepositController@index')->name('deposit.index');
            Route::get('/data', 'DepositController@data')->name('deposit.data');
            Route::get('/create', 'DepositController@form')->name('deposit.create');
            Route::post('/store', 'DepositController@store')->name('deposit.store');
            Route::get('/edit/{id}', 'DepositController@form')->name('deposit.edit');
            Route::get('/history/data', 'DepositController@history_data')->name('deposit.history_data');
            Route::get('/history/{id}', 'DepositController@history')->name('deposit.history');
            Route::delete('/destroy/{id}', 'DepositController@destroy')->name('deposit.destroy');
        });

        Route::group(['prefix' => 'tax'], function () {
            Route::get('/', 'TaxController@index')->name('tax.index');
            Route::get('/data', 'TaxController@data')->name('tax.data');
            Route::get('/create', 'TaxController@form')->name('tax.create');
            Route::post('/store', 'TaxController@store')->name('tax.store');
            Route::get('/edit/{id}', 'TaxController@form')->name('tax.edit');
            Route::delete('/destroy/{id}', 'TaxController@destroy')->name('tax.destroy');
        });

        Route::group(['prefix' => 'scale'], function () {
            Route::get('/', 'ScaleController@index')->name('scale.index');
            Route::post('/', 'ScaleController@index')->name('scale.search');
        });

        Route::group(['prefix' => 'sale'], function () {
            Route::get('/', 'SaleController@index')->name('sale.index');
            Route::post('/', 'SaleController@index')->name('sale.search');
            Route::get('/export', 'SaleController@export')->name('sale.export');
        });

        Route::group(['prefix' => 'profit-loss'], function () {
            Route::get('/', 'ProfitLossController@index')->name('profit_loss.index');
            Route::post('/', 'ProfitLossController@index')->name('profit_loss.search');
            Route::get('/export', 'ProfitLossController@export')->name('profit_loss.export');
        });

        Route::group(['prefix' => 'category-note'], function () {
            Route::get('/', 'CategoryNoteController@index')->name('category_note.index');
            Route::get('/data', 'CategoryNoteController@data')->name('category_note.data');
            Route::get('/create', 'CategoryNoteController@form')->name('category_note.create');
            Route::post('/store', 'CategoryNoteController@store')->name('category_note.store');
            Route::get('/edit/{id}', 'CategoryNoteController@form')->name('category_note.edit');
            Route::delete('/destroy/{id}', 'CategoryNoteController@destroy')->name('category_note.destroy');
        });
    });

});

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

require __DIR__.'/auth.php';
