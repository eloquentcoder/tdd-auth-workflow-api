<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\CodeResetPassword;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function __invoke(ResetPasswordRequest $request)
    {
        if (!CodeResetPassword::whereCode($request->code)->exists()) {
            return response()->json([
                'message' => 'Invalid Reset Code, Check And Try Again'
            ], 401);
        } 
        
        $code_reset = CodeResetPassword::whereCode($request->code)->first();
        $user = User::whereEmail($code_reset->email)->first();

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        $code_reset->delete();
        
        return response()->json([
            'message' => 'Password Reset Successfully'
        ], 200);
    }
}
