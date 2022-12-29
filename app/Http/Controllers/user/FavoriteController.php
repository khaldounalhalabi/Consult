<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function addToFavorite($expert_id)
    {
        $favorite = new Favorite();
        $favorite->expert_id = $expert_id;
        $favorite->user_id = Auth::guard('user')->user()->id;
        $favorite->save();

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    public function indexFavorite()
    {
        $user_id = Auth::guard('user')->user()->id;
        $favorites = Favorite::where('user_id', $user_id)
            ->with('expert')
            ->get();
        return response()->json([
            'message' => 'success',
            'favorites' => $favorites
        ], 200);
    }

    public function removeFavorite($expert_id)
    {
        $user_id = Auth::guard('user')->user()->id;
        $favorite = Favorite::where('user_id', $user_id)
            ->where('expert_id', $expert_id)
            ->first();
        $favorite->delete();
        return response()->json([
            'message' => 'success'
        ], 200);
    }
}
