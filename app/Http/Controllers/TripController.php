<?php

namespace App\Http\Controllers;
use App\Models\Trip;
use App\Models\UserTrip;
use App\Models\SharedTrip;
use App\Models\Mark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use mysql_xdevapi\Exception;

class TripController extends Controller
{

    public function init(Request $request){
        try {
            $validatedData = $request->validate([
                'user_id' =>'required',
                'title' => 'required',
                'desc' => 'required',
                'startdate' => 'required|date|date_format:Y-m-d',
                'enddate' => 'required|date|date_format:Y-m-d|after_or_equal:startdate',
            ], [
                'user_id.required' => 'PROBLEM W POBRANIU Użytkownika.',
                'title.required' => 'Pole Tytuł jest wymagane.',
                'desc.required' => 'Pole Opis jest wymagane.',
                'startdate.required' => 'Pole Data rozpoczęcia jest wymagane.',
                'startdate.date' => 'Pole Data rozpoczęcia musi być prawidłową datą.',
                'startdate.date_format' => 'Pole data rozpoczęcia musi być w formacie DD-MM-YYYY.',
                'enddate.required' => 'Pole data zakończenia jest wymagane.',
                'enddate.date' => 'Pole data zakończenia musi być prawidłową datą.',
                'enddate.date_format' => 'Pole data zakończenia musi być w formacie DD-MM-YYYY.',
                'enddate.after_or_equal' => 'Pole data zakończenia musi być datą późniejszą lub równą dacie rozpoczęcia.',
            ]);

            $trip = new Trip();
            $trip->owner_id = $request->user_id;
            $trip->title = $request->title;
            $trip->desc = $request->desc;
            $trip->start_date = $request->startdate;
            $trip->end_date = $request->enddate;
            $trip->save();

           // return redirect()->back()->with('success', 'Dane zostały pomyślnie zapisane.');
            return redirect('/map/' . $trip->trip_id);




        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Wystąpił błąd podczas zapisywania danych.' . $e->getMessage());
        }





    }



    public function index($trip_id)
    {
        try{
            if(Auth::user() != null){
                $user = Auth::user();
            }else{
                return redirect('/login');

            }
            // Pobierz dane o wybranym tripie na podstawie $tripId
            $trip = Trip::find($trip_id);
            if (!$trip) {
                abort(404); // Możesz przekierować użytkownika lub wyświetlić inny komunikat błędu
            }

            $sharedTrip = SharedTrip::where('trip_id', $trip_id)->where('user_id', $user->user_id)->first();


            if( $user->user_id === $trip->owner_id){
            }else if($sharedTrip != null && $user->user_id === $sharedTrip->user_id && $sharedTrip->trip_id === $trip->trip_id){
            }else{
                abort(403);
            }

        }catch (Exception $e){
            abort(404);
    }





        // Pobierz markery związane z tym triprem
        $markers = Mark::where('trip_id', $trip_id)->get();

        $markerData = [];
        if ($markers) {
            foreach ($markers as $marker) {
                $markerData[] = [
                    'id' => $marker->id,
                    'desc' => $marker->desc,
                    'name' => $marker->name,
                    'address' => $marker->address,
                    'latitude' => $marker->latitude,
                    'longitude' => $marker->longitude,
                ];
            }
        }
  /*
        $markers = Mark::all();
        $markerData = [];
        foreach ($markers as $marker) {
            $markerData[] = [
                'id' =>$marker->id,
                'desc' =>$marker->desc,
                'name' => $marker->name,
                'address' => $marker->address,
                'latitude' => $marker->latitude,
                'longitude' => $marker->longitude,
            ];
        }
*/
        return view('trip_creator', compact('markerData', 'trip'));
    }

    public function store(Request $request)
    {

/*
                try {
                        $validatedData = $request->validate([
                    'name' => 'required|min:3',
                    'desc' => 'required',
                    'address' => 'required',
                    'latitude' => 'required',
                    'longitude' => 'required',
                ]);


                    $mark = new Mark();
                    $mark->name = $request->name;
                    $mark->desc = $request->desc;
                    $mark->address = $request->address;
                    $mark->latitude = $request->latitude;
                    $mark->longitude = $request->longitude;
                    $mark->save();

                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Wystąpił błąd podczas zapisywania danych.' . $e->getMessage());
                }
        return redirect()->back()/*->with('success', 'Dane zapisane pomyślnie.')*/;
        // Odczytaj identyfikator tripa z żądania

        $trip_id = $request->trip_id;

        $mark = new Mark();
        $mark->trip_id = $trip_id;
        $mark->is_general = 0;
        $mark->name = $request->name;
        $mark->desc = $request->desc;
        $mark->address = $request->address;
        $mark->latitude = $request->latitude;
        $mark->longitude = $request->longitude;
        $mark->save();

        return redirect()->back()->with('message', 'Operacja zakończona pomyślnie.'. $mark->trip_id);
    }
}
