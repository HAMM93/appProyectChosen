<?php

namespace App\Services\Payment\src;

use App\Services\Payment\src\Providers\Pagarme\PagarmeClient;
use App\Services\Payment\src\Providers\Stripe\StripeClient;

class PaymentManager
{

    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function resolve()
    {
        $provider = $this->config['payment']['default_provider'];

        if ($provider === 'pagarme') {
            return new PagarmeClient();
        } else if ($provider === 'stripe') {
            return new StripeClient();
        } else {
            throw new \Exception('miss-configuration-payment-provider');
        }
    }
}
