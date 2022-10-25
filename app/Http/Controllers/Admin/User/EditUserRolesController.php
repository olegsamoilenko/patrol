<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EditUserRolesController extends Controller
{
    public function editUserRoles(Request $request): JsonResponse
    {
        $user = User::where('id', $request->id)->first();

        $user->roles()->sync($request->roles);

        return response()->json([
            'message' => 'Роль Користувача успішно змінена',
        ], 201);
    }
}
