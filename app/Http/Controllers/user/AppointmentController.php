<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function getAppointments($expert_id)
    {
        $appointments = Appointment::where('expert_id', $expert_id)
            ->where('status', 'waiting')
            ->with('user')
            ->get();
        return response()->json([
            'message' => 'data has been retrieved successfully',
            'appointments' => $appointments
        ], 200);
    }

    public function setAppointment(Request $request, $expert_id)
    {
        $rules = [
            'date' => 'date|required',
            'time' => 'date_format:h:i|required',
        ];

        $validator = Validator::make($request->only('date', 'time'), $rules);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ]);
        }
        $appointment = new Appointment;
        $appointment->user_id = Auth::guard('user')->user()->id;
        $appointment->expert_id = $expert_id;
        $appointment->date = $request->date;
        $appointment->time = $request->time;
        $appointment->save();

        return response()->json([
            'message' => 'success',
            'appointment' => $appointment,
        ], 200);
    }
}
