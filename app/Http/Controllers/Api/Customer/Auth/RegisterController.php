<?php

namespace App\Http\Controllers\Api\Customer\Auth;

use App\Models\User;
use App\Models\Customer;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\CustomerResource;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => bcrypt($request->password),
            'role' => 'customer'
        ]);

        Auth::login($user);
        $customer = Customer::create([
            'user_id' => $user->id
        ]);
        $token = $user->createToken($user->email)->plainTextToken;
        return response()->json([
            'token' => $token,
            'customer' => new CustomerResource($customer),
            'message' => 'User logged in successfully'
        ]);
    }
}
