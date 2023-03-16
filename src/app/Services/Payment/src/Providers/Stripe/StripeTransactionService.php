<?php

declare(strict_types=1);

namespace App\Services\Payment\src\Providers\Stripe;


use App\Services\Payment\src\Providers\Contracts\TransactionResponseInterface;
use App\Types\PaymentTypes;
use Carbon\Carbon;
use Stripe\Exception\ApiErrorException;
use Stripe\Subscription;

class StripeTransactionService implements TransactionResponseInterface
{

    const SUCCEEDED = 'succeeded';

    protected \Stripe\StripeClient $client;

    public \Stripe\Charge $response;

    public Subscription $subscriptionResponse;

    public string $status;

    public array $cartItems = [];

    public function __construct(\Stripe\StripeClient $client)
    {
        $this->client = $client;
    }

    //TODO :: Wellington Criar uma classe para tratar somente as inscrições
    final public function createSubscription(array $payload)
    {
        $this->subscriptionResponse = $this->client->subscriptions->create($payload);

        $this->status = $this->subscriptionResponse->status;
        return $this;
    }

    /**
     * @throws ApiErrorException
     */
    final public function create(array $charge)
    {
        $this->cartItems = $charge['items'];
        unset($charge['items']);

        $this->response = $this->client->charges->create($charge);

        $this->status = $this->response->status;
        return $this;
    }

    public function isSucceeded(): bool
    {
        return ($this->getStatus() === PaymentTypes::SUBSCRIPTION_PAID);
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getTransactionId(): string
    {
        return $this->subscriptionResponse->id;
    }

    public function getAmount()
    {
        // TODO: Implement getAmount() method.
    }

    public function getAmountCaptured()
    {
        // TODO: Implement getAmountCaptured() method.
    }

    public function getAmountRefunded()
    {
        // TODO: Implement getAmountRefunded() method.
    }

    public function getBalanceTransactionId()
    {
        // TODO: Implement getBalanceTransactionId() method.
    }

    public function getBillingDetails()
    {
        // TODO: Implement getBillingDetails() method.
    }

    public function getStatus()
    {
        return $this->subscriptionResponse->status;
    }

    public function getCurrency()
    {
        // TODO: Implement getCurrency() method.
    }

    public function getLiveMode()
    {
        // TODO: Implement getLiveMode() method.
    }

    public function getSource()
    {
        // TODO: Implement getSource() method.
    }

    public function getPaymentMethodDetails()
    {
        // TODO: Implement getPaymentMethodDetails() method.
    }

    public function getRefundDetails()
    {
        // TODO: Implement getRefundDetails() method.
    }

    public function getCreatedAt(): Carbon
    {
        return Carbon::createFromTimestamp($this->subscriptionResponse->created);
    }

    public function getFraudDetails()
    {
        // TODO: Implement getFraudDetails() method.
    }

    public function getInvoice()
    {
        // TODO: Implement getInvoice() method.
    }

    public function getCart()
    {
        return $this->cartItems;
    }
}
