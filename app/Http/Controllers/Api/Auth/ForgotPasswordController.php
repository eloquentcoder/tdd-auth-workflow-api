<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Mail\SendCodeReset;
use App\Models\CodeResetPassword;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function __invoke(ForgotPasswordRequest $request)
    {
        if (!User::whereEmail($request->email)->exists()) {
            return response()->json([
                'message' => 'This email does not belong to any user, try to register now'
            ], 401);
        }

        CodeResetPassword::where('email', $request->email)->delete();

        $code = rand(10000, 99999);
        CodeResetPassword::create([
            'email' => $request->email,
            'code' => $code
        ]);

        Mail::to($request->user)->later(now()->addSeconds(10), new SendCodeReset($code));

        return response()->json([
            'message' => 'Password reset code sent successfully'
        ], 200);

    }
}
