<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Роль список', ['only' => ['getRolesPagination', 'getAllRoles']]);
        $this->middleware('can:Роль створити', ['only' => ['addRole']]);
        $this->middleware('can:Роль редагувати', ['only' => ['editRolePermissions']]);
        $this->middleware('can:Роль видалити', ['only' => ['deleteRole']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function getRolesPagination(): JsonResponse
    {
        $roles = (new Role())->newQuery();
        $roles->latest();
        $roles->with('permissions');
        $roles = $roles->paginate(10)->onEachSide(2)->appends(request()->query());

        return response()->json([
            'roles' => $roles,
        ], 201);
    }

    /**
     * Get all permissions.
     *
     * @return JsonResponse
     */
    public function getAllRoles(): JsonResponse
    {
        $roles = (new Role())->newQuery();
        $roles = $roles->get();

        return response()->json([
            'roles' => $roles,
        ], 201);
    }

    /**
     * Add role.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function addRole(Request $request): JsonResponse
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255', 'unique:roles'],
                'permissions' => ['required', 'array'],
            ],
            [
                'name.required' => 'Поле не може бути порожнім',
                'name.max:255' => 'Максимально допустима кількість символів 255',
                'name.unique' => 'Таке ім\'я вже існує',
                'permissions.required' => 'Виберіть хоча б один дозвіл',
            ]
        );

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return response()->json([
            'message' => 'Роль успішно створена',
            'role' => $role,
        ], 201);
    }

    /**
     * Edit role permissions.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function editRolePermissions(Request $request): JsonResponse
    {
        $role = Role::where('id', $request->id)->first();

        $role->syncPermissions($request->permissions);

        return response()->json([
            'message' => 'Дозволи ролі успішно змінені',
        ], 201);
    }

    /**
     * Delete role.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function deleteRole(int $id): JsonResponse
    {
        $role = Role::where('id', $id)->first();
        $role->delete();
        $role->syncPermissions([]);

        return response()->json([
            'message' => 'Роль успішно видалено',
        ], 201);
    }
}
