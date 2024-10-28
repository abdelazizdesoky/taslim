<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Auth\EmployeeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

    Route::get('/', function () {
        return view('Dashboard.User.auth.signin');
    })->middleware('guest');


    Route::middleware(['auth:admin'])->group(function () {
      Route::get('/Dashboard/admin', function () {
          return view('Dashboard.Admin.dashboard');
      })->middleware('permission:1')->name('Dashboard.admin');
  
      Route::get('/Dashboard/user', function () {
          return view('Dashboard.User.dashboard');
      })->middleware('permission:2')->name('Dashboard.user');
  
      Route::get('/Dashboard/employee', function () {
          return view('Dashboard.Employees.dashboard');
      })->middleware('permission:3,4')->name('Dashboard.employee');
  });
  
  Route::post('login/admin', [AdminController::class, 'store'])
      ->name('login.admin');





  //############################## excel####################################

Route::get('import', [ImportController::class, 'showForm']);
Route::post('import', [ImportController::class, 'import'])->name('import.excel');

// user---------------------------------------------

Route::middleware('auth')->group(function () {




 
});


