<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AuthorController;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\BorrowController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json(['success' => true, 'data' => $request->user()]);
    });

    // Authors
    Route::apiResource('authors', AuthorController::class);

    // Books
    Route::apiResource('books', BookController::class);

    // Borrows
    Route::prefix('borrows')->group(function () {
        Route::post('/', [BorrowController::class, 'store']);
        Route::get('/my', [BorrowController::class, 'history']);
        Route::patch('/{id}/return', [BorrowController::class, 'returnBook']);
    });
});
