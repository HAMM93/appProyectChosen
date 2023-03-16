<?php

declare(strict_types=1);

namespace App\Services\Payment\src\Providers\Stripe;

use App\Core\Donation\Address;
use App\Models\StripeProducts;
use App\Services\Payment\src\Payment;
use App\Services\Payment\src\Providers\Contracts\CardInterface;
use App\Services\Payment\src\Providers\Contracts\CartInterface;
use App\Services\Payment\src\Providers\Contracts\CustomerInterface;
use App\Services\Payment\src\Providers\Contracts\TransactionResponseInterface;
use App\Types\PaymentTypes;
use Stripe\Exception\ApiErrorException;

class StripeClient extends Payment
{
    const SUCCEEDED = 'succeeded';
    protected \Stripe\StripeClient $client;
    public bool $isSucceeded = false;
    public string $status;
    public \Stripe\Charge $transaction;

    public function __construct()
    {
        parent::__construct();
        $this->client = new \Stripe\StripeClient($this->apiKey);
    }

    /**
     * @throws ApiErrorException
     */
    public function createCreditCardTransaction
    (
        array             $transaction_data,
        CardInterface     $card,
        CartInterface     $cart,
        CustomerInterface $customer,
        Address           $address
    ): TransactionResponseInterface
    {
        $items_id = collect($cart->getItems())->transform(function ($item) {
            return $item['id'];
        });

        $items = StripeProducts::whereIn('product_id', $items_id)->select('price_object_id')->get();

        if (!$items) throw new \Exception('Invalid Product', 422);

        $items['items'] = collect($items)->transform(function ($item) use ($cart) {
            return [
                'price' => $item['price_object_id'],
                'quantity' => $cart->getDonationQuantity()
            ];
        })->toArray();

        $cus = $this->client->customers->create([
            'name' => $customer->getName(),
            'description' => trans('payment.customer.description'),
            'email' => $customer->getEmail(),
            'metadata' => [trans('general.origin') => trans('general.system_name')]
        ]);

        $token = $this->client->tokens->create([
            'card' => [
                'number' => $card->getNumber(),
                'exp_month' => $card->getExpMonth(),
                'exp_year' => $card->getExpYear(),
                'cvc' => $card->getCVC(),
            ],
        ]);

        $this->client->customers->createSource(
            $cus->id,
            ['source' => $token->id]
        );

        $payload = [
            'customer' => $cus->id,
            'items' => $items['items']
        ];

        $transaction = new StripeTransactionService($this->client);
        $transaction->createSubscription($payload);
        $this->isSucceeded = $transaction->isSucceeded();

        $this->status = PaymentTypes::RELATION_TYPES[$transaction->getStatus()];

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
