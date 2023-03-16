<?php

declare(strict_types=1);

namespace App\Services\Payment\src\Providers\Pagarme;

use App\Core\Donation\Address;
use App\Services\Payment\src\Payment;
use App\Services\Payment\src\Providers\Contracts\CardInterface;
use App\Services\Payment\src\Providers\Contracts\CartInterface;
use App\Services\Payment\src\Providers\Contracts\CustomerInterface;
use App\Services\Payment\src\Providers\Contracts\TransactionResponseInterface;

class PagarmeClient extends Payment
{
    const SUCCEEDED = 'paid';
    protected \PagarMe\Client $client;
    public bool $isSucceeded = false;
    public string $status;

    public function __construct()
    {
        $this->client = new \PagarMe\Client(config('payment.api_key'));
        parent::__construct();
    }

    public function createCreditCardTransaction
    (
        array         $transaction_data,
        CardInterface $card,
        CartInterface $cart,
        CustomerInterface $customer,
        Address $address
    ): TransactionResponseInterface
    {
        $card_id = $this->client->cards()->create([
            'holder_name' => $card->getHolderName(),
            'number' => $card->getNumber(),
            'expiration_date' => $card->getExpMonth() . $card->getExpYear(),
            'cvv' => $card->getCVC()
        ]);

        $pagarmeCustomer = new PagarmeCustomer($customer);

        $data = [
            'amount' => $cart->getAmount(),
            'card_id' => $card_id->id, //TODO implement create card method on service class
            'payment_method' => 'credit_card',
            'customer' => $pagarmeCustomer->getCustomer(),
            'billing' => [
                'name' => $pagarmeCustomer->getName(),
                'address' => [
                    'country' => $address->getCountry(),
                    'street' => $address->getStreet(),
                    'street_number' => $address->getNumber(),
                    'state' => $address->getState(),
                    'city' => $address->getCity(),
                    'neighborhood' => $address->getNeighborhood(),
                    'zipcode' => $address->getZipcode()
                ]
            ],
            'items' => $cart->getItems()
        ];

        $transaction = new PagarmeTransactionService($this->client);
        $transaction->createTransaction($data);
        $this->isSucceeded = $transaction->getStatus() === self::SUCCEEDED;

        $this->status = $transaction->getStatus();
        return $transaction;
    }

    public function isSucceeded(): bool
    {
        return $this->isSucceeded;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
