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

Route::post('/updateMenu/{id}', [MenuController::class, "updateMenu"]);
// Route::get('/category', MenuController::class, 'getCategory');
//Authentication
//loginUser
Route::post('/login', [AuthenticationController::class, 'login']);
//loginAdmin
Route::post('/loginAdmin', [AuthenticationController::class, 'loginAdmin']);
Route::get('/logout', [AuthenticationController::class, 'logout'])->middleware(['auth:sanctum']);
// Route::get('/userLogin', [AuthenticationController::class, 'userLogin'])->middleware(['auth:sanctum']);
Route::post('/signUp', [AuthenticationController::class, 'addToUser']);
Route::put('/saveProfile/{id}', [AuthenticationController::class, 'updateProfile'])->middleware(['auth:sanctum']);

//add to cart
Route::post('/addToCart', [CartController::class, 'store'])->middleware(['auth:sanctum']);
//delete cart
Route::delete('/cart/{cart}', [CartController::class, 'removeFromCart'])->middleware(['auth:sanctum']);
//get item cart
Route::get('/getItemCart', [CartController::class, 'getItemFromCart'])->middleware(['auth:sanctum']);
//add to order
Route::post('/addToOrder', [OrderController::class, 'addOrder'])->middleware(['auth:sanctum']);
Route::get('/getOrderList', [OrderController::class, 'getOrder'])->middleware(['auth:sanctum']);
//payment gateway
Route::post('/create-transaction', [MidtransController::class, 'createTransaction'])->middleware(['auth:sanctum']);
//update status
Route::put('/payment/{id}/{status}', [OrderController::class, 'updatePayment']);
//increment cart
Route::put('incrementCart/{id}', [CartController::class, 'incrementItem'])->middleware(['auth:sanctum']);
//decrement cart
Route::put('decrementCart/{id}', [CartController::class, 'decrementItem'])->middleware(['auth:sanctum']);
//top 3 items
Route::get('/topThreeMenu', [MenuController::class, 'topThreeMenu']);