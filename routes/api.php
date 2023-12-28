<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::prefix("v1")->group(function () {

    Route::middleware('jwt')->group(function () {

        Route::controller(AuthController::class)->group(function () {
            Route::post('register', "register");
            Route::get('user-lists', 'showUserLists');
            Route::get('your-profile', 'yourProfile');
            Route::put('edit', "edit");
            Route::get('user-profile/{id}', 'checkUserProfile');
            Route::put("change-password", 'changePassword');
            Route::post("logout", 'logout');
        });

        Route::controller(CategoryController::class)->prefix("category")->group(function () {
            Route::post("store", 'store');
            Route::put("update/{id}", 'update');
            Route::delete("delete/{id}", 'destroy');
        });

        Route::controller(BrandController::class)->prefix("brand")->group(function () {
            Route::post("store", 'store');
            Route::put("update/{id}", 'update');
            Route::delete("delete/{id}", 'destroy');
        });

        Route::controller(ProductController::class)->prefix("product")->group(function () {
            Route::post("store", 'store');
            Route::put("update/{id}", 'update');
            Route::delete("delete/{id}", 'destroy');
        });

        Route::controller(ItemController::class)->prefix("item")->group(function () {
            Route::post("store", 'store');
            Route::put("update/{id}", 'update');
            Route::delete("delete/{id}", 'destroy');
        });

        Route::controller(PhotoController::class)->prefix("photo")->group(function () {
            Route::post("store", 'store');
            Route::delete("delete/{id}", 'destroy');
            Route::post('multiple-delete', 'deleteMultiplePhotos');
            Route::get("trash", 'trash');
            Route::patch("deleted-photo/{id}", "deletedPhoto");
            Route::patch("restore/{id}", "restore");
            Route::post("force-delete/{id}", "forceDelete");
            Route::post("clear-trash", "clearTrash");
        });

        Route::controller(ImportController::class)->prefix("import")->group(function () {
            Route::post("excel", 'ExcelImport');
            Route::post("csv", 'CsvImport');
        });
    });

    Route::controller(ExportController::class)->prefix("export")->group(function () {
        Route::get("excel", 'ExcelExport');
        Route::get("csv", 'CsvExport');
    });

    Route::post('login', [AuthController::class, 'login']);

    Route::controller(BrandController::class)->prefix("brand")->group(function () {
        Route::get("list", "index");
        Route::get("show/{id}", "show");
    });

    Route::controller(CategoryController::class)->prefix("category")->group(function () {
        Route::get("list", "index");
        Route::get("show/{id}", "show");
    });

    Route::controller(ProductController::class)->prefix("product")->group(function () {
        Route::get("list", "index");
        Route::get("show/{id}", "show");
    });
    Route::controller(ItemController::class)->prefix("item")->group(function () {
        Route::get("list", "index");
        Route::get("show/{id}", "show");
    });

    Route::controller(PhotoController::class)->prefix("photo")->group(function () {
        Route::get("list", "index");
        Route::get("show/{id}", "show");
    });
});
