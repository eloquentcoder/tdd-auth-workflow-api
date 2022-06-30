<?php

use App\Models\User;
use App\Models\CodeResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;

test('user must enter reset password code to reset password', function () {
    $response = $this->postJson('/api/v1/reset-password');
    $response->assertStatus(422)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json->has('message')
                ->has(
                    'errors',
                    fn ($json) =>
                    $json->has('code')
                        ->has('password')
                )
        );
});

test('user cannot reset password code if code is invalid', function () {
    CodeResetPassword::factory()->create([
        'email' => 'eloquentintech@gmail.com',
        'code' => '12345'
    ]);

    $response = $this->postJson('/api/v1/reset-password', ['code' => '13536', 'password' => 'password']);

    $this->assertDatabaseMissing('code_reset_passwords', ['code' => '13536']);
    $response->assertStatus(401)
        ->assertJson([
            'message' => 'Invalid Reset Code, Check And Try Again'
        ]);
});


test('user can reset password code if code is valid', function () {
    $user = User::factory()->customer()->create([
        'email' => 'eloquentintech@gmail.com'
    ]);

    CodeResetPassword::factory()->create([
        'email' => 'eloquentintech@gmail.com',
        'code' => '12345'
    ]);

    $response = $this->postJson('/api/v1/reset-password', ['code' => '12345', 'password' => 'password']);

    expect(Hash::check('password', Hash::make('password')))->toBeTrue();
    $this->assertDatabaseMissing('code_reset_passwords', ['code' => '12345']);

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Password Reset Successfully'
        ]);
});
