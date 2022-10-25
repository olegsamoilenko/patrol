<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetUserPaginationController extends Controller
{
    /**
     * Paginate products.
     */
    public function getUserPagination(Request $request): JsonResponse
    {
//        TODO: Не работает фильтрация по ролям и поиск по имени пользователя одновременно
        $users = User::when($request->role, static function ($query, $role) {
//            $role = Role::where('slug', $role)->first();
//            return $role->users();
//            foreach ($roles as $role) {
                $query->whereHas('roles', function ($query) use ($role) {
                    return $query->where('slug', $role);
                });
//            }
        })
            ->when($request->search, static function ($query, $search) {
                return $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('surname', 'LIKE', "%{$search}%");
            })
            ->with('roles')->orderBy($request->sortBy, $request->sortDirection)->paginate(10);

        return response()->json([
            'users' => $users,
        ], 201);
    }
}
