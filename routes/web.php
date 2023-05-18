<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Livewire\Backend\Users\Users;
use App\Http\Livewire\Backend\Events\Events;
use App\Http\Controllers\OfficeDropdownController;
use App\Http\Livewire\Backend\Dashboard\Dashboard;

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

Auth::routes(['verify' => true]);

Route::get('/getOffices/{education_id}',[OfficeDropdownController::class, 'getOffices']);

//Frontend
Route::group(['middleware' => ['auth', 'verified']], function (){
    Route::get('/', [HomeController::class, 'index'])->name('home');
});

//Backend
Route::group(['prefix' => 'admin', 'as' => 'admin.','middleware' => ['auth', 'role:admin|superadmin|operationsmanager']], function (){
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('users', Users::class)->name('users');
    Route::get('events', Events::class )->name('events');
});
