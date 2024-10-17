<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ProfileController;

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


      //################################# User ###########################
      Route::get('/Dashboard/user', function () {
        return view('Dashboard.User.dashboard');
    })->middleware(['auth', 'verified'])->name('Dashboard.user');


    //############################### Admin #############################
    Route::get('/Dashboard/admin', function () {
        return view('Dashboard.Admin.dashboard');
    })->middleware(['auth:admin', 'verified'])->name('Dashboard.admin');


     //############################### employee #############################
    Route::get('/Dashboard/employee', function () {
      return view('Dashboard.Employees.dashboard');
  })->middleware(['auth:employee'])->name('Dashboard.employee');




  //############################## excel####################################

Route::get('import', [ImportController::class, 'showForm']);
Route::post('import', [ImportController::class, 'import'])->name('import.excel');

// user---------------------------------------------

Route::middleware('auth')->group(function () {




  //  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  //  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
   // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


