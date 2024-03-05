<?php

namespace App\Http\Controllers;
use App\Events\AttractionEvent;
use App\Events\AddPostEvent;
use App\Events\DelAttractionEvent;
use App\Events\DelPostEvent;
use App\Events\DelQueueEvent;
use App\Events\EditMarkEvent;
use App\Events\fuelUpdateEvent;
use App\Events\InfoUpdateEvent;
use App\Events\MarkEvent;
use App\Events\PersonsUpdateEvent;
use App\Events\PrivateEvent;
use App\Events\AddQueueEvent;
use App\Events\settingsUpdateEvent;
use App\Events\TripEvent;
use App\Models\Attraction;
use App\Models\CategorieAttraction;
use App\Models\PublicTrip;
use App\Models\Trip;
use App\Models\Post;
use App\Models\User;
use App\Models\UserInvite;
use App\Models\UserTrip;
use App\Models\SharedTrip;
use App\Models\Mark;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Validator;
use DateInterval;
use DateTime;

class TripController extends Controller
{

    function leaveTrip($trip_id){
        $trip = Trip::with(['sharedusers'])->where('trip_id', $trip_id)->first();
        if ($trip) {
            $sharedUser = $trip->sharedusers->where('user_id', Auth::user()->user_id)->first();
            if ($sharedUser) {
                // Detach the relationship (remove the user from the trip)
                $trip->sharedusers()->detach(Auth::user()->user_id);

                return redirect()->back()->with('success', ' Udało się odejść z podróży');
            } else {
                return redirect()->back()->with('error', 'Użytkownik nie jest już częścią podróży');

            }
        } else {
            return redirect()->back()->with('error', 'Takiej podróży już nie ma ');
        }
    }


    public function delTrip($trip_id)
    {
        // Pobierz trip wraz z powiązaniami
        $trip = Trip::with(['marks', 'vehicles', 'sharedusers', 'publicTrip', 'posts', 'invites'])->where('trip_id',$trip_id)->where('owner_id',Auth::user()->user_id)->first();
        if (!$trip) {
            return redirect()->back()->with('error', 'Nie można znaleźć tripa do usunięcia.');
        }
        DB::beginTransaction();
        try {
            $trip->invites()->delete();
            $trip->sharedusers()->detach();

            $trip->marks()->delete();
            $trip->vehicles()->delete();
            $trip->publicTrip()->delete();
            $trip->posts()->each(function ($post) {
                $post->attractions()->delete();
            });
            $trip->posts()->delete();

            $trip->delete();

            DB::commit();


            return redirect()->back()->with('success', 'Trip został pomyślnie usunięty wraz z powiązaniami.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd('nie udało się !  ' . $e->getMessage());

            return redirect()->back()->with('error', 'Wystąpił błąd podczas usuwania tripa: ' . $e->getMessage());
        }
    }

    function init(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|max:30',
                'startdate' => 'required|date|date_format:Y-m-d',
                'enddate' => 'required|date|date_format:Y-m-d|after_or_equal:startdate',
            ], [
                'title.required' => 'Pole Tytuł jest wymagane.',
                'title.max' => 'Maksymalnie 30 znaków !',
                'startdate.required' => 'Pole Data rozpoczęcia jest wymagane.',
                'startdate.date' => 'Pole Data rozpoczęcia musi być prawidłową datą.',
                'startdate.date_format' => 'Pole data rozpoczęcia musi być w formacie DD-MM-YYYY.',
                'enddate.required' => 'Pole data zakończenia jest wymagane.',
                'enddate.date' => 'Pole data zakończenia musi być prawidłową datą.',
                'enddate.date_format' => 'Pole data zakończenia musi być w formacie DD-MM-YYYY.',
                'enddate.after_or_equal' => 'Pole data zakończenia musi być datą późniejszą lub równą dacie rozpoczęcia.',
            ]);

            $trip = new Trip();
            $trip->owner_id = Auth::user()->user_id;
            $trip->title = $request->title;
            $trip->start_date = $request->startdate;
            $trip->end_date = $request->enddate;
            $trip->save();

//            $data = [];
            $start_date = new DateTime($trip->start_date);
            $end_date = new DateTime($trip->end_date);

