<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Expert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpertController extends Controller
{
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

            if (Auth::guard('expertapi')->attempt([
                'email' => $request->email,
                'password' => $request->password
            ])) {
                $expert = Auth::guard('expertapi')->user();
                $token = Auth::guard('expertapi')->attempt([
                    'email' => $request->email,
                    'password' => $request->password
                ]);
                return response()->json([
                    'message' => 'success',
                    'user' => $expert,
                    'category' => $expert->category,
                    'token' => $token
                ]);
            } else {
                return response()->json(['message' => 'not authorized']);
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return response()->json(['message' => $error]);
        }
    }

    public function register(Request $request)
    {
        $rules = [
            'name' => 'string|min:3|max:255|required',
            // 'photo' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'email' => 'email|string|min:3|required|unique:experts',
            'password' => 'string|min:6|max:255',
            'experience' => 'min:3|required',
            'phone' => 'string|required',
            'mobile' => 'string|nullable',
            'country' => 'string|required',
            'city' => 'string|required',
            'street' => 'string|required',
            'category_id' => 'integer',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        try {
            $expert = new Expert;
            $expert->name = $request->name;
            $expert->email = $request->email;
            $expert->password = bcrypt($request->password);
            $expert->experience = $request->experience;
            $expert->phone = $request->phone;
            $expert->mobile = $request->mobile;
            $expert->country = $request->country;
            $expert->city = $request->city;
            $expert->street = $request->street;
            $expert->category_id = $request->category_id;

            if ($request->hasFile('image') != null) {
                $destenation_path = 'patients/images';
                $image_name = $request->file('image')->getClientOriginalName();
                $expert->image = $destenation_path . '/' . $image_name;
                $path = $request->file('image')->storeAs('public/' . $destenation_path, $image_name);
            }

            $expert->save();

            $token = Auth::guard('expertapi')->attempt([
                'email' => $request->email,
                'password' => $request->password,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Expert created successfully',
                'expert' => $expert,
                'category' => $expert->category,
                'authorization' => [
                    'token' => $token,
                    'type' => 'Bearer',
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'there is been an error',
                'error message' => $e->getMessage()
            ]);
        }
    }

    public function getAppointments()
    {
        $expert = Auth::guard('expertapi')->user();
        $appointments = Appointment::where('expert_id', $expert->id)
            ->with('user')->get();
        return response()->json([
            'message' => 'data has been retrieved successfully',
            'appointments' => $appointments
        ]);
    }

    public function getAppointmentDetails($id)
    {
        $appointment = Appointment::find($id);
        if (Auth::guard('expertapi')->user()->id == $appointment->expert_id) {
            $user = $appointment->user;
            return response()->json([
                'message' => 'success',
                'appointment' => $appointment,
            ]);
        } else {
            return response()->json([
                'message' => 'not authorized'
            ]);
        }
    }
}
