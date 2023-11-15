<?php

namespace App\Http\Controllers;
use App\Events\DelQueueEvent;
use App\Events\EditMarkEvent;
use App\Events\PrivateEvent;
use App\Events\AddQueueEvent;
use App\Events\TripEvent;
use App\Models\Trip;
use App\Models\Post;
use App\Models\UserTrip;
use App\Models\SharedTrip;
use App\Models\Mark;
use Carbon\Carbon;
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

    public function checkPermissions($trip_id){

            $user = null;
            if(Auth::user() != null){
                $user = Auth::user();
            }else{
                return redirect('/login');
            }

        $trip = Trip::find($trip_id);

            if (!$trip) {
                return abort(404); // Możesz przekierować użytkownika lub wyświetlić inny komunikat błędu
            }

            $sharedTrip = SharedTrip::where('trip_id', $trip->trip_id)->where('user_id', $user->user_id)->first();

            if( $user->user_id === $trip->owner_id){
                return true;
            }else if($sharedTrip != null && $user->user_id === $sharedTrip->user_id && $sharedTrip->trip_id === $trip->trip_id){
                return true;
            }else{
               return abort(403);
            }



    }
// POBIERANIE MARKERÓW DO MAPY INICJOWANE NA SAMYM STARCIE
    public function getMarkers($trip_id) {
        $markers = Mark::where('trip_id', $trip_id)->orderByRaw("ISNULL(queue), queue ASC")->get();

        $markerData = [];
        if ($markers) {
            foreach ($markers as $marker) {
                $markerData[] = [
                    'id' => $marker->mark_id,
                    'desc' => $marker->desc,
                    'name' => $marker->name,
                    'address' => $marker->address,
                    'latitude' => $marker->latitude,
                    'longitude' => $marker->longitude,
                    'queue' => $marker->queue,
                    'category_id' => $marker->category_id,
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
        // Sprawdź, czy żądanie jest typu JSON
        if (request()->expectsJson()) {
            return response()->json($markerData);
        }

        return $markerData;
    }


    public function index($trip_id)
    {

            if(!$this->checkPermissions($trip_id)){
                     return abort(404);
             }
          $trip = Trip::find($trip_id);
         session(['trip_id' => $trip_id]);
          $markerData = $this->getMarkers($trip_id);
        $posts = $trip->posts;

      return view('trip_creator', compact('markerData', 'trip','posts'));
    }

    public function addMarker(Request $request)
    {
        $trip_id = $request->trip_id;
        if(!$this->checkPermissions($trip_id)){
            return abort(404);
        }

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



        $mark = new Mark();
        $mark->trip_id = $trip_id;
        $mark->is_general = 0;
        $mark->name = $request->name;
        $mark->desc = $request->desc;
        $mark->address = $request->address;
        $mark->latitude = $request->latitude;
        $mark->longitude = $request->longitude;
        $mark->save();

        TripEvent::dispatch($trip_id,"JEST TO FUNKCJA ADD MARKER", $mark);
//        return redirect()->back()->with('message', 'Operacja zakończona pomyślnie.'. $mark->trip_id);

        return response()->json(['message' => 'Dane zapisane pomyślnie']);
    }
    public function editMarker(Request $request){

        $trip_id = session('trip_id');
        if(!$this->checkPermissions($trip_id)){
            return abort(404);
        }



        // Odczytaj dane z żądania POST
        $id = $request->input('id');

        $name = $request->input('name');
        $desc = $request->input('desc');
        $address = $request->input('address');


        // Przykład zapisu do bazy danych w Laravel Eloquent:
        $mark = Mark::find($id);

        $mark->name = $name;
        $mark->desc = $desc;
        $mark->address = $address;

        $mark->save();


        EditMarkEvent::dispatch($trip_id,"JEST TO FUNKCJA EDIT MARKER", $mark);


        // Odpowiedź z sukcesem lub błędem
        return response()->json(['message' => 'Zaktualizowano dane pomyślnie'. $trip_id],200);

    }

    public function addQueue(Request $request){

        $trip_id = session('trip_id');
        if(!$this->checkPermissions($trip_id)){
            return abort(404);
        }

        // Odczytaj dane z żądania POST
        $mark_id = $request->input('productId');
        $queue = $request->input('cartId');
        // Przykład zapisu do bazy danych w Laravel Eloquent:
        $mark = Mark::find($mark_id);
        if ($mark) {
            $mark->queue = $queue; // Ustaw wartość atrybutu 'queue' na wartość zmiennej $queue
            $mark->save(); // Zapisz zmiany w bazie danych

         AddQueueEvent::dispatch($trip_id,"JEST TO FUNKCJA EDIT MARKER", $mark);

        }else {
            // Obsłuż sytuację, gdy nie znaleziono obiektu Mark
            return response()->json(['error' => 'Nie znaleziono produktu o podanym ID'], 200);
        }

        return response()->json(['message' => 'Zaktualizowano dane pomyślnie'. $trip_id],200);

    }
    public function delQueue(Request $request){

        $trip_id = session('trip_id');
        if(!$this->checkPermissions($trip_id)){
            return abort(404);
        }
        $mark_id = $request->input('productId');
        $mark = Mark::find($mark_id);

        if ($mark) {
            $mark->queue = null;
            $mark->save();
        } else {
            // Obsłuż sytuację, gdy nie znaleziono obiektu Mark
            return response()->json(['error' => 'Nie znaleziono produktu o podanym ID'], 200);
        }

        DelQueueEvent::dispatch($trip_id,"JEST TO FUNKCJA EDIT MARKER", $mark);
        return response()->json(['message' => 'Zaktualizowano dane pomyślnie'. $trip_id],200);
    }

    public function getWaypoints(){
        $trip_id = session('trip_id');
        $waypoints = Mark::where('trip_id', $trip_id)->get();

        if ($waypoints) {
            return response()->json(['waypoints' => $waypoints]);
        } else {
            return response()->json(['error' => 'Brak danych']);
        }
    }

    public function addPost(Request $request){
        $trip_id = session('trip_id');
        if(!$this->checkPermissions($trip_id)){
            return abort(404);
        }

        $title = $request->input('title');
        $date = $request->input('date');
        $day = null;
        if($date != null){
            $start_date = Carbon::create(Trip::find($trip_id)->start_date);
            $day = $start_date->diffInDays(Carbon::create($request->input('date'))) + 1;
        }

        $post = new Post();
        $post->trip_id = $trip_id;
        $post->title = $title;
        $post->date = $date;
        $post->day = $day;
        $post->save();



        return redirect()->back()->with('success', 'DZIAŁA KURDE');
    }



}
