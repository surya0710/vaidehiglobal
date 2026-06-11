<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HsnController;
use App\Http\Controllers\ApiContentController;
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

Route::post('/form/contact', [FormController::class, 'popupForm'])->name('form.submit');
Route::post('/form/submit', [FormController::class, 'contactForm'])->name('form.submit');

Route::get('/blogs/{categorySlug?}', [BlogController::class, 'index'])->name('api.blogs.index')->defaults('categorySlug', null);
Route::get('/blog/{slug}', [BlogController::class, 'fetchBlogBySlug'])->name('api.blogs.show');

Route::middleware('static.api.token')->group(function () {
    Route::post('/categories/create', [ApiContentController::class, 'storeCategory'])->name('api.categories.create');
    Route::match(['put', 'post'], '/categories/{category}/modify', [ApiContentController::class, 'updateCategory'])->name('api.categories.modify');
    Route::post('/blogs/create', [ApiContentController::class, 'storeBlog'])->name('api.blogs.create');
    Route::match(['put', 'post'], '/blogs/{blog}/modify', [ApiContentController::class, 'updateBlog'])->name('api.blogs.modify');
});

Route::get('/hsnCode', [HsnController::class, 'index'])->name('api.hsnCode.index');
