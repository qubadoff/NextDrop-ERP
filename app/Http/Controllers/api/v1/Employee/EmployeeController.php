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
    public function list(): JsonResponse
    {
        return response()->json(EmployeeAttendance::where('employee_id', Auth::guard('employee')->user()->id)->orderBy('id', 'desc')->get());
    }

    public function sendAttendance(Request $request): JsonResponse
    {
        $request->validate([
            'employee_in' => 'nullable|date',
            'employee_out' => 'nullable|date',
            'location' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $employee = Auth::guard('employee')->user();

        $branchLatitude = $employee->branch->latitude;
        $branchLongitude = $employee->branch->longitude;

        $distance = $this->calculateDistance(
            $request->latitude,
            $request->longitude,
            $branchLatitude,
            $branchLongitude
        );

        if ($distance > 50) {
            return response()->json(['message' => 'Filila yaxin deyilsen !'], 422);
        }

        DB::beginTransaction();

        try {
            EmployeeAttendance::create([
                'employee_id' => Auth::guard('employee')->user()->id,
                'branch_id' => Auth::guard('employee')->user()->branch_id,
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

    /**
     * Calculate distance between two coordinates in meters
     *
     * @param float $lat1 Latitude of the first location
     * @param float $lon1 Longitude of the first location
     * @param float $lat2 Latitude of the second location
     * @param float $lon2 Longitude of the second location
     * @return float Distance in meters
     */
    private function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
