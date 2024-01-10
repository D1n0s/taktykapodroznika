<?php

use App\Events\TripEvent;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicTripsController;
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
    Route::get('/sharedtrips',[ProfileController::class,'sharedtrips']);
    Route::get('/publictrips',[PublicTripsController::class,'index'])->name('publictrips');
    Route::get('/map/{trip}', [TripController::class, 'index']);


Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::get('/profile_edit', [ProfileController::class, 'editshow']);
Route::get('/profile_edited', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/profile_cancel', [ProfileController::class, 'cancel'])->name('profile.cancel');


//Tworzenie TRIPA
Route::post('/init',[TripController::class, 'init'])->name('init');
Route::post('/deltrip/{trip_id}',[TripController::class, 'delTrip'])->name('delTrip');
Route::post('/leavetrip/{trip_id}',[TripController::class, 'leaveTrip'])->name('leaveTrip');



//Taktyk
Route::post('/trip/getMarkers/{trip_id}', [TripController::class, 'getMarkers']);
Route::post('/attraction', [TripController::class, 'Attraction'])->name('Attraction');
Route::post('/tacticadd', [TripController::class, 'addTactic'])->name('addTactic');
Route::post('/tacticadel', [TripController::class, 'delTactic'])->name('delTactic');
Route::post('/inviteaccept', [ProfileController::class, 'AcceptInvite'])->name('AcceptInvite');
Route::post('/invitedeceline', [ProfileController::class, 'DeclineInvite'])->name('DeclineInvite');
Route::post('/savepermissions', [TripController::class, 'SavePermissions'])->name('SavePermissions');
Route::post('/changetripsettings', [TripController::class, 'ChangeTripSetting'])->name('ChangeTripSetting');

//TRIP AKCJE
Route::post('/markadd', [TripController::class, 'addMarker'])->name('addMarker');
Route::post('/markedit', [TripController::class, 'editMarker'])->name('editMarker');
Route::post('/markdel', [TripController::class, 'delMarker'])->name('delMarker');
Route::post('/queueadd', [TripController::class, 'addQueue'])->name('addQueue');
Route::post('/queuedel', [TripController::class, 'delQueue'])->name('delQueue');
Route::post('/makeroute', [TripController::class, 'getWaypoints'])->name('makeRoute');
Route::post('/addpost', [TripController::class, 'addPost'])->name('addPost');
Route::post('/delpost', [TripController::class, 'delPost'])->name('delPost');
Route::post('/addattraction', [TripController::class, 'addAttraction'])->name('addAttraction');
Route::post('/editattraction', [TripController::class, 'editAttraction'])->name('editAttraction');
Route::post('/attractiondel', [TripController::class, 'delAttraction'])->name('delAttraction');
Route::post('/moveattraction', [TripController::class, 'moveAttraction'])->name('moveAttraction');
Route::post('/addroutedata', [TripController::class, 'AddRouteData'])->name('AddRouteData');
Route::post('/addvehicle', [TripController::class, 'AddVehicle'])->name('AddVehicle');
Route::post('/delvehicle', [TripController::class, 'DelVehicle'])->name('DelVehicle');
Route::post('/fuelPrice', [TripController::class, 'FuelPrice'])->name('FuelPrice');
Route::post('/PersonNumber', [TripController::class, 'PersonNumber'])->name('PersonNumber');

//Udostępnianie podróży
Route::post('/addpublictrip',[PublicTripsController::class,'AddPublicTrip'])->name('AddPublicTrip');
Route::post('/delpublictrip',[PublicTripsController::class,'DelPublicTrip'])->name('DelPublicTrip');
Route::post('/copypublictrip/{trip_id}',[PublicTripsController::class,'CopyPublicTrip'])->name('CopyPublicTrip');




});


Route::get('/', function () {
    return view('welcome');
});






Route::get('/color', function () {
    return view('color-picker');
});
Route::post('/waterEvent', function (Request $request){
    TripEvent::dispatch($request->trip_id,$request->message);
})->name('trip.event');
//Route::post('/waterEvent', function (Request $request){
//
//     PrivateEvent::dispatch($request->message);
//
//})->name('fire.private.event');


Route::post('/fireEvent', function (Request $request) {
    PublicEvent::dispatch($request->color);
})->name('fire.public.event');


