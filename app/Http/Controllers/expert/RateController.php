<?php

namespace App\Http\Controllers\expert;

use App\Http\Controllers\Controller;
use App\Models\CommentReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RateController extends Controller
{
    public function getCommentsAndReviews()
    {
        $expert_id = Auth::guard('expert')->user()->id;
        $comment_reviews = CommentReview::where('expert_id', $expert_id)
            ->orderByDesc('created_at')
            ->with('user')
            ->get();
        return response()->json([
            'message' => 'success',
            'comments' => $comment_reviews,
        ], 200);
    }
    public function totalRate()
    {
        $expert_id = Auth::guard('expert')->user()->id;
        $totalRates = CommentReview::where('expert_id', $expert_id)
            ->sum('star_rating');
        $ratesCount = CommentReview::where('expert_id', $expert_id)
            ->count();

        if($ratesCount == 0  || $totalRates == 0 ){
            return response()->json([
                'message' => 'success',
                'average rate' => 0 ,
            ], 200);
        }

        $averageRate = $totalRates / $ratesCount ;

        return response()->json([
            'message' => 'success',
            'average rate' => round($averageRate, 1)
        ], 200);
    }
}
