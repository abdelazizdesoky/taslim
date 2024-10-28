<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;




Route::middleware('guest')->group(function () {

   
//---------login -------------------------

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');


           
});
//--------------------end middleware---------------------------------


//-----------------------------log out ------------------------------------------------------

Route::get('logout/admin', [AdminController::class, 'destroy'])->middleware('auth:admin')->name('logout.admin');




