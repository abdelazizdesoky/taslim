<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;

use App\Http\Controllers\Dashboard\{
    BrandController,
    ProductController,
    InvoicesController,
    LocationController,
    SupplierController,
    CustomersController,
    ProductTypeController,
    SerialNumberController,
    AdminController,
    
};
use App\Http\Controllers\User\UserInvoicesController;
use App\Http\Controllers\Employee\EmployeeInvoiceController;


/*
|--------------------------------------------------------------------------
| Backend Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:admin'])->group(function () {
    
    /**
     * Admin Routes (Permission Level 1)
     */
    Route::middleware('permission:1')->prefix('admin')->name('admin.')->group(function () {
        // Supplier Management
        Route::resource('supplier', SupplierController::class);
        
        // Customer Management
        Route::resource('customers', CustomersController::class);
        
        // Invoice Management
        Route::resource('invoices', InvoicesController::class);
        Route::delete('invoices/cancel', [InvoicesController::class, 'cancel'])->name('invoices.cancel');
        
        // Location Management
        Route::resource('locations', LocationController::class);
        
        // // Employee Management
        // Route::resource('employees', EmployeeController::class);
        // Route::post('employees/update-password', [EmployeeController::class, 'update_password'])->name('employees.update-password');
        // Route::post('employees/update-status', [EmployeeController::class, 'update_status'])->name('employees.update-status');
        
        // User Management
        // Route::controller(RegisteredUserController::class)->prefix('users')->name('users.')->group(function () {
        //     Route::get('/', 'index')->name('index');
        //     Route::get('/create', 'create')->name('create');
        //     Route::post('/', 'store')->name('store');
        //     Route::get('/{id}/edit', 'edit')->name('edit');
        //     Route::put('/{id}', 'update')->name('update');
        //     Route::delete('/{id}', 'destroy')->name('destroy');
        //     Route::post('/update-password', 'update_password')->name('update-password');
        // });
        
        // user Management
        Route::controller(AdminController::class)->prefix('admins')->name('admins.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::post('/update-password', 'update_password')->name('update-password');
        });
        
        // Product Management
        Route::resource('brands', BrandController::class);
        Route::resource('product-types', ProductTypeController::class);
        Route::resource('products', ProductController::class);
        
        // Serial Number Management
        Route::controller(SerialNumberController::class)->prefix('serial')->name('serial.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/invoices/search', 'searchInvoices')->name('invoices.search');
        });
        
        // Data Migration
        Route::get('/migrate-data', [DataController::class, 'migrateData'])->name('migrate-data');
    });
    
   
    /**
     * User Routes (Permission Level 2)
     */
    Route::middleware('permission:2')->name('user.')->group(function () {
        Route::resource('invoices', UserInvoicesController::class)->names([
            'index' => 'invoices.index',
            'create' => 'invoices.create',
            'store' => 'invoices.store',
            'show' => 'invoices.show',
            'edit' => 'invoices.edit',
            'update' => 'invoices.update',
            'destroy' => 'invoices.destroy',
        ]);
        Route::delete('invoices/cancel', [UserInvoicesController::class, 'cancel'])->name('invoices.cancel');
    });


     /**
     * Employee Routes (Permission Level 3)
     */
    Route::middleware('permission:3')->prefix('employee')->name('employee.')->group(function () {
      Route::resource('invoices', EmployeeInvoiceController::class);
      Route::get('completed-invoices', [EmployeeInvoiceController::class, 'Compinvoice'])->name('invoices.completed');
  });
  

  
});

require __DIR__.'/auth.php';