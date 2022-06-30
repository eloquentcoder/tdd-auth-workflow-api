<?php

use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\assertDatabaseHas;


test('user must send all valid fields to the register endpoint', function () {

    $response =  $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->postJson('/api/v1/customer/register');

    $response->assertStatus(422)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json->has('message')
                ->has(
                    'errors',
                    fn ($json) =>
                    $json->has('first_name')
                        ->has('last_name')
                        ->has('email')
                        ->has('phone_number')
                        ->has('password')
                )
        );
});


test('user must send a valid email to the register endpoint', function () {
    $body = [
        'first_name' => 'Patrick',
        'last_name' => 'Obafemi',
        'email' => 'eloquent',
        'password' => 'password',
        'phone_number' => '07019318840'
    ];

    $response =  $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->postJson('/api/v1/customer/register', $body);

    $response->assertStatus(422)
        ->assertJson([
            "message" => "The email must be a valid email address.",
            "errors" => [
                "email" => ["The email must be a valid email address."],
            ]
        ]);
});


test('user can register by sending valid details to the endpoint', function()
{
    $body = [
        'first_name' => 'Patrick',
        'last_name' => 'Obafemi',
        'email' => 'eloquent@gmail.com',
        'password' => 'password',
        'phone_number' => '07019318840'
    ];

    $response =  $this->withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->postJson('/api/v1/customer/register', $body);
             

    $this->assertDatabaseHas('users', ['first_name' => 'Patrick', 'last_name' => 'Obafemi', 'email' => 'eloquent@gmail.com']);
    $this->assertDatabaseHas('customers', ['id' => 1, 'is_active' => '1']);

    $response->assertStatus(200)
             ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has('token')
                    ->has(
                        'customer',
                        fn ($json) =>
                        $json->has('isActive')
                            ->has(
                                'user', 
                                fn($json) => 
                                $json->has('firstName')
                                ->has('lastName')
                                ->has('email')
                                ->has('phoneNumber')
                                ->has('emailVerified')     
                            )
                    )
            );

});