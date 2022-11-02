<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:Дозвіл список', ['only' => ['getPermissionsPagination', 'getAllPermissions']]);
        $this->middleware('can:Дозвіл створити', ['only' => ['addPermission']]);
        $this->middleware('can:Дозвіл редагувати', ['only' => ['editPermissionRoles']]);
        $this->middleware('can:Дозвіл видалити', ['only' => ['deletePermission']]);
    }

    /**
     * Get a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getPermissionsPagination(Request $request): JsonResponse
    {
        $permissions = (new Permission())->newQuery();
        $permissions->when($request->search, static function ($query, $search) {
            return $query->where('name', 'LIKE', "%{$search}%");
        });
        $permissions->with('roles');
        $permissions->orderBy($request->sortBy, $request->sortDirection);
        $permissions = $permissions->paginate($request->perPage)->onEachSide(2)->appends(request()->query());

        return response()->json([
            'permissions' => $permissions,
        ], 201);
    }

    /**
     * Get all permissions.
     *
     * @return JsonResponse
     */
    public function getAllPermissions(): JsonResponse
    {
        $permissions = (new Permission())->newQuery();
        $permissions = $permissions->get();

        return response()->json([
            'permissions' => $permissions,
        ], 201);
    }

    /**
     * Add permission.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function addPermission(Request $request): JsonResponse
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255', 'unique:permissions'],
                'roles' => ['required', 'array'],
            ],
            [
                'name.required' => 'Поле не може бути порожнім',
                'name.max:255' => 'Максимально допустима кількість символів 255',
                'name.unique' => 'Таке ім\'я вже існує',
                'permissions.required' => 'Виберіть хоча б один дозвіл',
            ]
        );

        $permission = Permission::create(['name' => $request->name]);
        $permission->syncRoles($request->roles);

        return response()->json([
            'message' => 'Дозвіл успішно створено',
            'permission' => $permission,
        ], 201);
    }

    /**
     * Edit permission roles.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function editPermissionRoles(Request $request): JsonResponse
    {
        $permission = Permission::where('id', $request->id)->first();

        $permission->syncRoles($request->roles);

        return response()->json([
            'message' => 'Ролі дозволів успішно змінені',
        ], 201);
    }

    /**
     * Delete permission.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function deletePermission(int $id): JsonResponse
    {
        $permission = Permission::where('id', $id)->first();
        $permission->delete();
        $permission->syncRoles([]);

        return response()->json([
            'message' => 'Дозвіл успішно видалено',
        ], 201);
    }
}
