<?php

//use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
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

Route::get('/', [WelcomeController::class, 'index']);
Route::get('contact', [WelcomeController::class, 'contact']);
Route::get('about', [WelcomeController::class, 'about']);
Route::get('contactp', [WelcomeController::class, 'contactp']);
Route::get('despre', [WelcomeController::class,'despre']);
Route::get('despresir', [WelcomeController::class,'despresir']);
