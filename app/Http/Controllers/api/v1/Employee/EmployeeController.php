<?php

namespace App\Http\Controllers\api\v1\Employee;

use App\Http\Controllers\Controller;
use App\Models\EmployeeAttendance;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function list()
    {

    }

    public function sendAttendance(Request $request): JsonResponse
    {
        dd($request->all());
        $request->validate([
            'employee_in' => 'nullable|date',
            'employee_out' => 'nullable|date',
            'location' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        DB::beginTransaction();

        try {
            EmployeeAttendance::create([
                'employee_id' => Auth::user()->id,
                'branch_id' => Auth::user()->branch_id,
                'employee_in' => $request->employee_in,
                'employee_out' => $request->employee_out,
                'location' => $request->location,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Attendance sent successfully',
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json($e->getMessage(), 500);
        }
    }
}
