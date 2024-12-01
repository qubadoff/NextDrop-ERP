<?php

namespace App\Http\Controllers\api\v1\Employee;

use App\Employee\EmployeeAvansStatus;
use App\Employee\EmployeeLeaveStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeAvans;
use App\Models\EmployeeAward;
use App\Models\EmployeeLeave;
use App\Models\EmployeePenal;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function list(): JsonResponse
    {
        $employeeId = Auth::guard('employee')->user()->id;

        $attendancePaginated = EmployeeAttendance::where('employee_id', $employeeId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $attendanceData = collect($attendancePaginated->items())->groupBy(function ($attendance) {
            return $attendance->created_at->format('Y-m-d');
        });

        $formattedData = $attendanceData->map(function ($attendances, $date) {
            return [
                'date' => $date,
                'attendances' => $attendances->map(function ($attendance) {
                    return [
                        'employee_in' => $attendance->employee_in,
                        'employee_out' => $attendance->employee_out,
                    ];
                }),
            ];
        });

        return response()->json([
            'data' => $formattedData->values(),
            'pagination' => [
                'total' => $attendancePaginated->total(),
                'current_page' => $attendancePaginated->currentPage(),
                'last_page' => $attendancePaginated->lastPage(),
                'per_page' => $attendancePaginated->perPage(),
            ],
        ]);
    }

    public function sendAttendance(Request $request): JsonResponse
    {
        $request->validate([
            'qrCode' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $employee = Auth::guard('employee')->user();

        if ($request->qrCode !== $employee->branch->qr_code) {
            return response()->json(['message' => 'QR kodu düz deyil!'], 422);
        }

        $branchLatitude = $employee->branch->latitude;
        $branchLongitude = $employee->branch->longitude;

        $distance = $this->calculateDistance(
            $request->latitude,
            $request->longitude,
            $branchLatitude,
            $branchLongitude
        );

        if ($distance > 50) {
            return response()->json(['message' => 'Filiala yaxin deyilsen!'], 422);
        }

        DB::beginTransaction();

        try {
            $today = Carbon::today();
            $weekday = $today->dayOfWeekIso;

            $attendance = EmployeeAttendance::where('employee_id', $employee->id)
                ->whereDate('employee_in', $today)
                ->orderBy('employee_in', 'desc')
                ->first();

            if ($attendance && !$attendance->employee_out) {
                $employeeOut = Carbon::now();
                $employeeIn = Carbon::parse($attendance->employee_in);
                $duration = round($employeeIn->diffInMinutes($employeeOut));

                $attendance->update([
                    'employee_out' => $employeeOut,
                    'duration' => $duration,
                ]);
            } else {
                $employeeIn = Carbon::now();

                $isFirstEntry = !EmployeeAttendance::where('employee_id', $employee->id)
                    ->whereDate('employee_in', $today)
                    ->exists();

                if ($isFirstEntry) {
                    $workHours = DB::table('employee_work_hours')
                        ->where('employee_id', $employee->id)
                        ->where('weekday', $weekday)
                        ->first();

                    if ($workHours && $employeeIn->gt(Carbon::parse($workHours->start_time))) {
                        $startTime = Carbon::parse($workHours->start_time);
                        $lateTime = $startTime->diffInMinutes($employeeIn);

                        DB::table('late_employees')->insert([
                            'employee_id' => $employee->id,
                            'date' => $employeeIn,
                            'late_time' => $lateTime,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                EmployeeAttendance::create([
                    'employee_id' => $employee->id,
                    'branch_id' => $employee->branch_id,
                    'employee_in' => $employeeIn,
                    'qr_code' => $request->qrCode,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Uğurla göndərildi!',
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
                return 0;
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
            return 0;
        }

        $uSquared = $cos2Alpha * ($a * $a - $b * $b) / ($b * $b);
        $A = 1 + $uSquared / 16384 * (4096 + $uSquared * (-768 + $uSquared * (320 - 175 * $uSquared)));
        $B = $uSquared / 1024 * (256 + $uSquared * (-128 + $uSquared * (74 - 47 * $uSquared)));
        $deltaSigma = $B * $sinSigma * ($cos2SigmaM + $B / 4 * ($cosSigma * (-1 + 2 * $cos2SigmaM * $cos2SigmaM) -
                    $B / 6 * $cos2SigmaM * (-3 + 4 * $sinSigma * $sinSigma) * (-3 + 4 * $cos2SigmaM * $cos2SigmaM)));

        $distance = $b * $A * ($sigma - $deltaSigma);

        return $distance; // Distance in meters
    }

    public function penalList(): JsonResponse
    {
        $penal = EmployeePenal::where('employee_id', Auth::guard('employee')->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $penalData = $penal->items();

        $penalData = collect($penalData)->map(function ($item) {
            return [
                'id' => $item->id,
                'date' => $item->date,
                'who_added' => $item->who_added,
                'penal_amount' => $item->penal_amount,
                'reason' => $item->reason,
                'penal_type' => $item->penal_type->getLabel(),
                'status' => $item->status->getLabel(),
            ];
        });

        return response()->json([
            'data' => $penalData,
            'pagination' => [
                'total' => $penal->total(),
                'current_page' => $penal->currentPage(),
                'last_page' => $penal->lastPage(),
                'per_page' => $penal->perPage(),
            ],
        ]);
    }

    public function awardList(): JsonResponse
    {
        $award = EmployeeAward::where('employee_id', Auth::guard('employee')->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $awardData = collect($award->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'date' => $item->date,
                'who_added' => $item->who_added,
                'award_amount' => $item->award_amount,
                'reason' => $item->reason,
                'award_type' => $item->award_type->getLabel(),
                'status' => $item->status->getLabel(),
            ];
        });

        return response()->json([
            'data' => $awardData,
            'pagination' => [
                'total' => $award->total(),
                'current_page' => $award->currentPage(),
                'last_page' => $award->lastPage(),
                'per_page' => $award->perPage(),
            ],
        ]);
    }

    public function sendAvans(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => 'required|integer',
            'reason' => 'required',
        ]);

        DB::beginTransaction();

        try {
            EmployeeAvans::create([
                'employee_id' => Auth::guard('employee')->user()->id,
                'date' => Carbon::now(),
                'amount' => $request->amount,
                'reason' => $request->reason,
                'status' => EmployeeAvansStatus::PENDING->value,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Avans istəyi uğurla göndərildi !'
            ]);
        } catch (Exception $e)
        {
            DB::rollBack();

            return response()->json($e->getMessage());
        }
    }

    public function avansList(): JsonResponse
    {
        $avans = EmployeeAvans::where('employee_id', Auth::guard('employee')->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $avansData = collect($avans->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'date' => $item->date,
                'amount' => $item->amount,
                'reason' => $item->reason,
                'status' => $item->status->getLabel(),
            ];
        });

        return response()->json([
            'data' => $avansData,
            'pagination' => [
                'total' => $avans->total(),
                'current_page' => $avans->currentPage(),
                'last_page' => $avans->lastPage(),
                'per_page' => $avans->perPage(),
            ],
        ]);
    }

    public function leaveSend(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'reason' => 'required',
        ]);

        DB::beginTransaction();

        try {

            EmployeeLeave::create([
                'employee_id' => Auth::guard('employee')->user()->id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'reason' => $request->reason,
                'status' => EmployeeLeaveStatusEnum::PENDING->value
            ]);

            DB::commit();

            return response()->json([
                'message' => 'İcazə istəyi üğurla göndərildi !'
            ]);


        } catch (Exception $e) {
            DB::rollBack();

            return response()->json($e->getMessage());
        }
    }

    public function leaveList(): JsonResponse
    {
        $leave = EmployeeLeave::where('employee_id', Auth::guard('employee')->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $leaveData = collect($leave->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'start_date' => $item->start_date,
                'end_date' => $item->end_date,
                'reason' => $item->reason,
                'status' => $item->status->getLabel(),
            ];
        });

        return response()->json([
            'data' => $leaveData,
            'pagination' => [
                'total' => $leave->total(),
                'current_page' => $leave->currentPage(),
                'last_page' => $leave->lastPage(),
                'per_page' => $leave->perPage(),
            ],
        ]);
    }

}
