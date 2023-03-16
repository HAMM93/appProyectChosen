<?php

namespace App\Services\Payment\src\Providers\Stripe;

use App\Services\Payment\src\Providers\Stripe\Contracts\ChargeInterface;
use App\Services\Payment\src\Providers\Stripe\Contracts\TokenInterface;

class StripeCharge implements ChargeInterface
{
    private array $payload;
    private string $token;

    public function __construct(array $payload, TokenInterface $token)
    {
        $this->payload = $payload;
        $this->token = $token->get();
    }

    public function get(): array
    {
        return [
            'amount' => $this->payload['cart']['amount'],
            'currency' => 'brl',
            'source' => $this->token->id,
            'description' => 'My First Test Charge (created for API docs)',
        ];
    }
}
