<?php

namespace App\Http\Controllers\Api\Customer\Auth;

use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'customer'])) {
            return response()->json([
                'message' => 'Invalid Credentials, kindly check and try again'
            ], 401);
        }

        $user = User::whereEmail($request->email)->first();
        $token = $user->createToken($user->email)->plainTextToken;
        Auth::login($user);

        return response()->json([
            'token' => $token,
            'customer' => new CustomerResource($user->customer),
            'message' => 'User logged in successfully'
        ]);
    }
}
