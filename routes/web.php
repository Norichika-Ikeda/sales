<?php

use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();

Route::group(
    ['middleware' => ['auth']],
    function () {
        Route::get('/list', [ProductsController::class, 'showList']);

        Route::post('/list', [ProductsController::class, 'showList'])->name('list');

        Route::get('/search', [ProductsController::class, 'searchList']);

        Route::get('/paginate', [ProductsController::class, 'paginateList']);

        Route::get('/create', [ProductsController::class, 'createProductForm']);

        Route::post('/create', [ProductsController::class, 'createProduct'])->name('create');

        Route::get('/detail/{id}', [ProductsController::class, 'detailProduct'])->name('detail');

        Route::get('/editForm/{id}', [ProductsController::class, 'editProductForm'])->name('editForm');

        Route::post('/editForm/{id}', [ProductsController::class, 'editProductForm'])->name('editForm');

        Route::patch('/edit', [ProductsController::class, 'editProduct'])->name('edit');

        Route::delete('/delete/{id}', [ProductsController::class, 'deleteProduct'])->name('delete');
    }
);
