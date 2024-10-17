<?php

namespace App\Http\Controllers\Auth;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'password' => 'required',
        ]);

        $employee = Employee::where('code', $request->code)->first();

        if (!$employee || !Hash::check($request->password, $employee->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // إنشاء توكن جديد
        $token = $employee->createToken('employee_token')->plainTextToken;

        return response()->json(['token' => $token, 'employee' => $employee], 200);
    }

    public function logout(Request $request)
    {
        // حذف التوكنز النشطة
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

}
