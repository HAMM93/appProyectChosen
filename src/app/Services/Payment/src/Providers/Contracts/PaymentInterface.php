<?php

declare(strict_types=1);

namespace App\Services\Payment\src\Providers\Contracts;

use App\Core\Donation\Address;
use App\Services\Payment\src\Providers\Pagarme\PagarmeClient;
use App\Services\Payment\src\Providers\Stripe\StripeClient;

/**
 * @see PagarmeClient
 * @see StripeClient
 */
interface PaymentInterface
{
    /**
     * @param array $transaction_data
     * @param CardInterface $card
     * @param CartInterface $cart
     * @param CustomerInterface $customer
     * @param Address $address
     * @return mixed
     */
    public function createCreditCardTransaction(
        array $transaction_data,
        CardInterface $card,
        CartInterface $cart,
        CustomerInterface $customer,
        Address $address
    ): TransactionResponseInterface;

    public function isSucceeded():bool;
    public function getStatus():string;
}
