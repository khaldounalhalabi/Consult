<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\CommentReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function getCommentsAndReviews($expert_id)
    {
        $comment_reviews = CommentReview::where('expert_id', $expert_id)
            ->orderByDesc('created_at')
            ->with('user')
            ->get();
        return response()->json([
            'message' => 'success',
            'comments' => $comment_reviews,
        ], 200);
    }

    public function comment(Request $request, $expert_id)
    {
        $validator = Validator::make(
            $request->only('comment', 'rate'),
            [
                'comment' => 'string|nullable',
                'rate' => 'integer|min:0|max:5|nullable',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'validation error',
                'error message' => $validator->errors()
            ]);
        }
        $comment = new CommentReview;
        $comment->comment = $request->comment;
        $comment->star_rating = $request->rate;
        $comment->expert_id = $expert_id;
        $comment->user_id = Auth::guard('user')->user()->id;
        $comment->save();

        return response()->json(['message' => 'success'], 200);
    }

    public function deleteComment($comment_id)
    {
        $comment = CommentReview::find($comment_id);
        $user_id = Auth::guard('user')->user()->id;
        if ($comment->user_id == $user_id) {
            $comment->delete();
            return response()->json([
                'message' => 'success',
            ], 200);
        } else {
            return response()->json([
                'message' => 'not authorized',
            ], 401);
        }
    }

    public function totalRate($expert_id)
    {
        $totalRates = CommentReview::where('expert_id', $expert_id)
            ->sum('star_rating');
        $ratesCount = CommentReview::where('expert_id', $expert_id)
            ->count();

        $averageRate = $totalRates / $ratesCount;

        return response()->json([
            'message' => 'success',
            'average rate' => round($averageRate, 1)
        ], 200);
    }
}
