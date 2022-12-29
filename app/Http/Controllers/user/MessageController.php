<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function indexMessages($expert_id)
    {
        $user_id = Auth::guard('user')->user()->id;
        $messages = Message::where('user_id', $user_id)
            ->where('expert_id', $expert_id)
            ->orderByDesc('created_at')
            ->get();
        return response()->json([
            'message' => 'success',
            'messages' => $messages
        ], 200);
    }

    public function sendMessage(Request $request, $expert_id)
    {
        $message = new Message;
        $message->body = $request->message;
        $message->user_id = Auth::guard('user')->user()->id;
        $message->expert_id = $expert_id;
        $message->from = 'user';
        $message->save();
        return response()->json([
            'message' => 'success'
        ], 200);
    }

}
