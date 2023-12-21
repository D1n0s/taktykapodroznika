<?php

namespace App\Http\Controllers;

use App\Models\PublicTrip;
use App\Models\Trip;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PublicTripsController extends Controller
{

    public function index()
    {

        $trips = PublicTrip::with('trip')->paginate(5);
//        dd($trips);
        return view('publicTrips', compact('trips'));
    }


    public function AddPublicTrip(){

        if(PublicTrip::where('trip_id',session('trip_id'))->first() == null){
            $trip = new PublicTrip();
            $trip->trip_id = session('trip_id');
            $trip->save();
            return back();

        }else{
            return back();
        }


    }

    public function DelPublicTrip(){
        $trip = PublicTrip::where('trip_id', session('trip_id'))->first();
        $trip->delete();
        return back();

    }
    public function CopyPublicTrip($trip_id,Request $request) {
        // Pobierz oryginalny trip z wszystkimi relacjami
        $originalTrip = Trip::with(['marks', 'vehicles', 'owner', 'publicTrip', 'posts'])->find($trip_id);


        if (!$originalTrip) {
            return back()->with('error', 'Nie znaleziono podróży.');
        }

        // Sprawdź, czy użytkownik jest właścicielem oryginalnego tripu
        if ($originalTrip->owner_id == Auth::user()->user_id) {
            return back()->with('error', 'Ta podróż jest już Twoja.');
        }

        if ($originalTrip->publicTrip) {
            $originalTrip->publicTrip->update(['copied' => $originalTrip->publicTrip->copied + 1]);
             $originalTrip->publicTrip->save();
        }

        // Skopiuj trip
        $newTrip = $originalTrip->replicate();
        $newTrip->owner_id = Auth::user()->user_id;

        // Walidacja nowych danych
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:30',
            'startdate' => 'required|date',
            'enddate' => 'required|date|after_or_equal:startdate',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }

        if ($request->input('title') !== $originalTrip->title ||
            $request->input('startdate') !== $originalTrip->start_date ||
            $request->input('enddate') !== $originalTrip->end_date) {

            // Ustaw nowe dane, ale jeszcze nie zapisuj ich w bazie danych
            $newTrip->title = $request->input('title');
            $newTrip->start_date = $request->input('startdate');
            $newTrip->end_date = $request->input('enddate');

        }





            if($newTrip->save()) {

                // Skopiuj markery
                foreach ($originalTrip->marks as $originalMark) {
                    $newMark = $originalMark->replicate();
                    $newMark->trip_id = $newTrip->trip_id;
                    $newMark->save();
                }

                // Skopiuj pojazdy
                foreach ($originalTrip->vehicles as $originalVehicle) {
                    $newVehicle = $originalVehicle->replicate();
                    $newVehicle->trip_id = $newTrip->trip_id;
                    $newVehicle->save();
                }

                // Skopiuj wpisy
                foreach ($originalTrip->posts as $originalPost) {
                    $newPost = $originalPost->replicate();
                    $newPost->trip_id = $newTrip->trip_id;

                    if ($originalTrip->start_date !== $newTrip->start_date) {
                        $newDate= new DateTime($newTrip->start_date);
                        $end_date = new DateTime($newTrip->end_date);

                        $newDate->add(new DateInterval('P' . ($newPost->day - 1) . 'D'));

                        if ($newDate <= $end_date) {
                            $newPost->date = $newDate;
                        }else if($newDate > $end_date){
                            $newPost->date = null;
                            $newPost->day = null;
                        }
                     //   dd($newDate->format('Y-m-d'), ' startt ' . $newTrip->start_date . " koniec " . $end_date->format('Y-m-d'));
                    }
                    $newPost->save();

                    // Skopiuj atrakcje przypisane do wpisu
                    foreach ($originalPost->attractions as $originalAttraction) {
                        $newAttraction = $originalAttraction->replicate();
                        $newAttraction->post_id = $newPost->post_id;
                        $newAttraction->save();
                    }
                }
             return redirect('/map/' . $newTrip->trip_id);
        }
        return back()->with('error', 'JAKIŚ PROBLEM');

    }

}
