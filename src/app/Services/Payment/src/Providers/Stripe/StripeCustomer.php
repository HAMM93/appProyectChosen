<?php

namespace App\Services\Payment\src\Providers\Stripe;

use App\Services\Payment\src\Providers\Stripe\Contracts\CustomerInterface;

/*
 *
 * API Documentation: https://stripe.com/docs/api/customers/create
 */

class StripeCustomer implements CustomerInterface
{

    public function getDataToCreate(): array
    {
        // TODO: Implement getDataToCreate() method.
        return [];
    }
}
