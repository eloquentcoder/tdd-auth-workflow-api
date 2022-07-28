<?php

use App\Models\User;
use App\Models\Customer;
use Illuminate\Testing\Fluent\AssertableJson;

function getUser()
{
    return User::factory()->create([
        'email' => 'john.doe@gmail.com',
        'password' => bcrypt('passworded'),
        'role' => 'customer'
    ]);
}

test('user must be enter email to login', function () {
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->postJson('/api/v1/customer/login');

    $response->assertStatus(422)
        ->assertJson([
            "message" => "The email field is required.",
            "errors" => [
                "email" => ["The email field is required."],
            ]
        ]);
});

test('user must be enter valid email to login', function () {
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->postJson('/api/v1/customer/login', ['email' => 'patrick']);

    $response->assertStatus(422)
        ->assertJson([
            "message" => "The email must be a valid email address.",
            "errors" => [
                "email" => ["The email must be a valid email address."],
            ]
        ]);
});

test('user must be enter password to login', function () {
    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->postJson('/api/v1/customer/login', ['email' => 'eloquentintech@gmail.com']);

    $response->assertStatus(422)
        ->assertJson([
            "message" => "The password field is required.",
            "errors" => [
                "password" => ["The password field is required."],
            ]
        ]);
});


test('user cannot login with an unregistered email and password', function () {

    getUser();

    $body = [
        'email' => 'eloquentintech@gmail.com',
        'password' => 'password'
    ];

    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->postJson('/api/v1/customer/login', $body);

    $response->assertStatus(401)
        ->assertJson([
            "message" => "Invalid Credentials, kindly check and try again"
        ]);
});

test('registered user can login', function () {
    $user = User::factory()
        ->create([
            'email' => 'mark.tunde@gmail.com',
            'password' => bcrypt('passworded'),
            'role' => 'customer'
        ]);
    $customer = Customer::factory()->for($user)->create();


    $body = [
        'email' => 'mark.tunde@gmail.com',
        'password' => 'passworded'
    ];

    $response = $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->postJson('/api/v1/customer/login', $body);

    $response->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json->has('message')
                ->has('token')
                ->has(
                    'customer',
                    fn ($json) =>
                    $json->where('isActive', $customer->is_active)
                        ->has(
                            'user', 
                            fn($json) => 
                            $json->where('firstName', $user->first_name)
                            ->where('lastName', $user->last_name)
                            ->where('email', $user->email)
                            ->where('phoneNumber', $user->phone_number)
                            ->where('emailVerified', $user->is_email_verified)     
                        )
                )
        );
});
