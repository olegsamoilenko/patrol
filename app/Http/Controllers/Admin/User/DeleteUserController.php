<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeleteUserController extends Controller
{
    public function deleteUser($id): JsonResponse
    {
        $user = User::where('id', $id)->first();
        $user->delete();
        $user->roles()->detach();

        return response()->json([
            'message' => 'Користувача успішно видалено',
        ], 201);
    }
}
