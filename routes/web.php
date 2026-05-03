<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::controller(UserController::class)->group(function(){
    Route::get('/', 'index');
    Route::get('/admin/dashboard', 'dashboard');
    Route::get('/register', 'register');
    Route::get('/login', 'login')->name('login');
    Route::post('/add-user', 'addUser')->name('register');
    Route::post('/checkLogin', 'checkLogin')->name('checkLogin');
    Route::post('/logout', 'logout')->name('logout');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::prefix('/admin')->group(function () {
        Route::controller(CategoryController::class)->group(function () {
            Route::get('/category', 'category');
            Route::get('/category/add', 'addCategory');
            Route::get('/category/{id}/edit', 'editCategory');
            Route::post('/category/{id}/update', 'updateCategory')->name('updateCategory');
            Route::post('/category/create', 'createCategory')->name('createCategory');
            Route::get('/category/{id}/delete', 'deleteCategory');
        });
        Route::controller(ProductController::class)->group(function () {
            Route::get('/product', 'product');
            Route::get('/product/add', 'addProduct');
            Route::get('/product/edit', 'editProduct');
            Route::post('/product/createProduct', 'createProduct')->name('createProduct');
            Route::post('/product/{id}/update', 'updateProduct');
            Route::get('/product/{id}/delete', 'deleteProduct');
            });
         Route::controller(UserController::class)->group(function () {
             Route::get('/user', 'user');
             Route::get('/dashboard','dashboard');
            
        });
    });
});