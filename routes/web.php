<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CkeditorController;

/*
|--------------------------------------------------------------------------
| FRONTEND (React App)
|--------------------------------------------------------------------------
*/

// ✅ React app entry
Route::get('/', function () {
    return view('index'); // ⚠️ changed from 'index' → 'app'
});


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AdminController::class,'login'])->name('admin.login');
Route::post('/admin/login', [AdminController::class,'loginAttempt'])->name('admin.loginAttempt');

Route::middleware(['auth.admin', 'utype:ADM'])->group(function(){

    Route::get('/admin', [AdminController::class,'index'])->name('admin.index');

    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/admin/categories/add', [AdminController::class, 'category_add'])->name('admin.category.add');
    Route::post('/admin/categories/store', [AdminController::class, 'category_store'])->name('admin.category.store');
    Route::get('/admin/categories/edit/{id}', [AdminController::class, 'category_edit'])->name('admin.category.edit');
    Route::put('/admin/categories/update', [AdminController::class, 'category_update'])->name('admin.category.update');
    Route::delete('/admin/categories/delete/{id}', [AdminController::class, 'category_delete'])->name('admin.category.delete');

    Route::get('admin/blogs', [AdminController::class, 'blogs'])->name('admin.blogs');
    Route::get('admin/blogs/add', [AdminController::class, 'blog_add'])->name('admin.blog.add');
    Route::post('admin/blogs/store', [AdminController::class, 'blog_store'])->name('admin.blog.store');
    Route::get('admin/blogs/edit/{id}', [AdminController::class, 'blog_edit'])->name('admin.blog.edit');
    Route::put('admin/blogs/update/{blog}', [AdminController::class, 'blog_update'])->name('admin.blog.update');
    Route::put('admin/blogs/{blog}/toggle-status', [AdminController::class, 'BlogToggleStatus'])->name('admin.blog.toggleStatus');
    Route::delete('admin/blogs/{id}/delete', [AdminController::class, 'BlogDelete'])->name('admin.blog.delete');

    Route::get('admin/blogs/import-blogs', [AdminController::class, 'importblogscsv'])->name('import.blogs');
    Route::post('admin/blogs/import-blogs/add', [AdminController::class, 'importBlogs'])->name('import.blogs.add');

    Route::post('/ckeditor/upload', [CkeditorController::class, 'upload'])->name('ckeditor.upload');

    Route::get('admin/hsncode', [AdminController::class, 'hsncode'])->name('admin.hsncode');
    Route::match(['GET', 'POST'], 'admin/hsncode/add', [AdminController::class, 'addHSN'])->name('admin.hsn.add');
    Route::match(['GET', 'POST'], 'admin/hsncode/{id}/edit', [AdminController::class, 'editHSN'])->name('admin.hsn.edit');
    Route::delete('admin/hsncode/{id}/delete', [AdminController::class, 'hsnDelete'])->name('admin.hsn.destroy');
    Route::post('/admin/logout', [AdminController::class,'logout'])->name('logout');
});


/*
|--------------------------------------------------------------------------
| REACT ROUTER CATCH-ALL (VERY IMPORTANT)
|--------------------------------------------------------------------------
*/

// ✅ This fixes refresh 404
Route::get('/{any}', function () {
    return view('index');
})->where('any', '^(?!admin|api).*$');