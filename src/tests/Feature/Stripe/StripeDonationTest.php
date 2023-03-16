<?php

namespace Tests\Feature\Stripe;

use Tests\TestCase;

class StripeDonationTest extends TestCase
{
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    /**
     * @test
     * doc: https://stripe.com/docs/testing
     */
    public function createCharge()
    {
        $body = [
            "document" => [
//                "type" => "rfc",
//                "value" => null,
                "type" => "cpf",
                "value" => "475.392.528-55"
            ],
            "card" => [
                "number" => "4242424242424242",
                "exp_month" => "06",
                "exp_year" => "23",
                "cvc" => "956",
                "holder_name" => "Fulano de tal"
            ],
            "address" => [
                "street" => "Rua Dílson Marone",
                "number" => "10",
                "complement" => "",
                "zipcode" => "11350570",
                "neightborhood" => "Cidade Naútica",
                "city" => "São Vicente",
                "state" => "SP",
                "country" => "br"
            ],
            'child_quantity' => 2,
            'items_id' => [1]
        ];

        $response = $this->json('POST', '/v1/payment/1', $body, ['Content-Type' => 'application/json']);

        //TODO :: (Wellington) Pending create assertions

        $this->assertEquals(200, $response->status());
    }
}
