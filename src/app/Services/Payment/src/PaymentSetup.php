<?php

declare(strict_types=1);

namespace App\Services\Payment\src;


class PaymentSetup
{
    /**
     * @var string
     */
    public string $provider;

    /**
     * @var bool
     */
    public bool $setup;

    public function __construct()
    {
        $this->provider = config('payment.default_provider');

        if (config('payment.providers.' . $this->provider . '.setup') !== null) {
            $this->setup = config('payment.providers.' . $this->provider . '.setup');
        }
    }

}
