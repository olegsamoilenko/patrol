<?php

namespace App\Http\Controllers;

use App\Events\ChatMessage;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SendChatNotification;

class ChatController extends Controller
{
    public function getAllMessages()
    {
        $messages = Message::with('user')->get();

        return response()->json([
            'messages' => $messages,
        ], 201);
    }

    public function storeMessage(Request $request)
    {
        $user = Auth::user();
        $message = Message::create($request->all());

        broadcast(new ChatMessage($user, $message));
        $users = User::all();
        foreach ($users as $user) {
            if ($user->fcm_token == null) {
                continue;
            } else {
                $user->notify(new SendChatNotification('Повідомлення','Отримано нове повідомлення в чаті', $user->fcm_token));
            }
        }



        return response()->json([
            'message' => $message,
        ], 201);
    }
}
