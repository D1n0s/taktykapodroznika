<?php

namespace App\Http\Controllers;
use App\Events\AttractionEvent;
use App\Events\AddPostEvent;
use App\Events\DelAttractionEvent;
use App\Events\DelPostEvent;
use App\Events\DelQueueEvent;
use App\Events\EditMarkEvent;
use App\Events\MarkEvent;
use App\Events\PrivateEvent;
use App\Events\AddQueueEvent;
use App\Events\TripEvent;
use App\Models\Attraction;
use App\Models\Trip;
use App\Models\Post;
use App\Models\User;
use App\Models\UserInvite;
use App\Models\UserTrip;
use App\Models\SharedTrip;
use App\Models\Mark;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;


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

    public function Gate($trip_id){
            $user = null;
            if(Auth::user() != null){
                $user = Auth::user();
            }else{
                return redirect('/login');
            }

        $trip = Trip::find($trip_id);

            if (!$trip) {
                return abort(404);
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
    public function checkPermission(){
        $trip_id = session('trip_id');
        if(!$this->Gate($trip_id)){
            return abort(404);
        }

        $trip = Trip::find($trip_id);
        $sharedusers = $trip->sharedusers;

        if(auth()->user()->user_id === $trip->owner_id){
            return true;
        }
        $permission = $sharedusers->where('user_id', auth()->user()->user_id)->first()->pivot->permission_id;
        if($permission === 1 ){
            return true;
        }else if($permission === 2 ){
            return false;
        }
        return false;
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

   public function delMarker(Request $request){
       $trip_id = session('trip_id');
       if(!$this->checkPermission()){
           return response()->json(['message' => 'Nie masz uprawnień'],400);
       }

        $mark_id = $request->input('mark_id');
       $trip = Trip::find($trip_id);
       $mark = $trip->marks->find($mark_id);
       $mark->delete();
        MarkEvent::dispatch($trip_id);
       return response()->json(['message' => 'Zaktualizowano dane pomyślnie'],200);
}


    public function index($trip_id)
    {
            if(!$this->Gate($trip_id)){
                     return abort(404);
             }

        session(['trip_id' => $trip_id]);
        $trip = Trip::find($trip_id);
        $sharedusers = $trip->sharedusers;
          $markerData = $this->getMarkers($trip_id);
        $posts = $trip->posts->sortBy('date');
       $attractions = $trip->posts->pluck('attractions')->flatten()->sortBy('time_start');

        $attractionsWithTime = $attractions->filter(function ($attraction) {
            return isset($attraction->time_start);
        })->sortBy('time_start');

        $attractionsWithoutTime = $attractions->reject(function ($attraction) {
            return isset($attraction->time_start);
        });

         $attractions = $attractionsWithTime->merge($attractionsWithoutTime);

        $permission = $sharedusers->where('user_id', auth()->user()->user_id)->first()->pivot->permission_id;



      return view('trip_creator', compact('markerData', 'trip','posts','attractions','sharedusers','permission'));
    }

    public function addMarker(Request $request)
    {
        $trip_id = $request->trip_id;
       if(!$this->checkPermission()){
           return response()->json(['message' => 'Nie masz uprawnień'],400);
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
       if(!$this->checkPermission()){
           return response()->json(['message' => 'Nie masz uprawnień'],400);
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
       if(!$this->checkPermission()){
           return response()->json(['message' => 'Nie masz uprawnień'],400);
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
       if(!$this->checkPermission()){
           return response()->json(['message' => 'Nie masz uprawnień'],400);
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
       if(!$this->checkPermission()){
           return response()->json(['message' => 'Nie masz uprawnień'],400);
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
        if($post->save()){
            AddPostEvent::dispatch($trip_id,"Dodano nowy post", $post);
            return response()->json(['success' => 'Udało się utworzyć Post ! '], 200);

        }else{
            return response()->json(['error' => 'Nie udało się utworzyć Posta'], 200);
        }

    }
    public function delPost(Request $request){
        $trip_id = session('trip_id');
       if(!$this->checkPermission()){
           return response()->json(['message' => 'Nie masz uprawnień'],400);
       }

        $post_id = $request->input('postid');
        $post = Post::where('post_id', $post_id)->where('trip_id', $trip_id)->first();

        if($post){
                $post->attractions()->delete();
                $post->delete();
                DelPostEvent::dispatch($trip_id);

            return response()->json(['success' => 'Udało się usunąć Post ! ' ], 200);
        }else{
            return response()->json(['error' => 'NIE Udało się usunąć Post ! ' ], 400);
        }



    }
    public function Attraction(Request $request){
        $trip_id = session('trip_id');
       if(!$this->checkPermission()){
           return response()->json(['message' => 'Nie masz uprawnień'],400);
       }

        $markers = $this->getMarkers($trip_id);

        $post_id = $request->input('post_id');
        $post = Post::find($post_id);
        $att = null;
        return view('components.addAttractionComponents', compact('att','post', 'markers', 'trip_id'));
    }
    public function editAttraction(Request $request){
        $trip_id = session('trip_id');
       if(!$this->checkPermission()){
           return response()->json(['message' => 'Nie masz uprawnień'],400);
       }

        $att_id = $request->input('attractionId');
        $att = Attraction::find($att_id);
        $markers = $this->getMarkers($trip_id);
        $post = $att->post;
        echo $post;

        return view('components.addAttractionComponents', compact('att','post', 'markers', 'trip_id'));
    }

    public function addAttraction(Request $request){
        $trip_id = session('trip_id');
       if(!$this->checkPermission()){
           return response()->json(['message' => 'Nie masz uprawnień'],400);
       }

        $existingAttraction = Attraction::where('post_id', $request->input('post'))
            ->where('attraction_id', $request->input('attraction_id'))->first();
        if ($existingAttraction) {
            $duration = null;
            if($request->input('start_time') != null && $request->input('end_time') != null) {
                $start_time = Carbon::createFromFormat('H:i', $request->input('start_time'));
                $end_time = Carbon::createFromFormat('H:i', $request->input('end_time'));
                $duration = ($start_time && $end_time) ? $end_time->diff($start_time) : null;
            }
            $existingAttraction->title = $request->input('name');
            $existingAttraction->desc = $request->input('desc');
            $existingAttraction->cost = $request->input('price') ?? '0.00';
            $existingAttraction->time_start = $request->input('start_time');
            $existingAttraction->time_end = $request->input('end_time');
            $existingAttraction->mark_id = $request->input('location');
            $existingAttraction->duration = ($duration) ? $duration->format('%H:%I') : null;
            $existingAttraction->save();
            AttractionEvent::dispatch($trip_id);
            return  redirect("/map/$trip_id?tab=posts")->with('success', 'Utworzono pomyślnie atrakcje');

        } else {
            $duration = null;
            if($request->input('start_time') != null && $request->input('end_time') != null) {
                $start_time = Carbon::createFromFormat('H:i', $request->input('start_time'));
                $end_time = Carbon::createFromFormat('H:i', $request->input('end_time'));
                $duration = ($start_time && $end_time) ? $end_time->diff($start_time) : null;
            }
            $attraction = new Attraction();
            $attraction->post_id = $request->input('post');
            $attraction->title = $request->input('name');
            $attraction->desc = $request->input('desc');
            $attraction->cost = $request->input('price') ?? '0.00';
            $attraction->time_start = $request->input('start_time');
            $attraction->time_end = $request->input('end_time');
            $attraction->mark_id = $request->input('location');
            $attraction->setDurationAttribute($duration);
            $attraction->save();
            AttractionEvent::dispatch($trip_id);
            return  redirect("/map/$trip_id?tab=posts")->with('success', 'Utworzono pomyślnie atrakcje');

        }
    }

    public function delAttraction(Request $request){
        $trip_id = session('trip_id');
       if(!$this->checkPermission()){
           return response()->json(['message' => 'Nie masz uprawnień'],400);
       }

        if($request){
        $att_id = $request->input('attractionId');
        $att = Attraction::find($att_id)->delete();
        AttractionEvent::dispatch($trip_id);
        }else {
            return response()->json(['error' => 'Nie znaleziono produktu o podanym ID'], 200);
        }
        return response()->json(['message' => 'Zaktualizowano dane pomyślnie'],200);
    }
    public function moveAttraction(Request $request){
        $trip_id = session('trip_id');
       if(!$this->checkPermission()){
           return response()->json(['message' => 'Nie masz uprawnień'],400);
       }
            $att_id = $request->input('attraction_id');
            $post_id = $request->input('post_id');
            $attraction = Attraction::find($att_id);
            $post = Post::find($post_id);

            $attraction->post_id = $post->post_id;
            $attraction->save();

            AttractionEvent::dispatch($trip_id);

            return response()->json(['error' => 'Nie znaleziono produktu o podanym ID'], 200);
        return response()->json(['message' => 'Zaktualizowano dane pomyślnie'],200);
    }

    public function addTactic(Request $request){
        $trip_id = session('trip_id');
       if(!$this->checkPermission()){
           return response()->json(['message' => 'Nie masz uprawnień'],400);
       }

        $user_id = $request->input('user_id');
        $trip_id = $request->input('trip_id');
        $email = $request->input('email');
        $permission = $request->input('permission');

        $invited_user = User::where('email', $email)->first();
        if ($invited_user === null) {
            return response()->json(['error' => 'Nie znaleziono użytkownika'], 400);
        }

        $trip = Trip::where('trip_id',$trip_id)->where('owner_id',$invited_user->user_id)->first();

        if($trip != null){
            return response()->json(['error' => 'To Twój wyjazd przyjacielu !'], 400);
        }

        $existingInvite = UserInvite::where('user_id', $invited_user->user_id)->where('invited_trip', $trip_id)->first();
        if ($existingInvite != null) {
            return response()->json(['error' => 'Zaproszenie już istnieje'], 400);
        }

        $shared = SharedTrip::where('user_id',$invited_user->user_id)->where('trip_id',$trip_id)->first();

        if($shared != null){
            return response()->json(['error' => 'Użytkownik został już dodany ! '], 400);
        }

        if ($invited_user) {
            $invite = new UserInvite();
            $invite->user_id = $invited_user->user_id;
            $invite->invited_by = $user_id;
            $invite->invited_trip = $trip_id;
            $invite->permission = $permission;
            $invite->save();

            return response()->json(['success' => 'Wysłano zaproszenie użytkownikowi' . $email], 200);
        }
            return response()->json(['error' => 'Nie znaleziono użytkownika'], 400);

    }

   public function delTactic(Request $request){
       $trip_id = session('trip_id');
       if(!$this->Gate($trip_id)){
           return abort(404);
       }

       $user_email = $request->input('email');

       $user_id = User::where('email', $user_email)->first()->user_id;
       $shared = SharedTrip::where('trip_id',$trip_id)->where('user_id',$user_id)->first();
       $shared->delete();
       return response()->json(['success' => 'Pomyślnie usunięto użytkownika !'], 200);
   }

    public function SavePermissions(Request $request){
        $trip_id = session('trip_id');
       if(!$this->checkPermission()){
           return response()->json(['message' => 'Nie masz uprawnień'],400);
       }

        $user_email = $request->input('email');
        $permission_id = $request->input('permission_id');
        $user_id = User::where('email', $user_email)->first()->user_id;
        $shared = SharedTrip::where('trip_id',$trip_id)->where('user_id',$user_id)->first();

        if($shared->permission_id == $permission_id){
            return response()->json(['success' => 'Użytkownik posiada już takie uprawnienia'], 200);
        }

        $shared->permission_id = $permission_id;
        $shared->save();

        return response()->json(['success' => 'Pomyślnie zmieniono uprawnienia'], 200);

    }


}
