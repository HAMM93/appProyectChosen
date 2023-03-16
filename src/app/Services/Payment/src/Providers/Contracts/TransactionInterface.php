<?php

namespace App\Services\Payment\src\Providers\Contracts;

use App\Services\Payment\src\Providers\Pagarme\PagarmeTransactionService;
use App\Services\Payment\src\Providers\Stripe\Contracts\ChargeInterface;
use App\Services\Payment\src\Providers\Stripe\StripeTransactionService;

interface TransactionInterface
{
    /**
     * @param ChargeInterface $transaction_data
     * @return mixed
     * @see PagarmeTransactionService
     * @see StripeTransactionService
     */
    public function create(ChargeInterface $transaction_data);

    /**
     * @param string $status
     * @return mixed
     * @see PagarmeTransactionService
     * @see StripeTransactionService
     */
    public function isSucceeded(string $status): bool;
}
