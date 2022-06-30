<?php

use App\Models\Customer;
use App\Models\User;


test('user with role customer has a customer relationship', function () {
    $user = User::factory()
            ->has(Customer::factory())
            ->customer()->create();

    $this->assertDatabaseHas('customers', ['id' => 1, 'is_active' => 1, 'user_id' => $user->id]);
});
