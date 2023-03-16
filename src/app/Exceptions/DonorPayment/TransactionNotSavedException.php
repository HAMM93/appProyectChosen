<?php

namespace App\Exceptions\DonorPayment;

use App\Services\Logging\Facades\Logging;

class TransactionNotSavedException extends DonorPaymentException
{
    public function __construct(\Throwable $exception)
    {
        parent::__construct($exception);
        Logging::critical($exception);
    }
}
