<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix("auth")->group(function (){
    Route::post("login",[AuthController::class,"login"]);
    Route::post("register",[AuthController::class,"register"]);
});

Route::middleware(ApiAuthMiddleware::class)->group(function (){
    Route::prefix("invoices")->group(function (){
        Route::get("userInvoicesByDate",[InvoiceController::class,'getUserInvoicesByDate']);
    });

    Route::prefix("users")->group(function (){
        Route::get('userInvoices',[UserController::class,'getUserInvoices']);
    });


    //Only for admins
    Route::middleware("role:admin")->group(function (){
        Route::apiResource('users',UserController::class);
        Route::apiResource("products",ProductController::class);
        Route::apiResource("invoices",InvoiceController::class);
    });
});


// Route::prefix("invoices")->group(function (){
//     Route::get("users/{userId}/invoicesByDate",[InvoiceController::class,'getUserInvoicesByDate']);
//     Route::apiResource("/",InvoiceController::class);
// });




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
