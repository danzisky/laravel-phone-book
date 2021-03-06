<?php

use App\Http\Controllers\ContactsController;
use App\Http\Controllers\PhonebooksController;
use App\Models\Phonebook;
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


Route::resource('phonebooks', PhonebooksController::class)->middleware('auth');
Route::resource('phonebooks.contacts', ContactsController::class)->shallow()->middleware('auth');
Route::post('phonebooks/publicity', [PhonebooksController::class, 'changePublicity'])->name('publicity');
Route::post('contacts/visibility', [ContactsController::class, 'changeVisibility'])->name('visibility');
Route::get('phonebook/{id}', [PhonebooksController::class, 'getPhonebook'])->name('phonebook');
Route::get('contact/{id}', [ContactsController::class, 'getContact'])->name('contact');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
