<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\Role;
use App\Models\User;
use App\Providers\RouteServiceProvider;
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
    | validation and creation. By default, this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     */
    protected string $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make(
            $data,
            [
                'name' => ['required', 'string', 'max:255'],
                'surname' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255', 'unique:users'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'is_headquarters' => ['boolean'],
                'battalion_id' => ['required_if:is_headquarters,false'],
                'company_id' => ['required_if:is_headquarters,false'],
                'platoon_id' => ['required_if:is_headquarters,false'],
            ],
            [
                'name.required' => 'Поле "Ім\'я" обов\'язкове для заповнення',
                'name.max' => 'Поле "Ім\'я" не повинно перевищувати 255 символів',
                'surname.required' => 'Поле "Прізвище" обов\'язкове для заповнення',
                'surname.max' => 'Поле "Прізвище" не повинно перевищувати 255 символів',
                'phone.required' => 'Поле "Телефон" обов\'язкове для заповнення',
                'phone.unique' => 'Користувач із таким телефоном вже зареєстрований',
                'email.required' => 'Поле "Пошта" обов\'язкове для заповнення',
                'email.email' => 'Поле "Пошта" має бути валідною email адресою',
                'email.max' => 'Поле "Пошта" не повинно перевищувати 255 символів',
                'email.unique' => 'Користувач з такою електронною адресою вже зареєстрований',
                'password.required' => 'Поле "Пароль" обов\'язкове для заповнення',
                'password.min' => 'Поле "Пароль" має бути не менше 8 символів',
                'password.confirmed' => 'Поле "Пароль" має збігатися з полем "Підтвердження пароля"',
                'battalion_id.required_if' => 'Виберіть Батальон',
                'company_id.required_if' => 'Виберіть Роту',
                'platoon_id.required_if' => 'Виберіть Взвод',
            ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data): User
    {
        $formation = Formation::first();
        $user = User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'phone' => $data['phone'],
            'formation_id' => $formation->id,
            'battalion' => $data['battalion'] ?? null,
            'company' => $data['company'] ?? null,
            'platoon' => $data['platoon'] ?? null,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $role = Role::where('name', 'Користувач')->first();

        $user->roles()->attach($role->id);

        return $user;
    }
}
