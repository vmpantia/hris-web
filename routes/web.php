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

Auth::routes(['verify' => true]);


Route::group(['middleware' => 'auth'], function() {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/home', function () {
        return view('welcome');
    });


    ################## ADMIN ROUTES ##################
    
        ################## DEPARTMENT ROUTES ##################
        Route::get('/admin/department', [App\Http\Controllers\DepartmentController::class, 'index']);
        Route::get('/admin/saveDepartment', [App\Http\Controllers\DepartmentController::class, 'saveDepartment']);
        Route::get('/admin/getDepartments', [App\Http\Controllers\DepartmentController::class, 'getDepartments']);
        Route::get('/admin/searchDepartments/{keyword}', [App\Http\Controllers\DepartmentController::class, 'searchDepartments']);
});