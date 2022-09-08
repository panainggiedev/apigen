<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HistoryController;

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
    return view('auth/login')->with('id', 'idd');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\APIController::class, 'index'])->name('home');
Route::resource('/api', 'App\Http\Controllers\APIController');

Route::get('/shift-data', [App\Http\Controllers\APIController::class, 'shiftdata'])->name('shiftdata');
Route::get('/destroy-data', [App\Http\Controllers\APIController::class, 'destroy'])->name('destroy');
Route::post('/update-data', [App\Http\Controllers\APIController::class, 'update'])->name('update');
Route::post('/new-data', [App\Http\Controllers\APIController::class, 'newdata'])->name('newdata');

//Route::get('/api',[App\Http\Controllers\APIController::class, 'shows'])->name('shows');

Route::group(['middleware' => ['auth']], function() {
   /**
   * Logout Route
   */
   Route::get('/logout', 'App\Http\Controllers\LogoutController@perform')->name('logout.perform');
});

Route::group(['middleware' => ['auth']], function() {
   /**
   * Logout Route
   */
   Route::get('/idle', 'App\Http\Controllers\LogoutController@idle')->name('logout.idle');
});