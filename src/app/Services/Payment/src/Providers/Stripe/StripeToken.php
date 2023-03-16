<?php

namespace App\Services\Payment\src\Providers\Stripe;

use App\Services\Payment\src\Providers\Stripe\Contracts\TokenInterface;

class StripeToken extends StripeClient implements TokenInterface
{
    private $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
        parent::__construct();
    }

    public function get()
    {
        return $this->client->tokens->create([
            'card' => [
                'number' => $this->payload['card']['number'],
                'exp_month' => $this->payload['card']['exp_month'],
                'exp_year' => $this->payload['card']['exp_year'],
                'cvc' => $this->payload['card']['cvc'],
                'name' => $this->payload['card']['holder_name'],
                'address_line1' => $this->payload['address']['street'] . ' ' . $this->payload['address']['neightborhood'],
                'address_line2' => $this->payload['address']['number'] . ' ' . $this->payload['address']['complement'],
                'address_city' => $this->payload['address']['city'],
                'address_state' => $this->payload['address']['state'],
                'address_zip' => $this->payload['address']['zipcode'],
                'address_country' => $this->payload['address']['country'],
            ]
        ]);
    }
}
