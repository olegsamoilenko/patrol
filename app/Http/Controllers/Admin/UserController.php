<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Користувач статистика', ['only' => ['getUserStatistics']]);
        $this->middleware('can:Користувач список', ['only' => ['getUserPagination']]);
        $this->middleware('can:Користувач активувати', ['only' => ['activateUser']]);
        $this->middleware('can:Користувач редагувати', ['only' => ['editUser']]);
        $this->middleware('can:Користувач видалити', ['only' => ['deleteUser']]);
    }

    /**
     * Get User Statistics.
     *
     * @return JsonResponse
     */
    public function getUserStatistics(): JsonResponse
    {
        $users = User::all();
        $usersCount = $users->count();
        $notActivatedUsers = User::where('is_activated', 'Ні')->get();
        $notActivatedUsersCount = $notActivatedUsers->count();
        $usersWithRoleAdmin = User::role('Адміністратор')->get();
        $usersWithRoleAdminCount = $usersWithRoleAdmin->count();
        $usersWithRoleUser = User::role('Користувач')->get();
        $usersWithRoleUserCount = $usersWithRoleUser->count();

        return response()->json([
            'usersCount' => $usersCount,
            'notActivatedUsersCount' => $notActivatedUsersCount,
            'usersWithRoleAdminCount' => $usersWithRoleAdminCount,
            'usersWithRoleUserCount' => $usersWithRoleUserCount,
        ], 201);
    }

    /**
     * Get User Pagination.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getUserPagination(Request $request): JsonResponse
    {
//        TODO: Не работает фильтрация по ролям и поиск по имени пользователя одновременно
        $users = User::when($request->role, static function ($query, $role) {
//            $role = Role::where('slug', $role)->first();
//            return $role->users();
//            foreach ($roles as $role) {

            $query->whereHas('roles', function ($query) use ($role) {
                return $query->where('name', $role);
            });
//            }
        })
            ->when($request->search, static function ($query, $search) {
                return $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('surname', 'LIKE', "%{$search}%")
                ;
            })
            ->with('roles')->with('permissions')->orderBy($request->sortBy, $request->sortDirection)->paginate(10);

        return response()->json([
            'users' => $users,
        ], 201);
    }

    /**
     * Activate user.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function activateUser(int $id): JsonResponse
    {
        $user = User::where('id', $id)->first();
        $user->is_activated = 'Так';
        $user->save();

        return response()->json([
            'message' => 'Користувач успішно активований',
            'user' => $user,
        ], 201);
    }

    /**
     * Edit User.
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
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
                'roles.required' => 'У користувача обов\'язково повинна бути хоча б одна роль',
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
     *
     * @param int $id
     * @return JsonResponse
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
