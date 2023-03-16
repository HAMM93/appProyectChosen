<?php

declare(strict_types=1);

namespace App\Services\Payment\src\Providers\Pagarme;

use App\Services\Payment\src\Providers\Contracts\TransactionResponseInterface;
use Carbon\Carbon;

class PagarmeTransactionService implements TransactionResponseInterface
{
    protected \PagarMe\Client $client;

    public $response;

    public function __construct(\PagarMe\Client $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritDoc
     * @see https://docs.pagar.me/v4/reference#criar-transacao
     */
    public function createTransaction(array $transaction_data)
    {
        $this->response = $this->client->transactions()->create($transaction_data);
    }

    public function getTransactionId()
    {
        return $this->response->tid;
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
        return $this->response->status;
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
        return Carbon::create($this->response->date_created);
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
        // TODO: Implement getCart() method.
    }
}
