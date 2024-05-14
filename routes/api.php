<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Menu Controller
Route::resource('/menu', MenuController::class)->middleware(['auth:sanctum']);
//Authentication
Route::post('/login', [AuthenticationController::class, 'login']);
Route::get('/logout', [AuthenticationController::class, 'logout'])->middleware(['auth:sanctum']);
// Route::get('/userLogin', [AuthenticationController::class, 'userLogin'])->middleware(['auth:sanctum']);
Route::post('/signUp', [AuthenticationController::class, 'addToUser']);

//add to cart
Route::post('/addToCart', [CartController::class, 'store'])->middleware(['auth:sanctum']);
//delete cart
Route::delete('/cart/{cart}', [CartController::class, 'removeFromCart'])->middleware(['auth:sanctum']);
//add to order
Route::post('/addToOrder', [OrderController::class, 'addOrder'])->middleware(['auth:sanctum']);
//payment gateway
Route::post('/create-transaction', [MidtransController::class, 'createTransaction'])->middleware(['auth:sanctum']);
