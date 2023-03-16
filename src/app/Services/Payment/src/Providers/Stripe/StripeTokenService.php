<?php

namespace App\Services\Payment\src\Providers\Stripe;

use App\Services\Payment\src\Providers\Stripe\Contracts\TokenInterface;

class StripeTokenService extends StripeClient implements TokenInterface
{
    private $token;

    public function __construct(array $payload)
    {
        $this->token = $payload;
    }

    public function get()
    {
        return $this->client->tokens->create($this->token->get());
    }
}
