<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'login' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'login.required' => 'Pole nazwy użytkownika jest wymagane.',
            'login.string' => 'Nazwa użytkownika musi być ciągiem znaków.',
            'login.max' => 'Nazwa użytkownika nie może być dłuższa niż 255 znaków.',
            'login.unique' => 'Ta nazwa użytkownika jest już zajęta.',

            'email.required' => 'Pole adresu email jest wymagane.',
            'email.string' => 'Adres email musi być ciągiem znaków.',
            'email.email' => 'Podany adres email jest nieprawidłowy.',
            'email.max' => 'Adres email nie może być dłuższy niż 255 znaków.',
            'email.unique' => 'Ten adres email jest już zarejestrowany.',

            'password.required' => 'Pole hasła jest wymagane.',
            'password.string' => 'Hasło musi być ciągiem znaków.',
            'password.min' => 'Hasło musi mieć co najmniej 8 znaków.',
            'password.confirmed' => 'Potwierdzenie hasła nie pasuje do hasła.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'login' => $data['login'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
