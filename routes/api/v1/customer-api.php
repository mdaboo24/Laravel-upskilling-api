<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\CustomerController;
use App\Http\Controllers\Api\v1\JWTController;

Route::post('login', [JWTController::class, 'authenticate']);
Route::post('register', [JWTController::class, 'register']);
Route::post('CheckToken', [JWTController::class, 'get_user']);

Route::group(['middleware' => ['jwt.verify']], function () {

    Route::get('/customer', [CustomerController::class, 'GetAllCustomer']);

    Route::get('/customer/{id}', [CustomerController::class, 'SearchCustomer']);

    Route::post('/customer', [CustomerController::class, 'SaveCustomer']);

    Route::delete('/customer/{id}', [CustomerController::class, 'DeleteCustomer']);

    Route::put('/customer/{id}', [CustomerController::class, 'UpdateCustomer']);

});
?>