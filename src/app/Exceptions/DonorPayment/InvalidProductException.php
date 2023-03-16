<?php

namespace App\Exceptions\DonorPayment;

use App\Services\Logging\Facades\Logging;
use Symfony\Component\HttpFoundation\Response;

class InvalidProductException extends DonorPaymentException
{
    public function __construct()
    {
        parent::__construct(
            trans('payment.product.items-not-available'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        Logging::security(['message' => trans('payment.product.items-not-available'), 'error' => 0]);
    }
}
