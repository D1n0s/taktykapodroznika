<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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

    public function update(Request $request){
        $userId = $request->user_id;
        $user = User::find($userId );



    $validator = Validator::make($request->all(), [
        'name' => 'nullable|string|max:255',
        'surname' => 'nullable|string|max:255',
        'email' => 'email|unique:users,email,' . $userId . ',id',
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



    /**
     * Remove the specified resource from storage.
     */
    public function cancel()
    {
        return redirect()->route('profile')->with('info', 'Operacja anulowana');
        return view('profile');
    }
}
