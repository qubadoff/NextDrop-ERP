<?php

namespace App\Http\Controllers\api\v1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function __construct(){}

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $employee = Auth::guard('employee')->user();

        if (!Auth::guard('employee')->attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $token = $employee->createToken('CustomerLoginToken')->plainTextToken;

        $expires_in = config('sanctum.expiration');

        DB::table('personal_access_tokens')
            ->where('tokenable_id', $employee->id)
            ->update([
                'expires_at' => now()->addMinutes($expires_in),
            ]);

        return response()->json([
            'token' => $token,
            'employee' => $employee
        ]);
    }
}
