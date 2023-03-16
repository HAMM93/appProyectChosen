<?php

declare(strict_types=1);

namespace App\Services\Payment\src;

use App\Services\Payment\src\Providers\Contracts\PaymentInterface;

abstract class Payment implements PaymentInterface
{
    protected const MAX_UNIQUE_ITEMS = 2;

    /**
     * Customer object data
     * @var array
     */
    public array $customer = [];

    /**
     * Cart object data
     * @var array
     */
    public array $card = [];

    /**
     * Address of customer
     * @var array
     */
    public array $address = [];

    /**
     * @var string|mixed
     */
    public string $countryCode;

    /**
     * @var string|mixed
     */
    public string $currency;

    /**
     * @var array|mixed
     */
    public array $documents;

    /**
     * @var string|\Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    protected string $apiKey;

    public function __construct()
    {
        $country = config('app.default_country');
        $countryConfig = config('app.countries.' . $country);
        $this->apiKey = config('payment.api_key');
        $this->currency = $countryConfig['currency'];
        $this->documents = $countryConfig['documents'];
        $this->countryCode = $countryConfig['country_code'];
    }
}
