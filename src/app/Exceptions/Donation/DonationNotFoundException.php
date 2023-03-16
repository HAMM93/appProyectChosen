<?php

namespace App\Exceptions\Donation;

use App\Services\Logging\Facades\Logging;
use Exception;
use Illuminate\Http\Response;
use Throwable;

class DonationNotFoundException extends Exception
{
    public function __construct(Throwable $e = null)
    {
        if ($e !== null) {
            parent::__construct(
                trans('exception.general.donation_not_found', ['code_error' => Logging::critical($e)]),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } else {
            parent::__construct(
                trans('exception.general.donation_not_refused'),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }
}
