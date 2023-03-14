<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\SendChatNotification;
use Illuminate\Http\Request;
use Notification;
use Kutia\Larafirebase\Facades\Larafirebase;

//use Kutia\Larafirebase\Serveces\Larafirebase;

class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function updateToken(Request $request)
    {
        try {
            $request->user()->update(['fcm_token' => $request->token]);
            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'success' => false
            ], 500);
        }
    }

    public function notification(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'message' => 'required'
        ]);

        try {
            $fcmTokens = User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();

            Notification::send(null,new SendChatNotification($request->title, $request->message, $fcmTokens));

            /* or */

//            auth()->user()->notify(new SendChatNotification($request->title,$request->message,$fcmTokens));

            /* or */

            Larafirebase::withTitle($request->title)
                ->withBody($request->message)
                ->sendMessage($fcmTokens);

            return redirect()->back()->with('success', 'Notification Sent Successfully!!');

        } catch (\Exception $e) {
            report($e);
            return redirect()->back()->with('error', 'Something goes wrong while sending notification.');
        }
    }
}
