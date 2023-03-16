<?php

namespace App\Services\Payment\src\Providers\Contracts;

use Carbon\Carbon;

interface TransactionResponseInterface
{
    public function getTransactionId();

    public function getAmount();

    public function getAmountCaptured();

    public function getAmountRefunded();

    public function getBalanceTransactionId();

    /**
     * Stripe contains address and customer information
     * @return mixed
     */
    public function getBillingDetails();

    public function getStatus();

    public function getCurrency();

    public function getLiveMode();

    /**
     * Stripe field "source"
     * @return mixed
     */
    public function getSource();

    /**
     * Stripe field "payment_method_details"
     * @return mixed
     */
    public function getPaymentMethodDetails();

    public function getRefundDetails();

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon;

    public function getFraudDetails();

    public function getInvoice();

    public function getCart();

}
