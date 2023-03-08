<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function getAllChats()
    {
        $chats = Chat::with('user')->get();

        return response()->json([
            'chats' => $chats,
        ], 201);
    }

    public function storeChat(Request $request)
    {
        $chat = Chat::create($request->all());

        return response()->json([
            'chat' => $chat,
        ], 201);
    }
}
