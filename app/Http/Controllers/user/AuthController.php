<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
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

            $token = Auth::guard('user')->attempt([
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
                ], 200
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'there is been an error', 'error message' => $e->getMessage()], 500);
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

            if (Auth::guard('user')->attempt([
                'email' => $request->email,
                'password' => $request->password
            ])) {
                $user = Auth::guard('user')->user();
                $token = Auth::guard('user')->attempt([
                    'email' => $request->email,
                    'password' => $request->password
                ]);
                return response()->json([
                    'message' => 'success',
                    'user' => $user,
                    'token' => $token
                ], 200);
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
        Auth::guard('user')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ], 200);
    }

    public function details()
    {
        $user = Auth::guard('user')->user();
        return response()->json([
            "message" => 'success',
            "user" => $user,
        ], 200);
    }

    public function editDetails(Request $request)
    {
        $rules = [
            'name' => 'string|min:3|max:255|nullable',
            'email' => 'email|string|min:3|nullable|unique:users',
            'phone' => 'string|nullable',
            'mobile' => 'string|nullable',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        try {
            $user = User::find(Auth::guard('user')->user()->id);

            if (isset($request->name)) {
                $user->name = $request->name;
            }

            if (isset($request->email)) {
                $user->email = $request->email;
            }

            if (isset($request->phone)) {
                $user->phone = $request->phone;
            }

            if (isset($request->mobile)) {
                $user->mobile = $request->mobile;
            }

            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'User Edited successfully',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'there is been an error', 'error message' => $e->getMessage()], 500);
        }
    }
}
