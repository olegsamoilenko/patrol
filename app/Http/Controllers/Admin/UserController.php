<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public User $user;

    public function __construct()
    {
        $this->user = new User();
        $this->middleware('can:Користувач статистика', ['only' => ['getUserStatistics']]);
        $this->middleware('can:Користувач список', ['only' => ['getUserPagination']]);
        $this->middleware('can:Користувач активувати', ['only' => ['activateUser']]);
        $this->middleware('can:Користувач редагувати', ['only' => ['editUser']]);
        $this->middleware('can:Користувач видалити', ['only' => ['deleteUser']]);
    }

    /**
     * Get User Statistics.
     */
    public function getUserStatistics(): JsonResponse
    {
        return response()->json([
            'usersCount' => $this->user->getCount(),
            'notActivatedUsersCount' => $this->user->getNotActivatedCount(),
            'usersWithRoleAdminCount' => $this->user->getRoleAdminCount(),
            'usersWithRoleUserCount' => $this->user->getRoleUserCount(),
        ], 201);
    }

    /**
     * Get User Pagination.
     */
    public function getUserPagination(Request $request): JsonResponse
    {
        $users = User::when($request->role, static function ($query, $role) {
            $query->role($role);
        })
            ->when($request->search, static function ($query, $search) {
                return $query->search($search);
            })
            ->with('roles')->with('permissions')->orderBy($request->sortBy, $request->sortDirection)->paginate(10);

        return response()->json([
            'users' => $users,
        ], 201);
    }

    /**
     * Activate user.
     */
    public function activateUser(int $id, Request $request): JsonResponse
    {
        $user = User::where('id', $id)->first();
        $user->is_activated = 'Так';
        $user->activated_by = [
            'user_id' => $request->user()->id,
            'user_name' => $request->user()->name,
            'user_surname' => $request->user()->surname,
            'user_phone' => $request->user()->phone,
        ];
        $user->save();

        return response()->json([
            'message' => 'Користувач успішно активований',
            'user' => $user,
        ], 201);
    }

    /**
     * Edit User.
     */
    public function editUser(int $id, Request $request): JsonResponse
    {
        $user = User::find($id);
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'phone' => 'required|string|max:255|unique:users,phone,'.$user->id,
                'email' => 'required|email|unique:users,email,'.$user->id,
                'roles' => 'required|array',
            ],
            [
                'name.required' => "Поле Ім'я обов'язкове для заповнення",
                'name.max' => "Поле Ім'я не повинно перевищувати 255 символів",
                'surname.required' => "Поле Прізвище обов'язкове для заповнення",
                'surname.max' => "Поле Прізвище не повинно перевищувати 255 символів",
                'phone.required' => "'Поле Телефон обов'язкове для заповнення'",
                'phone.unique' => 'Користувач із таким телефоном вже зареєстрований',
                'email.required' => "'Поле Пошта обов'язкове для заповнення'",
                'email.email' => 'Поле Пошта має бути валідною email адресою',
                'email.max' => 'Поле Пошта не повинно перевищувати 255 символів',
                'email.unique' => 'Користувач з такою електронною адресою вже зареєстрований',
                'roles.required' => "У користувача обов'язково повинна бути хоча б одна роль",
            ]
        );

        $user->update($request->all());

        if (count($request->roles) && is_int($request->roles[0])) {
            $user->syncRoles($request->roles);
        }

        return response()->json([
            'message' => 'Користувача успішно відредаговано',
            'user' => $user,
        ], 201);
    }

    /**
     * Delete User.
     */
    public function deleteUser(int $id): JsonResponse
    {
        $user = User::where('id', $id)->first();
        $user->delete();
        $user->roles()->detach();

        return response()->json([
            'message' => 'Користувача успішно видалено',
        ], 201);
    }
}
