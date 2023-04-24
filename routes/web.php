<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OfficeDropdownController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/getOffices/{education_id}',[OfficeDropdownController::class, 'getOffices']);

// Route::get('/getOffices/{education_id}',[OfficeDropdownController::class]'OfficeDropdownController@getOffices');

// Route::get('/getOffices/{education_id}', function ($education_id) {
//     $offices = Office::where('education_id', $education_id)->pluck('name', 'id');
//         return response()->json($offices);
// });

Auth::routes(['verify' => true]);

Route::group(['middleware' => ['auth', 'verified']], function (){
    Route::get('/', [HomeController::class, 'index'])->name('home');
});
