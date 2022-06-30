<?php

use App\Models\Customer;
use App\Models\User;

test('customer is active is boolean', function () {
   $customer = Customer::factory()->for(User::factory()->customer())->create();

   expect($customer->is_active)->toBeTrue();
});