            while ($start_date <= $end_date) {
                $post = new Post();
                $post->trip_id = $trip->trip_id;
                $post->title = '   ZBIÓR WYDARZEŃ   ';
                $post->date = $start_date->format('Y-m-d');
                $post->day = ($start_date->diff(new DateTime($trip->start_date))->days) + 1 ;
                $post->save();
//                $data[] = $start_date->format('Y-m-d');
//                $data[] =  $start_date->diff(new DateTime($trip->start_date))->days;
                $start_date->add(new DateInterval('P1D'));
            }

             return redirect('/map/' . $trip->trip_id);
        } catch (\Exception $e) {
            return redirect()->back();        }

        }

    public function Gate($trip_id)
    {
        $user = null;
        if (Auth::user() != null) {
            $user = Auth::user();
        } else {
            return redirect('/login');
        }

        $trip = Trip::find($trip_id);

        if (!$trip) {
            return abort(404);
        }

        $sharedTrip = SharedTrip::where('trip_id', $trip->trip_id)->where('user_id', $user->user_id)->first();
        $publicTrip = PublicTrip::where('trip_id',$trip->trip_id)->first();
        if ($user->user_id === $trip->owner_id) {
            return true;
        } else if ($sharedTrip != null && $user->user_id === $sharedTrip->user_id && $sharedTrip->trip_id === $trip->trip_id) {
            return true;
        } else if($publicTrip != null){
            return true;
        }
        else {
            return abort(404);
        }
    }

    public function checkPermission()
    {
        $trip_id = session('trip_id');
        if (!$this->Gate($trip_id)) {
            return abort(404);
        }

        $trip = Trip::find($trip_id);
        $sharedusers = $trip->sharedusers;

        if (auth()->user()->user_id === $trip->owner_id) {
            return true;
        }
        $permission = $sharedusers->where('user_id', auth()->user()->user_id)->first()->pivot->permission_id;
        if ($permission === 1) {
            return true;
        } else if ($permission === 2) {
            return false;
        }
        return false;
    }

