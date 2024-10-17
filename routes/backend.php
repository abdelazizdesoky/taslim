<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Dashboard\BrandController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\User\UserInvoicesController;
use App\Http\Controllers\Dashboard\EmployeeController;
use App\Http\Controllers\Dashboard\InvoicesController;
use App\Http\Controllers\Dashboard\LocationController;
use App\Http\Controllers\Dashboard\SupplierController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Dashboard\CustomersController;
use App\Http\Controllers\Dashboard\ProductCodeController;
use App\Http\Controllers\Dashboard\ProductTypeController;
use App\Http\Controllers\Dashboard\SerialNumberController;
use App\Http\Controllers\Dashboard\ProductDetailController;
use App\Http\Controllers\Employee\EmployeeInvoiceController;

/*
|--------------------------------------------------------------------------
| backend Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

        Route::middleware(['auth:admin'])->group(function () {

       Route::prefix('admin')->group(function () {

            //############################# supplier route ##########################################
        
               Route::resource('supplier', SupplierController::class);
        
            
            //############################# customers route ##########################################
        
            Route::resource('customers', CustomersController::class);
            
          
            
            //############################# Invoices route ##########################################
        
            Route::resource('Invoices', InvoicesController::class);

            // Routes for location CRUD
            Route::resource('Location', LocationController::class);

            Route::delete('cancel', [InvoicesController::class, 'cancel'])->name('Invoices.cancel');
            
            //############################# employee route ##########################################
        
            Route::resource('employee', EmployeeController::class);
        
            Route::post('update_password', [EmployeeController::class, 'update_password'])->name('update_password');
            Route::post('update_status', [EmployeeController::class, 'update_status'])->name('update_status');


          //############################# user route ##########################################
      
          Route::get('users', [RegisteredUserController::class, 'index'])->name('user.index');


          Route::get ('user/edit/{id}', [RegisteredUserController::class, 'edit'])->name('user.edit');
          Route::put('user/update/{id}', [RegisteredUserController::class, 'update'])->name('user.update');
          

          Route::get('user/create', [RegisteredUserController::class, 'create'])->name('user.create');
          Route::post('user/store', [RegisteredUserController::class, 'store'])->name('user.store');


          Route::delete('user/delete/{id}', [RegisteredUserController::class, 'destroy'])->name('user.destroy');

          Route::post('update_password', [RegisteredUserController::class, 'update_password'])->name('update_password');


         //############################# admin route ##########################################


         Route::get ('admin/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit');
         Route::put('admin/update/{id}', [AdminController::class, 'update'])->name('admin.update');
         

         Route::delete('admin/delete/{id}', [AdminController::class, 'delete'])->name('admin.delete');

         Route::post('admin_password', [AdminController::class, 'update_password'])->name('admin_password');
            
             //############################# product route ##########################################
        
                                // Routes for Brand CRUD
                    Route::resource('brands', BrandController::class);

                    // Routes for Product Type CRUD
                    Route::resource('product-types', ProductTypeController::class);

                    // Routes for Product CRUD
                    Route::resource('products', ProductController::class);

                    // Routes for Product Detail CRUD
                    Route::resource('product-details', ProductDetailController::class);

                    // Routes for Product Code CRUD
                    Route::resource('product-codes', ProductCodeController::class);
        
     //############################# SerialNumber route ##########################################           

    
        Route::get ('serial', [SerialNumberController::class, 'index'])->name('serial.index');

        Route::post('/serial/invoices/search', [SerialNumberController::class, 'searchInvoices'])->name('serial.invoices.search');
 


          //####################data MS SQL##################################33
         Route::get('/migrate-data', [DataController::class, 'migrateData']);


                    
        
           });  
        });







             //############################## Dashboard employee ##################################

            Route::middleware(['auth:employee'])->group(function () {

               Route::prefix('employee')->group(function () {

           //############################## invoise ##################################

            Route::resource('employeeinvoice', EmployeeInvoiceController::class);

            Route::get('compinvoice', [EmployeeInvoiceController::class, 'Compinvoice'])->name('Compinvoice');

            Route::put('/employeeinvoice/{id}', [EmployeeInvoiceController::class, 'show']);



            });
            });

            
             //############################## Dashboard user ##################################

             Route::middleware(['auth'])->group(function () {

             Route::prefix('user')->group(function () {

              //############################## invoise ##################################
           
                   Route::resource('UserInvoices', UserInvoicesController::class);
   
       
                   Route::delete('cancel', [UserInvoicesController::class, 'cancel'])->name('UserInvoices.cancel');
   
        
              });
              });

          //################################################################

        require __DIR__.'/auth.php';
