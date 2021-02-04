<?php

use App\Http\Controllers\HomeController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', [HomeController::class, 'index']);

Route::get('/', 'HomeController@index');
Route::post('/trackingresult', 'HomeController@trackingresult');

Route::get('/createForm', 'HomeController@createForm');
Route::post('/createFormProcess', 'HomeController@createFormProcess');

Route::post('/province', 'HomeController@province');
Route::post('/city', 'HomeController@city');
Route::post('/stores', 'HomeController@stores');
Route::post('/backend_stores', 'HomeController@backend_stores');
Route::post('/branch_drop_off', 'HomeController@branch_drop_off');