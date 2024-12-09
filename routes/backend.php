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
    ActivityLogController,
    
};
use App\Http\Controllers\User\UserInvoicesController;
use App\Http\Controllers\Employee\EmployeeInvoiceController;
use App\Http\Controllers\Viewer\ViewerInvoicesController;

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
        Route::post('invoices/cancel', [InvoicesController::class, 'cancel'])->name('invoices.cancel');
        Route::post('invoices/cancelserial', [InvoicesController::class, 'cancelserial'])->name('invoices.cancelserial');

        // Location Management
        Route::resource('locations', LocationController::class);
       
        // logs Management
        Route::get('admin/activity-logs', [ActivityLogController::class, 'index'])->name('activityLogs.index');
        Route::get('/logs/{id}', [ActivityLogController::class, 'show'])->name('logs.show');

        
        // user Management
        Route::controller(AdminController::class)->prefix('admins')->name('admins.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'delete')->name('delete');
            Route::post('/update-password', 'update_password')->name('update-password');
        });
        
        // Product Management
        Route::resource('brands', BrandController::class);
        Route::resource('product-types', ProductTypeController::class);
        Route::resource('products', ProductController::class);
        
        // Serial Number Management
        Route::get('/',[SerialNumberController::class, 'index'])->name('serial.index');
        Route::post('/invoices/search',[SerialNumberController::class, 'searchInvoices'])->name('invoices.search');

       
        
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
        Route::post('invoices/cancel', [UserInvoicesController::class, 'cancel'])->name('invoices.cancel');
    });


     /**
     * Deliver Routes (Permission Level 3)
     */
    Route::middleware('permission:3,4')->prefix('employee')->name('employee.')->group(function () {

      Route::controller(EmployeeInvoiceController::class)->name('invoices.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('show/{id}', 'show')->name('show');
        Route::get('update/{id}', 'update')->name('update');
        Route::post('/', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');

        Route::get('completed-invoices', 'Compinvoice')->name('completed');
        Route::get('/check-serial/{invoice}/{serial}', 'checkSerial');


    });

    
     
  });


    
    /**
     * viewer Routes (Permission Level 5)
     */
    Route::middleware('permission:5')->prefix('viewer')->name('viewer.')->group(function () {

        Route::controller(ViewerInvoicesController::class)->name('invoices.')->group(function () {

            Route::get('index', 'index')->name('index');
            Route::get('show/{id}', 'show')->name('show');
            Route::get('/', 'create')->name('create');
            Route::post('/invoices/search', 'searchInvoices')->name('search');
            Route::get('prodact/view', 'viewProduct')->name('prodact');

    });

    
 
       
    });

  
  

  
});

require __DIR__.'/auth.php';