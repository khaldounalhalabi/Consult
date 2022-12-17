<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expert;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $expert = Expert::where('id' , $id)->with('category')->get();
        return response()->json(['expert' => $expert]);
    }
}
