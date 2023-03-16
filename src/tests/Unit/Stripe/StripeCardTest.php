<?php

namespace Tests\Unit\Stripe;

use App\Services\Payment\src\Payment;
use Tests\TestCase;

class StripeCardTest extends TestCase
{
    private $stripe;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->stripe = new \Stripe\StripeClient(
            'sk_test_51JSt1mJQfh9tmQy0HYLfcTFFiSDQRIK5iEyGLVaIN4ANOb3H3eeoGYDUlC6M9OKzlt7oRpAOmA4iY5QkiSjlQ2MD00KzspjsEw'
        );
    }

    /**
     * A basic test example.
     * API documentation: https://stripe.com/docs/api/customers/create
     * @return void
     * @throws \Stripe\Exception\ApiErrorException
     * @test
     */
    public function createNewCustomer()
    {
        $response_desired = [
            "object" => "customer",
            "address" => null,
            "balance" => 0,
            "currency" => "usd",
            "delinquent" => true,
            "description" => "My First Test Customer (created for API docs)",
            "discount" => null,
            "livemode" => false,
            "metadata" => [
                'chosen_name' => 'mexico',
                'origin' => 'external_checkout'
            ],
            'email' => 'wellington.rogati@maquinadobem.com',
            'name' => 'Wellington Rogati',
            "phone" => null,
            "preferred_locales" => [],
            "shipping" => null,
            "tax_exempt" => "none"
        ];

        $customer_data = [
            'address' => [
                'city' => 'Praia Grande', //City, district, suburb, town, or village.
                'country' => 'BR', //Two-letter country code https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
                'line1' => 'Rua Coronel Fontenele', //Address line 1 (e.g., street, PO Box, or company name).
                'line2' => '324', //Address line 2 (e.g., apartment, suite, unit, or building).
                'postal_code' => '11700720', //ZIP or postal code.
                'state' => 'São Paulo' //State, county, province, or region.
            ],
            'description' => 'Doador do sistema Chosen', //An arbitrary string that you can attach to a customer object. It is displayed alongside the customer in the dashboard.
            'email' => 'wellington.rogati@maquinadobem.com', //Customer’s email address. It’s displayed alongside the customer in your dashboard and can be useful for searching and tracking. This may be up to 512 characters.
            'name' => 'Wellington Rogati', //The customer’s full name or business name.
            'metadata' => [ // Set of key-value pairs that you can attach to an object. This can be useful for storing additional information about the object in a structured format. Individual keys can be unset by posting an empty value to them. All keys can be unset by posting an empty value to metadata.
                'chosen_name' => 'mexico',
                'origin' => 'external_checkout'
            ]
        ];

        $customer = $this->stripe->customers->create($customer_data);

        $this->assertJson(json_encode($response_desired), $customer->toJSON());

        $this->assertArrayHasKey('id', $customer->toArray());
    }

    /**
     * API Documentation: https://stripe.com/docs/api/tokens/create_card
     * @throws \Stripe\Exception\ApiErrorException
     * @test
     */
    public function createCardToken()
    {
        $response_desired = [
            "object" => "token",
            "card" => [
                "object" => "card",
                'address_city' => 'Praia Grande', //City, district, suburb, town, or village.
                'address_country' => 'BR', //Two-letter country code https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
                'address_line1' => 'Rua Coronel Fontenele', //Address line 1 (e.g., street, PO Box, or company name).
                'address_line2' => '324', //Address line 2 (e.g., apartment, suite, unit, or building).
                'address_postal_code' => '11700720', //ZIP or postal code.
                'address_state' => 'São Paulo', //State, county, province, or region.
                "brand" => "Visa",
                "country" => "BR",
                "cvc_check" => "pass",
                "dynamic_last4" => null,
                "exp_month" => 1,
                "exp_year" => 2024,
                "funding" => "credit",
                "last4" => "4242",
                "metadata" => [],
                "name" => null,
                "tokenization_method" => null,
            ],
            "livemode" => false,
            "type" => "card",
            "used" => false,
        ];

        $token = $this->stripe->tokens->create([
            'card' => [
                'number' => '4242424242424242', //The card number, as a string without any separators.
                'exp_month' => '01', //Two-digit number representing the card's expiration month.
                'exp_year' => '24', //Two- or four-digit number representing the card's expiration year.
                'cvc' => '100', //Card security code. Highly recommended to always include this value, but it's required only for accounts based in European countries.
                'name' => 'John Smith', //Cardholder's full name.
                'address_line1' => 'Rua Coronel Fontenele', //Address line 1 (e.g., street, PO Box, or company name).
                'address_line2' => '324', //Address line 2 (e.g., apartment, suite, unit, or building).
                'address_city' => 'Praia Grande', //City, district, suburb, town, or village.
                'address_state' => 'São Paulo', //State, county, province, or region.
                'address_zip' => '11700720', //ZIP or postal code.
                'address_country' => 'BR', //Two-letter country code https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
            ]
        ]);

        $this->assertJson(json_encode($response_desired), $token->toJSON());
    }

    /**
     * API Documentation: https://stripe.com/docs/api/charges/create
     * @throws \Stripe\Exception\ApiErrorException
     * @test
     */
    public function createCharge()
    {
        $token = $this->stripe->tokens->create([
            'card' => [
                'number' => '4242424242424242', //The card number, as a string without any separators.
                'exp_month' => '01', //Two-digit number representing the card's expiration month.
                'exp_year' => '24', //Two- or four-digit number representing the card's expiration year.
                'cvc' => '100', //Card security code. Highly recommended to always include this value, but it's required only for accounts based in European countries.
                'name' => 'John Smith', //Cardholder's full name.
                'address_line1' => 'Rua Coronel Fontenele', //Address line 1 (e.g., street, PO Box, or company name).
                'address_line2' => '324', //Address line 2 (e.g., apartment, suite, unit, or building).
                'address_city' => 'Praia Grande', //City, district, suburb, town, or village.
                'address_state' => 'São Paulo', //State, county, province, or region.
                'address_zip' => '11700720', //ZIP or postal code.
                'address_country' => 'BR', //Two-letter country code https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
            ]
        ]);

        $charge = $this->stripe->charges->create([
            'amount' => 1000,
            'currency' => 'brl',
            'source' => $token->id,
            'description' => 'My First Test Charge (created for API docs)',
        ]);

        $this->assertEquals('succeeded', $charge->status);
    }
}
