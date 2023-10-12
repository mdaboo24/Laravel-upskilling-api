<?php


use App\Http\Controllers\Api\v1\TestApiController;
use Illuminate\Support\Facades\Route;

Route::post('create', [TestApiController::class, 'create']);
