<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CommentReview;
use App\Models\Expert;
use App\Models\Favorite;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {

        $rules = [
            'name' => 'string|min:3|max:255|required',
            'email' => 'email|string|min:3|required|unique:users',
            'password' => 'string|min:6|max:255',
            'phone' => 'string|required',
            'mobile' => 'string|nullable',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        try {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->phone = $request->phone;
            $user->mobile = $request->mobile;

            $user->save();

            $token = Auth::guard('userapi')->attempt([
                'email' => $request->email,
                'password' => $request->password
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'Bearer',
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'there is been an error', 'error message' => $e->getMessage()]);
        }
    }


    public function login(Request $request)
    {
        try {
            $rules = [
                'email' => 'required|string|min:3',
                'password' => 'required|string|min:3',
            ];
            $validator = Validator::make($request->only('email', 'password'), $rules);

            if ($validator->fails()) {
                return response()->json($validator->errors());
            }

            if (Auth::guard('userapi')->attempt([
                'email' => $request->email,
                'password' => $request->password
            ])) {
                $user = Auth::guard('userapi')->user();
                $token = Auth::guard('userapi')->attempt([
                    'email' => $request->email,
                    'password' => $request->password
                ]);
                return response()->json([
                    'message' => 'success',
                    'user' => $user,
                    'token' => $token
                ]);
            } else {
                return response()->json(
                    [
                        'message' => 'not authorized'
                    ],
                    401
                );
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return response()->json(['message' => $error]);
        }
    }

    public function logout()
    {
        Auth::guard('userapi')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function details()
    {
        $user = Auth::guard('userapi')->user();
        return response()->json([
            "message" => 'success',
            "user" => $user,
        ]);
    }

    public function indexCategory()
    {
        try {
            $categories = Category::all();
            return response()->json([
                'Categories' => $categories,
                'message' => 'data has been retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'there is been an error',
                'error message' => $e->getMessage(),
            ]);
        }
    }

    public function indexExperts($id)
    {
        try {
            $category = Category::find($id);
            $experts = $category->experts;

            return response()->json([
                'message' => 'data has been retieved successfully',
                'experts' => $experts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'there is been an error',
                'error message' => $e->getMessage(),
            ]);
        }
    }

    public function search(Request $request)
    {
        $rules = [
            'name' => 'string|min:3|required',
        ];
        $validator = Validator::make(
            $request->only('name'),
            $rules
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $expert = Expert::where('name', $request->name)->with('category')->get();
        return response()->json(['expert' => $expert]);
    }

    public function expertDetails($id)
    {
        $expert = Expert::where('id', $id)->with('category')->get();
        return response()->json(['expert' => $expert]);
    }

    public function indexMessages($expert_id)
    {
        $user_id = Auth::guard('userapi')->user()->id;
        $messages = Message::where('user_id', $user_id)
            ->where('expert_id', $expert_id)
            ->orderByDesc('created_at')
            ->get();
        return response()->json([
            'message' => 'success',
            'messages' => $messages
        ]);
    }

    public function sendMessage(Request $request, $expert_id)
    {
        $message = new Message;
        $message->body = $request->message;
        $message->user_id = Auth::guard('userapi')->user()->id;
        $message->expert_id = $expert_id;
        $message->save();
        return $this->indexMessages($expert_id);
    }

    public function getCommentsAndReviews($expert_id)
    {
        $comment_reviews = CommentReview::where('expert_id', $expert_id)
            ->orderByDesc('created_at')
            ->with('user')
            ->get();
        return response()->json([
            'message' => 'success',
            'comments' => $comment_reviews,
        ]);
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
        $comment->user_id = Auth::guard('userapi')->user()->id;
        $comment->save();

        return $this->getCommentsAndReviews($expert_id);
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
        ]);
    }

    public function addToFavorite($expert_id)
    {
        $favorite = new Favorite;
        $favorite->expert_id = $expert_id;
        $favorite->user_id = Auth::guard('userapi')->user()->id;
        $favorite->save();

        return response()->json([
            'message' => 'success'
        ]);
    }

    public function indexFavorite()
    {
        $user_id = Auth::guard('userapi')->user()->id;
        $favorites = Favorite::where('user_id', $user_id)
            ->with('expert')
            ->get();
        return response()->json([
            'message' => 'success',
            'favorites' => $favorites
        ]);
    }
}
