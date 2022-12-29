<?php

namespace App\Http\Controllers\expert;

use App\Http\Controllers\Controller;
use App\Models\Expert;
use App\Models\OpenedTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
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
            $token = Auth::guard('expert')->attempt([
                'email' => $request->email,
                'password' => $request->password
            ]);

            if ($token) {

                $expert = Auth::guard('expert')->user();
                $category = $expert->category ;
                $work_time = $expert->opened_time ;

                return response()->json([
                    'message' => 'success',
                    'expert' => $expert,
                    // 'category' => $expert->category,
                    'token' => $token
                ], 200);
            } else {
                return response()->json(['message' => 'wrong credentials'], 401);
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return response()->json(['message' => $error], 500);
        }
    }

    public function register(Request $request)
    {
        $rules = [
            'name' => 'string|min:3|max:255|required',
            'photo' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'email' => 'email|string|min:3|required|unique:experts',
            'password' => 'string|min:6|max:255',
            'experience' => 'min:3|required',
            'phone' => 'string|required',
            'mobile' => 'string|nullable',
            'country' => 'string|required',
            'city' => 'string|required',
            'street' => 'string|required',
            'category_id' => 'integer',
            'price' => 'numeric',
            'saturday_from' => 'nullable|date_format:h:i',
            'saturday_to' => 'nullable|date_format:h:i',
            'sunday_from' => 'nullable|date_format:h:i',
            'sunday_to' => 'nullable|date_format:h:i',
            'monday_from' => 'nullable|date_format:h:i',
            'monday_to' => 'nullable|date_format:h:i',
            'tuesday_from' => 'nullable|date_format:h:i',
            'tuesday_to' => 'nullable|date_format:h:i',
            'wednesday_from' => 'nullable|date_format:h:i',
            'wednesday_to' => 'nullable|date_format:h:i',
            'thursday_from' => 'nullable|date_format:h:i',
            'thursday_to' => 'nullable|date_format:h:i',
            'friday_from' => 'nullable|date_format:h:i',
            'friday_to' => 'nullable|date_format:h:i',
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

            if ($request->hasFile('photo') != null) {
                $destenation_path = 'photos';
                $image_name = $request->file('photo')->getClientOriginalName();
                $path = $request->file('photo')->storeAs('public/' . $destenation_path, $image_name);
                $expert->photo = $destenation_path . '/' . $image_name;
            }

            $expert->save();

            $opened_time = new OpenedTime;
            $opened_time->saturday_from = $request->saturday_from;
            $opened_time->saturday_to = $request->saturday_to;
            $opened_time->sunday_from = $request->sunday_from;
            $opened_time->sunday_to = $request->sunday_to;
            $opened_time->monday_from = $request->monday_from;
            $opened_time->monday_to = $request->monday_to;
            $opened_time->tuesday_from = $request->tuesday_from;
            $opened_time->tuesday_to = $request->tuesday_to;
            $opened_time->wednesday_from = $request->wednesday_from;
            $opened_time->wednesday_to = $request->wednesday_to;
            $opened_time->thursday_from = $request->thursday_from;
            $opened_time->thursday_to = $request->thursday_to;
            $opened_time->friday_from = $request->friday_from;
            $opened_time->friday_to = $request->friday_to;

            $opened_time->expert_id = $expert->id;
            $opened_time->save();


            $token = Auth::guard('expert')->attempt([
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
                ], 200
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'there is been an error',
                'error message' => $e->getMessage()
            ]);
        }
    }

    public function logout()
    {
        Auth::guard('expert')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ], 200);
    }

    public function details()
    {
        $expert = Auth::guard('expert')->user();
        $opened_time = OpenedTime::where('expert_id', $expert->id)->get();
        return response()->json([
            "message" => 'success',
            "expert" => $expert,
            'work time' => $opened_time,
        ], 200);
    }

    public function editDetails(Request $request)
    {
        $rules = [
            'name' => 'string|min:3|max:255',
            'photo' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'email' => 'email|string|min:3|unique:experts',
            'experience' => 'min:3',
            'phone' => 'string',
            'mobile' => 'string|nullable',
            'country' => 'string',
            'city' => 'string',
            'street' => 'string',
            'category_id' => 'integer',
            'price' => 'numeric',
            'saturday_from' => 'nullable|date_format:h:i',
            'saturday_to' => 'nullable|date_format:h:i',
            'sunday_from' => 'nullable|date_format:h:i',
            'sunday_to' => 'nullable|date_format:h:i',
            'monday_from' => 'nullable|date_format:h:i',
            'monday_to' => 'nullable|date_format:h:i',
            'tuesday_from' => 'nullable|date_format:h:i',
            'tuesday_to' => 'nullable|date_format:h:i',
            'wednesday_from' => 'nullable|date_format:h:i',
            'wednesday_to' => 'nullable|date_format:h:i',
            'thursday_from' => 'nullable|date_format:h:i',
            'thursday_to' => 'nullable|date_format:h:i',
            'friday_from' => 'nullable|date_format:h:i',
            'friday_to' => 'nullable|date_format:h:i',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        try {
            $id = Auth::guard('expert')->user()->id;
            $expert = Expert::find($id)->with('category')->first();
            if (isset($request->name)) {
                $expert->name = $request->name;
            }
            if (isset($request->email)) {
                $expert->email = $request->email;
            }
            if (isset($request->experience)) {
                $expert->experience = $request->experience;
            }
            if (isset($request->phone)) {
                $expert->phone = $request->phone;
            }
            if (isset($request->mobile)) {
                $expert->mobile = $request->mobile;
            }
            if (isset($request->country)) {
                $expert->country = $request->country;
            }
            if (isset($request->city)) {
                $expert->city = $request->city;
            }
            if (isset($request->street)) {
                $expert->street = $request->street;
            }
            if (isset($request->category_id)) {
                $expert->category_id = $request->category_id;
            }

            if ($request->hasFile('photo') != null) {
                $destenation_path = 'photos';
                $image_name = $request->file('photo')->getClientOriginalName();
                $path = $request->file('photo')->storeAs('public/' . $destenation_path, $image_name);
                $expert->photo = $destenation_path . '/' . $image_name;
            }

            $expert->save();

            $opened_time = OpenedTime::where('expert_id', $expert->id)->first();
            if (isset($request->saturday_from)) {
                $opened_time->saturday_from = $request->saturday_from;
            }
            if (isset($request->saturday_to)) {
                $opened_time->saturday_to = $request->saturday_to;
            }
            if (isset($request->sunday_from)) {
                $opened_time->sunday_from = $request->sunday_from;
            }
            if (isset($request->sunday_to)) {
                $opened_time->sunday_to = $request->sunday_to;
            }
            if (isset($request->monday_from)) {
                $opened_time->monday_from = $request->monday_from;
            }
            if (isset($request->monday_to)) {
                $opened_time->monday_to = $request->monday_to;
            }
            if (isset($request->tuesday_from)) {
                $opened_time->tuesday_from = $request->tuesday_from;
            }
            if (isset($request->tuesday_to)) {
                $opened_time->tuesday_to = $request->tuesday_to;
            }
            if (isset($request->wednesday_from)) {
                $opened_time->wednesday_from = $request->wednesday_from;
            }
            if (isset($request->wednesday_to)) {
                $opened_time->wednesday_to = $request->wednesday_to;
            }
            if (isset($request->thursday_from)) {
                $opened_time->thursday_from = $request->thursday_from;
            }
            if (isset($request->thursday_to)) {
                $opened_time->thursday_to = $request->thursday_to;
            }
            if (isset($request->friday_from)) {
                $opened_time->friday_from = $request->friday_from;
            }
            if (isset($request->friday_to)) {
                $opened_time->friday_to = $request->friday_to;
            }

            $opened_time->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Expert created successfully',
                'expert' => $expert,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'there is been an error',
                'error message' => $e->getMessage()
            ], 500);
        }
    }
}
