<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});


// CSV download
Route::get("/ProductText", "App\Http\Controllers\ProductController@ProductText");

// Excel download
Route::get("/ProductExcel", "App\Http\Controllers\ProductController@ProductExcel");

// PDF download
Route::get("/Product123", "App\Http\Controllers\ProductController@productviews");
Route::get("exportPDF", "App\Http\Controllers\ProductController@exportPDF");

Route::get("Status/{id}", "App\Http\Controllers\ProductController@ProductStatus");





