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
        $a = 6378137.0; // Semi-major axis of the Earth (meters)
        $f = 1 / 298.257223563; // Flattening
        $b = 6356752.314245; // Semi-minor axis

        $lat1 = deg2rad($lat1);
        $lat2 = deg2rad($lat2);
        $lon1 = deg2rad($lon1);
        $lon2 = deg2rad($lon2);

        $L = $lon2 - $lon1;
        $U1 = atan((1 - $f) * tan($lat1));
        $U2 = atan((1 - $f) * tan($lat2));
        $sinU1 = sin($U1);
        $cosU1 = cos($U1);
        $sinU2 = sin($U2);
        $cosU2 = cos($U2);

        $lambda = $L;
        $lambdaP = 2 * M_PI;
        $iterLimit = 100;
        while (abs($lambda - $lambdaP) > 1e-12 && --$iterLimit > 0) {
            $sinLambda = sin($lambda);
            $cosLambda = cos($lambda);
            $sinSigma = sqrt(($cosU2 * $sinLambda) * ($cosU2 * $sinLambda) +
                ($cosU1 * $sinU2 - $sinU1 * $cosU2 * $cosLambda) * ($cosU1 * $sinU2 - $sinU1 * $cosU2 * $cosLambda));
            if ($sinSigma == 0) {
                return 0; // Co-incident points
            }
            $cosSigma = $sinU1 * $sinU2 + $cosU1 * $cosU2 * $cosLambda;
            $sigma = atan2($sinSigma, $cosSigma);
            $sinAlpha = $cosU1 * $cosU2 * $sinLambda / $sinSigma;
            $cos2Alpha = 1 - $sinAlpha * $sinAlpha;
            $cos2SigmaM = $cosSigma - 2 * $sinU1 * $sinU2 / $cos2Alpha;
            $C = $f / 16 * $cos2Alpha * (4 + $f * (4 - 3 * $cos2Alpha)); // Corrected $C usage
            $lambdaP = $lambda;
            $lambda = $L + (1 - $C) * $f * $sinAlpha *
                ($sigma + $C * $sinSigma * ($cos2SigmaM + $C * $cosSigma * (-1 + 2 * $cos2SigmaM * $cos2SigmaM)));
        }

        if ($iterLimit == 0) {
            return 0; // Formula failed to converge
        }

        $uSquared = $cos2Alpha * ($a * $a - $b * $b) / ($b * $b);
        $A = 1 + $uSquared / 16384 * (4096 + $uSquared * (-768 + $uSquared * (320 - 175 * $uSquared)));
        $B = $uSquared / 1024 * (256 + $uSquared * (-128 + $uSquared * (74 - 47 * $uSquared)));
        $deltaSigma = $B * $sinSigma * ($cos2SigmaM + $B / 4 * ($cosSigma * (-1 + 2 * $cos2SigmaM * $cos2SigmaM) -
                    $B / 6 * $cos2SigmaM * (-3 + 4 * $sinSigma * $sinSigma) * (-3 + 4 * $cos2SigmaM * $cos2SigmaM)));

        $distance = $b * $A * ($sigma - $deltaSigma);

        return $distance; // Distance in meters
    }
}
