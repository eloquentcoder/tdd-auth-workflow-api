<?php

use App\Models\User;

test('user is email verified attribute must return boolean', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
        'role' => 'customer'
    ]);

    expect($user->is_email_verified)->toBeTrue();
});
