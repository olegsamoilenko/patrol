<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Validate the user login request.
     *
     * @throws ValidationException
     */
    protected function validateLogin(Request $request): void
    {
        $request->validate(
            [
                $this->username() => 'required|string',
                'password' => 'required|string',
            ],
            [
                'email.required' => 'Поле "Електронна пошта" обов\'язкове для заповнення',
                'password.required' => 'Поле "Пароль" обов\'язкове для заповнення',
                'email.credentials' => 'Невірний логін або пароль',
            ]
        );
    }

    protected function sendFailedLoginResponse(): void
    {
        throw ValidationException::withMessages([
            'password' => 'Невірний логін або пароль!',
        ]);
    }
}
