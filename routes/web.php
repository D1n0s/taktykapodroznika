<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TripController;
use App\Events\PresenceEvent;
use App\Events\PrivateEvent;
use App\Events\PublicEvent;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();




Route::middleware(['auth'])->group(function () {
    Route::get('/mytrips',[ProfileController::class,'mytrips']);

});


Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::get('/profile_edit', [ProfileController::class, 'editshow']);
Route::get('/profile_edited', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/profile_cancel', [ProfileController::class, 'cancel'])->name('profile.cancel');


//Tworzenie TRIPA
Route::post('/init',[TripController::class, 'init'])->name('init');
//Route::get('/map', [TripController::class, 'index']);
Route::get('/map/{trip}', [TripController::class, 'index']);
Route::post('/mark', [TripController::class, 'store'])->name('trip.store');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/color', function () {
    return view('color-picker');
});

Route::post('/fireEvent', function (Request $request) {
    PublicEvent::dispatch($request->color);
})->name('fire.public.event');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
