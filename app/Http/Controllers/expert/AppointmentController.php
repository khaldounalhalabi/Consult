<?php

namespace App\Http\Controllers\expert;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function getAppointments()
    {
        $expert = Auth::guard('expert')->user();
        $appointments = Appointment::where('expert_id', $expert->id)
            ->where('status', 'waiting')
            ->with('user')
            ->get();
        return response()->json([
            'message' => 'data has been retrieved successfully',
            'appointments' => $appointments
        ], 200);
    }

    public function getAppointmentDetails($id)
    {
        $appointment = Appointment::find($id);
        if (Auth::guard('expert')->user()->id == $appointment->expert_id) {
            $user = $appointment->user;
            return response()->json([
                'message' => 'success',
                'appointment' => $appointment,
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'message' => 'not authorized'
            ], 401);
        }
    }

    public function changeAppointmentStatus($appointment_id)
    {
        $appointment = Appointment::find($appointment_id);
        $appointment->status = 'done';
        $appointment->save();
        $expert = Auth::guard('expert')->user();
        $expert->wallet = $expert->wallet + $expert->price;

        $user = User::find($appointment->user_id);
        $user->wallet = $user->wallet - $expert->price;
        $user->save();
        return response()->json([
            'message' => 'success',
        ], 200);
    }
}
