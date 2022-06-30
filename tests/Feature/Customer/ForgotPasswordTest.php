<?php

use App\Mail\SendCodeReset;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\Fluent\AssertableJson;

test('user must enter an email to reset password', function () {
    $response = $this->postJson('/api/v1/forgot-password');
    $response->assertStatus(422)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json->has('message')
                ->has(
                    'errors',
                    fn ($json) =>
                    $json->has('email')
                )
        );
});

test('only registered users can send password reset email to reset password', function () {
    $user = User::factory()->customer()->create([
        'email' => 'eloquent@gmail.com'
    ]);

    $body = ['email' => 'eloquentintech@gmail.com'];

    $response = $this->postJson('/api/v1/forgot-password', $body);

    $this->assertDatabaseMissing('users', ['email' => 'eloquentintech@gmail.com']);

    $response->assertStatus(401)
        ->assertJsonStructure([
            'message'
        ]);
});

test('users can send reset email to reset password', function () {
    $user = User::factory()->customer()->create([
        'email' => 'eloquent@gmail.com'
    ]);

    Mail::fake();

    $body = ['email' => 'eloquent@gmail.com'];

    $response = $this->postJson('/api/v1/forgot-password', $body);

    $this->assertDatabaseHas('code_reset_passwords', ['email' => 'eloquent@gmail.com']);
    Mail::assertQueued(SendCodeReset::class);

    $response->assertStatus(200)
             ->assertJsonStructure([
                'message'
             ]);
});