// POBIERANIE MARKERÓW DO MAPY INICJOWANE NA SAMYM STARCIE
    public function getMarkers($trip_id)
    {
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

    public function delMarker(Request $request)
    {
        $trip_id = session('trip_id');
        if (!$this->checkPermission()) {
            return response()->json(['message' => 'Nie masz uprawnień'], 400);
        }

        $mark_id = $request->input('mark_id');
        $trip = Trip::find($trip_id);
        $mark = $trip->marks->find($mark_id);

        // Usuń marker_id z powiązanych atrakcji
        foreach ($mark->attractions as $attraction) {
            $attraction->mark_id = null;
            $attraction->save();
        }

        // Usuń marker
        AttractionEvent::dispatch($trip_id);
        DelQueueEvent::dispatch($trip_id, "", $mark);
        MarkEvent::dispatch($trip_id, $mark);
        InfoUpdateEvent::dispatch($trip_id);
        $mark->delete();
        return response()->json(['message' => 'Zaktualizowano dane pomyślnie'], 200);
    }


    public function index($trip_id)
    {
        if (!$this->Gate($trip_id)) {
            return abort(404);
        }
        session(['trip_id' => $trip_id]);
        $trip = Trip::find($trip_id);
        $sharedusers = $trip->sharedusers;
        $markerData = $this->getMarkers($trip_id);
        $posts = $trip->posts->sortBy('date');
        $attractions = $trip->posts->pluck('attractions')->flatten()->sortBy('time_start');

        $publictrip = PublicTrip::where('trip_id',$trip_id)->first();

                    $attractionsWithTime = $attractions->filter(function ($attraction) {
                        return isset($attraction->time_start);
                    })->sortBy('time_start');

                    $attractionsWithoutTime = $attractions->reject(function ($attraction) {
                        return isset($attraction->time_start);
                    });

                    $attractions = $attractionsWithTime->merge($attractionsWithoutTime);

        if($publictrip != null){
            $permission = 2;
        } else if ($trip->owner_id == Auth::user()->user_id) {
            $permission = 1;
        } else {
            $permission = $sharedusers->where('user_id', auth()->user()->user_id)->first()->pivot->permission_id;
        }


        $totalAttractionCost = $trip->posts->flatMap(function ($post) {
            return $post->attractions;
        })->sum('cost');
        $totalFuelCost = 0;

        return view('trip_creator', compact('markerData', 'trip', 'posts', 'attractions', 'sharedusers', 'permission','totalFuelCost','publictrip', 'totalAttractionCost'));
    }

    public function addMarker(Request $request)
    {
        $trip_id = $request->trip_id;
        if (!$this->checkPermission()) {
            return response()->json(['message' => 'Nie masz uprawnień'], 400);
        }/*
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


        $existingMark = Mark::where('trip_id', $trip_id)
            ->where('latitude', $request->latitude)
            ->where('longitude', $request->longitude)
            ->first();

        if ($existingMark) {
            $existingMark->update([
                'trip_id' => $trip_id,
                'is_general' => 0,
                'name' => $request->name,
                'desc' => $request->desc,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
            TripEvent::dispatch($trip_id, "JEST TO FUNKCJA ADD MARKER", $existingMark);
            InfoUpdateEvent::dispatch($trip_id);

            return response()->json(['message' => 'Marker zaktualizowany pomyślnie'], 200);
        } else {
            // Marker nie istnieje, więc możesz utworzyć nowy
            $mark = new Mark();
            $mark->trip_id = $trip_id;
            $mark->is_general = 0;
            $mark->name = $request->name;
            $mark->desc = $request->desc;
            $mark->address = $request->address;
            $mark->latitude = $request->latitude;
            $mark->longitude = $request->longitude;
            $mark->save();
            TripEvent::dispatch($trip_id, "JEST TO FUNKCJA ADD MARKER", $mark);
            InfoUpdateEvent::dispatch($trip_id);

            return response()->json(['message' => 'Marker utworzony pomyślnie'], 201);
        }

//        return redirect()->back()->with('message', 'Operacja zakończona pomyślnie.'. $mark->trip_id);


    }

    public function editMarker(Request $request)
    {

        $trip_id = session('trip_id');
        if (!$this->checkPermission()) {
            return response()->json(['message' => 'Nie masz uprawnień'], 400);
        }


        // Odczytaj dane z żądania POST
        $id = $request->input('id');

        $name = $request->input('name');
        $desc = $request->input('desc');
        $address = $request->input('address');



        $mark = Mark::find($id);

        $mark->name = $name;
        $mark->desc = $desc;
        $mark->address = $address;

        $mark->save();


        EditMarkEvent::dispatch($trip_id, "JEST TO FUNKCJA EDIT MARKER", $mark);


        // Odpowiedź z sukcesem lub błędem
        return response()->json(['message' => 'Zaktualizowano dane pomyślnie' . $trip_id], 200);

    }

    public function addQueue(Request $request)
    {

        $trip_id = session('trip_id');
        if (!$this->checkPermission()) {
            return response()->json(['message' => 'Nie masz uprawnień'], 400);
        }

        // Odczytaj dane z żądania POST
        $mark_id = $request->input('productId');
        $queue = $request->input('cartId');
        $mark = Mark::find($mark_id);
        if ($mark) {
            $mark->queue = $queue;
            $mark->save();
            AddQueueEvent::dispatch($trip_id, "", $mark);
            InfoUpdateEvent::dispatch($trip_id);
            AttractionEvent::dispatch($trip_id);


        } else {
            // Obsłuż sytuację, gdy nie znaleziono obiektu Mark
            return response()->json(['error' => 'Nie znaleziono produktu o podanym ID'], 200);
        }

        return response()->json(['message' => 'Zaktualizowano dane pomyślnie' . $trip_id], 200);

    }

    public function delQueue(Request $request)
    {

        $trip_id = session('trip_id');
        if (!$this->checkPermission()) {
            return response()->json(['message' => 'Nie masz uprawnień'], 400);
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

        DelQueueEvent::dispatch($trip_id, "", $mark);
        InfoUpdateEvent::dispatch($trip_id);
        AttractionEvent::dispatch($trip_id);

        return response()->json(['message' => 'Zaktualizowano dane pomyślnie' . $trip_id], 200);
    }

    public function getWaypoints()
    {
        $trip_id = session('trip_id');
        $waypoints = Mark::where('trip_id', $trip_id)->get();

        if ($waypoints) {
            return response()->json(['waypoints' => $waypoints]);
        } else {
            return response()->json(['error' => 'Brak danych']);
        }
    }

    public function addPost(Request $request)
    {
        $trip_id = session('trip_id');
        if (!$this->checkPermission()) {
            return response()->json(['message' => 'Nie masz uprawnień'], 400);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:50',
            'date' => [
                'nullable',
                function ($attribute, $value, $fail) use ($trip_id) {
                    $trip = Trip::find($trip_id);

                    if ($value !== null) {
                        $startDate = Carbon::createFromFormat('Y-m-d', $trip->start_date);
                        $endDate = Carbon::createFromFormat('Y-m-d', $trip->end_date);
                        $inputDate = Carbon::createFromFormat('Y-m-d', $value);
                        if ($inputDate->lessThan($startDate) || $inputDate->greaterThan($endDate)) {
                            $fail('Nieprawidłowa data. Data musi być pomiędzy datą rozpoczęcia a datą zakończenia podróży.');
                        }
                    }
                },
            ],
        ], [
            'title.required' => 'Pole tytuł jest wymagane.',
            'title.max' => 'Pole tytuł może mieć maksymalnie 50 znaków.',
            'date' => 'Nieprawidłowa data. Data musi być pomiędzy datą rozpoczęcia a datą zakończenia podróży.',
            'date.nullable' => 'Pole data musi być datą lub wartością pustą.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->first();
            return response()->json(['errors' => $errors], 422);
        }



        $title = $request->input('title');
        $date = $request->input('date');
        $day = null;
        if ($date != null) {
            $start_date = Carbon::create(Trip::find($trip_id)->start_date);
            $day = $start_date->diffInDays(Carbon::create($request->input('date'))) + 1;
        }

        $existpost = Post::where('trip_id',$trip_id)->where('date',$date)->first();


        if( $existpost == null || $existpost->date == null){
            $postsWihtOutData = Post::where('trip_id',$trip_id)->where('date',null)->count();
            if($postsWihtOutData >= 3 && $request->input('date') == null){
                return response()->json(['errors' => 'Można stworzyć maksymalnie 3 zbiory bez określonej daty'], 422);
            }
            $post = new Post();
            $post->trip_id = $trip_id;
            $post->title = $title;
            $post->date = $date;
            $post->day = $day;

            if ($post->save()) {
                AddPostEvent::dispatch($trip_id, "", $post);
                return response()->json(['success' => 'Udało się utworzyć zbiór wydarzeń ! '], 200);

            } else {
                return response()->json(['error' => 'Nie udało się utworzyć Posta'], 400);
            }
        }else if($existpost != null){
            $existpost->title = $title;
            $existpost->update();
            if ($existpost->update()) {
                AddPostEvent::dispatch($trip_id, "", $existpost);
                return response()->json(['success' => 'Udało się utworzyć zbiór wydarzeń ! '], 200);
            } else {
                return response()->json(['error' => 'Nie udało się utworzyć Posta'], 400);
            }
        }

        return response()->json(['error' => 'Nie udało się utworzyć zbioru'], 400);
    }

    public function delPost(Request $request)
    {
        $trip_id = session('trip_id');
        if (!$this->checkPermission()) {
            return response()->json(['message' => 'Nie masz uprawnień'], 400);
        }

        $post_id = $request->input('postid');
        $post = Post::where('post_id', $post_id)->where('trip_id', $trip_id)->first();

        if ($post) {
            $post->attractions()->delete();
            $post->delete();
            DelPostEvent::dispatch($trip_id);
            InfoUpdateEvent::dispatch($trip_id);

            return response()->json(['success' => 'Udało się usunąć Post ! '], 200);
        } else {
            return response()->json(['error' => 'NIE Udało się usunąć Post ! '], 400);
        }


    }

    public function Attraction(Request $request)
    {
        $trip_id = session('trip_id');
        if (!$this->checkPermission()) {
            return response()->json(['message' => 'Nie masz uprawnień'], 400);
        }

        $attractions_category = CategorieAttraction::all();

        $markers = $this->getMarkers($trip_id);

        $post_id = $request->input('post_id');
        $post = Post::find($post_id);
        $att = null;
        return view('components.addAttractionComponents', compact('att', 'post', 'markers', 'trip_id','attractions_category'));
    }

    public function editAttraction(Request $request)
    {
        $trip_id = session('trip_id');
        if (!$this->checkPermission()) {
            return response()->json(['message' => 'Nie masz uprawnień'], 400);
        }
        $attractions_category = CategorieAttraction::all();

        $att_id = $request->input('attractionId');
        $att = Attraction::find($att_id);
        $markers = $this->getMarkers($trip_id);
        $post = $att->post;

        return view('components.addAttractionComponents', compact('att', 'post', 'markers', 'trip_id','attractions_category'));
    }

    public function addAttraction(Request $request)
    {
        $trip_id = session('trip_id');
        if (!$this->checkPermission()) {
            return response()->json(['message' => 'Nie masz uprawnień'], 400);
        }

        $trip = Trip::find($trip_id);

        $existingAttraction = Attraction::where('post_id', $request->input('post'))
            ->where('attraction_id', $request->input('attraction_id'))->first();

        if ($existingAttraction) {


            $duration = null;
            if ($request->input('start_time') != null && $request->input('end_time') != null) {
                $start_time = Carbon::createFromFormat('H:i', $request->input('start_time'));
                $end_time = Carbon::createFromFormat('H:i', $request->input('end_time'));
                $duration = ($start_time && $end_time) ? $end_time->diff($start_time) : null;
            }
            $existingAttraction->title = $request->input('name');
            $existingAttraction->desc = $request->input('desc');
            $existingAttraction->category_id = $request->input('category');
            $existingAttraction->cost = $request->input('price') ?? '0.00';
            $existingAttraction->time_start = $request->input('start_time');
            $existingAttraction->time_end = $request->input('end_time');
            if ($trip->marks->where('mark_id', $request->input('location'))->isNotEmpty()) {
                $existingAttraction->mark_id = $request->input('location');
            }else{
                $existingAttraction->mark_id =  null;
            }

            $existingAttraction->duration = ($duration) ? $duration->format('%H:%I') : null;


            $existingAttraction->save();
            AttractionEvent::dispatch($trip_id);
            InfoUpdateEvent::dispatch($trip_id);

            return redirect("/map/$trip_id?tab=posts")->with('scrollTo', $existingAttraction->attraction_id);
        } else {
            $duration = null;
            if ($request->input('start_time') != null && $request->input('end_time') != null) {
                $start_time = Carbon::createFromFormat('H:i', $request->input('start_time'));
                $end_time = Carbon::createFromFormat('H:i', $request->input('end_time'));
                $duration = ($start_time && $end_time) ? $end_time->diff($start_time) : null;
            }



            $attraction = new Attraction();
            $attraction->post_id = $request->input('post');
            $attraction->title = $request->input('name');
            $attraction->desc = $request->input('desc');
            $attraction->category_id = $request->input('category');
            $attraction->cost = $request->input('price') ?? '0.00';
            $attraction->time_start = $request->input('start_time');
            $attraction->time_end = $request->input('end_time');
            if ($trip->marks->where('mark_id', $request->input('location'))->isNotEmpty()) {
                $attraction->mark_id = $request->input('location');
            }else{
                $attraction->mark_id =  null;
            }

            $attraction->setDurationAttribute($duration);
            $attraction->save();
            AttractionEvent::dispatch($trip_id);
            InfoUpdateEvent::dispatch($trip_id);

            return redirect("/map/$trip_id?tab=posts")->with('scrollTo', $attraction->attraction_id);


        }
    }

    public function delAttraction(Request $request)
    {
        $trip_id = session('trip_id');
        if (!$this->checkPermission()) {
            return response()->json(['message' => 'Nie masz uprawnień'], 400);
        }

        if ($request) {
            $att_id = $request->input('attractionId');
            $att = Attraction::find($att_id)->delete();
            AttractionEvent::dispatch($trip_id);
            InfoUpdateEvent::dispatch($trip_id);
        } else {
            return response()->json(['error' => 'Nie znaleziono produktu o podanym ID'], 200);
        }
        return response()->json(['message' => 'Zaktualizowano dane pomyślnie'], 200);
    }

    public function moveAttraction(Request $request)
    {
        $trip_id = session('trip_id');
        if (!$this->checkPermission()) {
            return response()->json(['message' => 'Nie masz uprawnień'], 400);
        }
        $att_id = $request->input('attraction_id');
        $post_id = $request->input('post_id');
        $attraction = Attraction::find($att_id);
        $post = Post::find($post_id);

        $attraction->post_id = $post->post_id;
        $attraction->save();

        AttractionEvent::dispatch($trip_id);

        return response()->json(['message' => 'Zaktualizowano dane pomyślnie'], 200);
    }

    public function addTactic(Request $request)
    {
        $trip_id = session('trip_id');
        if (!$this->checkPermission()) {
            return response()->json(['message' => 'Nie masz uprawnień'], 400);
        }

        try {
            $validatedData = $request->validate([
                'email' => 'required|email',
                'permission' => 'required|in:1,2',
            ], [
                'email.required' => 'Pole email jest wymagane.',
                'email.email' => 'Nieprawidłowy format adresu email.',
                'permission.required' => 'Pole uprawnień jest wymagane.',
                'permission.in' => 'Wartość pola uprawnień jest nieprawidłowy.',
            ]);

            $invited_user = User::where('email', $validatedData['email'])->firstOrFail();
            $permission = $validatedData['permission'];

            $trip = Trip::where('trip_id', $trip_id)->where('owner_id', Auth::user()->user_id)->first();
            if ($trip === null) {
                return response()->json(['error' => 'Nie ma takiej podróży!'], 400);
            }

            if ($invited_user->user_id === $trip->owner_id) {
                return response()->json(['error' => 'To Twój wyjazd przyjacielu!'], 400);
            }

            $existingInvite = UserInvite::where('user_id', $invited_user->user_id)->where('invited_trip', $trip_id)->first();
            if ($existingInvite !== null) {
                return response()->json(['error' => 'Zaproszenie już istnieje'], 400);
            }

            // Check if the user is already added to the trip
            $shared = SharedTrip::where('user_id', $invited_user->user_id)->where('trip_id', $trip_id)->first();
            if ($shared !== null) {
                return response()->json(['error' => 'Użytkownik został już dodany!'], 400);
            }

            $invite = new UserInvite();
            $invite->user_id = $invited_user->user_id;
            $invite->invited_by = Auth::user()->user_id;
            $invite->invited_trip = $trip_id;
            $invite->permission = $permission;
            $invite->save();

            return response()->json(['success' => 'Wysłano zaproszenie użytkownikowi ' . $validatedData['email']], 200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Nie znaleziono użytkownika'], 400);
        }
    }

    public function delTactic(Request $request)
    {
        $trip_id = session('trip_id');
        if (!$this->Gate($trip_id)) {
            return abort(404);
        }

        $user_email = $request->input('email');

        $user_id = User::where('email', $user_email)->first()->user_id;
        $shared = SharedTrip::where('trip_id', $trip_id)->where('user_id', $user_id)->first();
        $shared->delete();
        return response()->json(['success' => 'Pomyślnie usunięto użytkownika !'], 200);
    }

    public function SavePermissions(Request $request)
    {
        $trip_id = session('trip_id');
        if (!$this->checkPermission()) {
            return response()->json(['message' => 'Nie masz uprawnień'], 400);
        }

        $user_email = $request->input('email');
        $permission_id = $request->input('permission_id');
        $user_id = User::where('email', $user_email)->first()->user_id;
        $shared = SharedTrip::where('trip_id', $trip_id)->where('user_id', $user_id)->first();

        if ($shared->permission_id == $permission_id) {
            return response()->json(['success' => 'Użytkownik posiada już takie uprawnienia'], 200);
        }

        $shared->permission_id = $permission_id;
        $shared->save();

        return response()->json(['success' => 'Pomyślnie zmieniono uprawnienia'], 200);

    }

    public function AddRouteData(Request $request)
    {
        $trip_id = session('trip_id');
        if (!$this->checkPermission()) {
            return response()->json(['message' => 'Nie masz uprawnień'], 400);
        }

        $trip = Trip::find($trip_id);

        $trip->travel_time = $request->input('travel_time');
        $trip->distance = $request->input('distance');
        $trip->avg_speed = $request->input('avg_speed');
        $trip->fuel_consumed = $request->input('fuel_consumed');
        $trip->save();

        InfoUpdateEvent::dispatch($trip_id);

        return response()->json(['success' => 'Pomyślnie zmieniono uprawnienia'], 200);
    }

    public function AddVehicle(Request $request)
    {
        $trip_id = session('trip_id');
        if (!$this->checkPermission()) {
            return response()->json(['message' => 'Nie masz uprawnień'], 400);
        }

        $validatedData = $request->validate([
            'vehicle_name' => 'required|string|max:30',
            'consumption' => 'required|numeric|max:99.9',
            'fuel' => [
                'required',
                Rule::in(['benzyna', 'diesel', 'gaz']),
            ],
        ], [
            'vehicle_name.required' => 'Pole Nazwa pojazdu jest wymagane.',
            'vehicle_name.string' => 'Pole Nazwa pojazdu musi być ciągiem znaków.',
            'vehicle_name.max' => 'Pole Nazwa pojazdu nie może przekraczać 30 znaków.',
            'consumption.required' => 'Pole Zużycie paliwa jest wymagane.',
            'consumption.numeric' => 'Pole Zużycie paliwa musi być liczbą.',
            'consumption.max' => 'Pole Zużycie paliwa nie może przekraczać 99.9.',
            'fuel.required' => 'Pole Rodzaj paliwa jest wymagane.',
            'fuel.in' => 'Nieprawidłowy rodzaj paliwa. Wybierz spośród: benzyna, diesel, gaz.',
        ]);

        $veh = Vehicle::firstOrNew(
            [
                'trip_id' => $trip_id,
                'name' => $validatedData['vehicle_name'],
            ]
        );

        $veh->consumption = $validatedData['consumption'];
        $veh->fuel = $validatedData['fuel'];
        $veh->save();

        if ($veh->wasRecentlyCreated) {
            InfoUpdateEvent::dispatch($trip_id);
            return response()->json(['success' => 'Pomyślnie dodano nowy pojazd'], 200);
        } else {
            InfoUpdateEvent::dispatch($trip_id);
            return response()->json(['success' => 'Pomyślnie zaktualizowano istniejący pojazd'], 200);
        }
    }

    public function DelVehicle(Request $request)
    {
        $trip_id = session('trip_id');
        if (!$this->checkPermission()) {
            return response()->json(['message' => 'Nie masz uprawnień'], 400);
        }
        $trip = Trip::find($trip_id);

        $vehicle = $trip->vehicles()->find($request->input('vehicle_id'));

        if ($vehicle) {
            $vehicle->delete();
            InfoUpdateEvent::dispatch($trip_id);
            return response()->json(['success' => 'Pomyślnie usunięto pojazd'], 200);
        } else {
            InfoUpdateEvent::dispatch($trip_id);
            return response()->json(['error' => 'Nie znaleziono pojazdu'], 404);
        }

    }
    public function FuelPrice(Request $request)
    {
        $trip_id = session('trip_id');
        if (!$this->checkPermission()) {
            return response()->json(['message' => 'Nie masz uprawnień'], 400);
        }

        $trip = Trip::find($trip_id);

        $validatedData = $request->validate([
            'diesel' => 'numeric|min:0|max:99.99',
            'petrol' => 'numeric|min:0|max:99.99',
            'gas' => 'numeric|min:0|max:99.99',
        ], [
            'diesel.numeric' => 'Cena za diesel musi być liczbą.',
            'diesel.min' => 'Cena za diesel nie może być mniejsza niż :min.',
            'diesel.max' => 'Cena za diesel nie może być większa niż :max.',

            'petrol.numeric' => 'Cena za benzynę musi być liczbą.',
            'petrol.min' => 'Cena za benzynę nie może być mniejsza niż :min.',
            'petrol.max' => 'Cena za benzynę nie może być większa niż :max.',

            'gas.numeric' => 'Cena za gaz musi być liczbą.',
            'gas.min' => 'Cena za gaz nie może być mniejsza niż :min.',
            'gas.max' => 'Cena za gaz nie może być większa niż :max.',
        ]);

        if ($trip->diesel_cost != $validatedData['diesel'] ||
            $trip->petrol_cost != $validatedData['petrol'] ||
            $trip->gas_cost != $validatedData['gas']) {

            $trip->update([
                'diesel_cost' => $validatedData['diesel'],
                'petrol_cost' => $validatedData['petrol'],
                'gas_cost' => $validatedData['gas'],
            ]);

            fuelUpdateEvent::dispatch($trip_id);
            InfoUpdateEvent::dispatch($trip_id);
            return response()->json(['success' => 'Ceny zostały zaktualizowane pomyślnie'], 200);
        } else {
            fuelUpdateEvent::dispatch($trip_id);
            InfoUpdateEvent::dispatch($trip_id);
            return response()->json(['message' => 'Ceny są takie same, nie dokonano aktualizacji'], 200);
        }
    }


    public function PersonNumber(Request $request){
        $trip_id = session('trip_id');
        if (!$this->checkPermission()) {
            return response()->json(['message' => 'Nie masz uprawnień'], 400);
        }
        $validatedData = $request->validate([
            'persons' => 'numeric|min:1|max:99|required',
        ], [
            'persons.numeric' => 'To musi być liczba!',
            'persons.min' => 'Liczba nie może być mniejsza niż :min.',
            'persons.max' => 'Liczba nie może być większa niż :max.',
            'persons.required' => 'Musisz podać jakąś liczbę' ,
        ]);
        $trip = Trip::find($trip_id);
        $trip->persons = $validatedData['persons'];
        $trip->update();
        InfoUpdateEvent::dispatch($trip_id);
        PersonsUpdateEvent::dispatch($trip_id);

        return response()->json(['success' => 'Ustwiono liczbę taktyków'], 200);
    }

function ChangeTripSetting(Request $request){
    $trip_id = session('trip_id');
    if (!$this->checkPermission()) {
        return response()->json(['message' => 'Nie masz uprawnień'], 400);
    }
    $trip = Trip::find($trip_id);

    $validatedData = $request->validate([
        'title' => 'min:3|max:30',
        'startdate' => [
            'date',
            'date_format:Y-m-d',
            function ($attribute, $value, $fail) use ($trip) {
                if ($value < $trip->start_date && $value < now()->format('Y-m-d')) {
                    $fail('Data startu nie może być wcześniejsza niż data początkowa wycieczki oraz data dzisiejsza ');
                }
            },
        ],
        'enddate' => 'date|date_format:Y-m-d|after_or_equal:startdate',
    ], [
        'title.max' => 'W tytule maksymalnie 30 znaków!',
        'title.min' => 'W tytule minimalnie 3 znaki!',
        'startdate.date' => 'Pole Data rozpoczęcia musi być prawidłową datą.',
        'startdate.date_format' => 'Pole data rozpoczęcia musi być w formacie DD-MM-RRRR.',
        'enddate.date' => 'Pole data zakończenia musi być prawidłową datą.',
        'enddate.date_format' => 'Pole data zakończenia musi być w formacie DD-MM-RRRR.',
        'enddate.after_or_equal' => 'Pole data zakończenia musi być datą późniejszą lub równą dacie rozpoczęcia.',
    ]);
//    return response()->json(['success' => $trip->title. ' ' . $validatedData['title'] ], 200);

    if ($trip->title != $validatedData['title']) {
        $trip->title = $validatedData['title'];
        $trip->save();

    }

    if ($trip->start_date != $validatedData['startdate'] || $trip->end_date != $validatedData['enddate']) {
        $trip->start_date = $validatedData['startdate'];
        $trip->end_date = $validatedData['enddate'];
            if($trip->update()) {
                          foreach ($trip->posts as $post) {

                    $newDate = new DateTime($trip->start_date);
                    $end_date = new DateTime($trip->end_date);

                    $dayToAdd = max(0, $post->day - 1);

                    $newDate->add(new DateInterval('P' . $dayToAdd . 'D'));

                    if ($newDate <= $end_date && $post->date != null) {
                        $post->date = $newDate;
                    } else if ($newDate > $end_date) {
                        $post->date = null;
                        $post->day = null;
                    }
                    $post->update();
                    }
        }
    }


    settingsUpdateEvent::dispatch($trip_id);
    DelPostEvent::dispatch($trip_id);

    return response()->json(['success' => 'Zmieniono ustawienia' ], 200);

}

}
