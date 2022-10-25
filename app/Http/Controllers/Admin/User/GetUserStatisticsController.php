<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class GetUserStatisticsController extends Controller
{
    /**
     * Get User Statistics.
     */
    public function getUserStatistics(): JsonResponse
    {
        $users = User::all();
        $roleNone = Role::where('slug', 'none')->first();
        $roleAdmin = Role::where('slug', 'admin')->first();
        $roleUser = Role::where('slug', 'user')->first();

        $numberOfUsers = $users->count();
        $usersWithRoleNone = $roleNone->users()->get()->count();
        $usersWithRoleAdmin = $roleAdmin->users()->get()->count();
        $usersWithRoleUser = $roleUser->users()->get()->count();

        return response()->json([
            'numberOfUsers' => $numberOfUsers,
            'usersWithRoleNone' => $usersWithRoleNone,
            'usersWithRoleAdmin' => $usersWithRoleAdmin,
            'usersWithRoleUser' => $usersWithRoleUser,
        ], 201);
    }
}
