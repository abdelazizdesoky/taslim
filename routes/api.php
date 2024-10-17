<?php

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\InvoiceCollection;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Employee\Api\EmployeeInvoiceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//######################################login employee ##################################################

Route::post('employee/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('employee/logout', [AuthController::class, 'logout']);


//########################################################################################

Route::middleware('auth:sanctum')->group(function () {

    Route::get('employee/invoices', [EmployeeInvoiceController::class, 'index']);
    Route::get('employee/invoices/{id}', [EmployeeInvoiceController::class, 'show']);
    Route::get('employee/completed-invoices', [EmployeeInvoiceController::class, 'Compinvoice']);
    Route::post('employee/invoices/serials', [EmployeeInvoiceController::class, 'store']);
    
  
});


