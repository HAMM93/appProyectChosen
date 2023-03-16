<?php

namespace App\Services\Payment\src\Providers\Stripe;


class StripeCustomerService extends StripeClient
{

    public function createCustomer(array $customer_data)
    {
        $this->client->customers->create();
    }
}
