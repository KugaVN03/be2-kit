<?php

use App\Http\Controllers\Api\CommentController as ApiCommentController;
use App\Http\Controllers\Api\ProductController as ApiProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('products', ApiProductController::class);
Route::get('products/{id}/comments', [ApiCommentController::class, 'show']);
Route::post('products/{id}/comments', [ApiCommentController::class, 'store']);
Route::post('products/{id}/likes', [ApiProductController::class, 'like']);
Route::post('products/{id}/unlikes', [ApiProductController::class, 'unlike']);