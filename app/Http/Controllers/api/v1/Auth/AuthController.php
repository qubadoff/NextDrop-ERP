<?php

namespace App\Http\Controllers\api\v1\Auth;

use App\Employee\EmployeeStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(){}

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_id' => 'nullable|string',
        ]);

        $employee = Employee::where('email', $request->email)->first();

        dd($employee->status);

        if ($employee->status === EmployeeStatusEnum::INACTIVE->value) {
            $employee->tokens()->delete();
            return response()->json(['message' => 'Sizin hesabınız deaktiv edilib !'], 422);
        }

        if (!$employee || !Hash::check($request->password, $employee->password)) {
            return response()->json(['message' => 'Email və ya şifrə yanlışdır !'], 422);
        }

//        if (is_null($employee->device_id)) {
//            $employee->device_id = $request->device_id;
//            $employee->save();
//        } elseif ($employee->device_id !== $request->device_id) {
//            return response()->json(['message' => 'Sizin cihaz fərqlidir ! Zəhmət olmazsa bir cihazdan istifadə edin !'], 422);
//        }

        $token = $employee->createToken('EmployeeLoginToken')->plainTextToken;

        DB::table('personal_access_tokens')
            ->where('tokenable_id', $employee->id)
            ->update([
                'expires_at' => now()->addMinutes(config('sanctum.expiration')),
            ]);

        $employeeData = [
            'name' => $employee->name,
            'surname' => $employee->surname,
            'email' => $employee->email,
            'photo' => $employee->photo ? url($employee->photo) : url('/default.png'),
        ];

        return response()->json([
            'token' => $token,
            'employee' => $employeeData,
        ]);
    }
}
