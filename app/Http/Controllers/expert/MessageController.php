<?php

namespace App\Http\Controllers\expert;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function indexMessages($user_id)
    {
        $expert_id = Auth::guard('expert')->user()->id;
        $messages = Message::where('user_id', $user_id)
            ->where('expert_id', $expert_id)
            ->orderByDesc('created_at')
            ->get();
        return response()->json([
            'message' => 'success',
            'messages' => $messages
        ], 200);
    }

    public function sendMessage(Request $request, $user_id)
    {
        $message = new Message;
        $message->body = $request->message;
        $message->expert_id = Auth::guard('expert')->user()->id;
        $message->user_id = $user_id;
        $message->from = 'expert';
        $message->save();
        return response()->json(['message' => 'success'], 200);
    }
}
