<?php

namespace App\Http\Controllers\api\v1\Auth;

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
        ]);

        $employee = Employee::where('email', $request->email)->first();

        if (!$employee || !Hash::check($request->password, $employee->password)) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $token = $employee->createToken('EmployeeLoginToken')->plainTextToken;

        DB::table('personal_access_tokens')
            ->where('tokenable_id', $employee->id)
            ->update([
                'expires_at' => now()->addMinutes(config('sanctum.expiration')),
            ]);

        return response()->json([
            'token' => $token,
            'employee' => $employee
        ]);
    }
}
