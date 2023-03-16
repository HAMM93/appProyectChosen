<?php

namespace App\Services\Payment\src\Providers\Pagarme;

use App\Services\Payment\src\Providers\Stripe\Contracts\CustomerInterface;

/*
 * API Endpoint https://api.pagar.me/1/customers
 */

class PagarmeCustomerService extends PagarmeClient
{
    public function create(CustomerInterface $customer)
    {
        return $this->client->customers()->create($customer->getDataToCreate());
    }
}
