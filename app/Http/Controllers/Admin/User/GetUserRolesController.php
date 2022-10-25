<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\JsonResponse;

class GetUserRolesController extends Controller
{
    /**
     * Get User Roles.
     */
    public function getUserRoles(): JsonResponse
    {
        $roles = Role::orderBy('id')->get();

        return response()->json([
            'roles' => $roles,
        ], 201);
    }
}
