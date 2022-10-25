<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivateUserController extends Controller
{
    /**
     * Activate user.
     */
    public function activateUser($id): JsonResponse
    {
        $user = User::where('id', $id)->first();
        $role = Role::where('slug', 'user')->first();

        $user->roles()->sync([$role->id]);

        return response()->json([
            'message' => 'Користувач успішно активований',
            'user' => $user,
        ], 201);
    }
}
