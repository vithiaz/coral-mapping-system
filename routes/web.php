<?php

use App\Livewire\Homepage;
use App\Livewire\CoralPointsList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\AddCoralPoints;

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

Route::get('/logout', function () {
    
    Auth::logout();
    return redirect()->back();
});


Route::get('/', Homepage::class)->name('homepage');
Route::get('/corals', CoralPointsList::class)->name('coral-list');
Route::get('/admin/add-coral-loc', AddCoralPoints::class)->name('admin.add-coral-points');

