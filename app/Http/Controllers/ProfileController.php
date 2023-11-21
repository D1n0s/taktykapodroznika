<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Trip;
use App\Models\SharedTrip;
use App\Models\UserInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class ProfileController extends Controller{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('profile');
    }

    public function mytrips(){


        $trips = Trip::where('owner_id',Auth::user()->user_id)->paginate(5);

        return view('myTrips', compact('trips'));
    }
    public function sharedtrips(){

        $trips = SharedTrip::where('user_id', auth()->user()->user_id)->with('trip')->paginate(4);

        return view('sharedTrips', ['trips' => $trips]);
    }



    public function update(Request $request){
        $userId = $request->user_id;
        $user = User::find($userId);



    $validator = Validator::make($request->all(), [
        'name' => 'nullable|string|max:255',
        'surname' => 'nullable|string|max:255',
        'email' => 'email|unique:users,email,' . $userId . ',user_id',
        'phone' => 'nullable|regex:/^[0-9]{9}$/'
    ]);



        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try{
            $user->name = $request->name;
            $user->surname = $request->surname;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->save();

        }catch(\Exception $e){

            return redirect()->back()->with('error', 'Wystąpił błąd podczas zapisu użytkownika.'. $e->getMessage());
        }
        return redirect()->route('profile')->with('success', 'Zmiany zostały zapisane.');

    }
    public function editshow()
    {
       return view('profile_edit');
    }

    public function cancel()
    {
        return redirect()->route('profile')->with('info', 'Operacja anulowana');
        return view('profile');
    }

    public function  AcceptInvite(Request $request){

        $invite_id = $request->input('invite_id');
        $invite = UserInvite::find($request->input('invite_id'));

        if($invite->user_id == Auth::user()->user_id){
            $shareTrip = new SharedTrip();
            $shareTrip->trip_id =  $invite->invitedTrip->trip_id;
            $shareTrip->user_id = Auth::user()->user_id;
            $shareTrip->permission_id =  $invite->permission;

            if($shareTrip->save()){
                $invite->delete();
                return response()->json(['success' =>  'Zaproszenie zaakceptowane','id' => $invite_id], 200 );
            }

        }
        return response()->json(['succcess' =>  'działa'], 200 );

    }
    public function  DeclineInvite(Request $request){

        $invite_id = $request->input('invite_id');
        $invite = UserInvite::find($request->input('invite_id'));

        if($invite->user_id == Auth::user()->user_id){
            $invite->delete();
            return response()->json(['success' =>  'Usunięto zaproszenie','id' => $invite_id], 200 );
        }

    }
}
