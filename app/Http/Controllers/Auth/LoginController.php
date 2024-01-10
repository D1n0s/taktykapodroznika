<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => ['Błędne dane logowania. Spróbuj ponownie.'],
            'password' => ['Nieprawidłowe hasło. Spróbuj ponownie.'],
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only($this->username(), 'password');

        if ($this->guard()->attempt($credentials, $request->filled('remember'))) {
            return $this->sendLoginResponse($request);
        }

        $this->sendFailedLoginResponse($request);
    }

    public function username()
    {
        return 'email';
    }
}
