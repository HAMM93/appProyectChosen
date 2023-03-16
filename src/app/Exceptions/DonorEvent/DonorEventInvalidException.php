<?php

namespace App\Exceptions\DonorEvent;

use Exception;
use Illuminate\Http\Response;
use Throwable;

class DonorEventInvalidException extends DonorEventException
{
    public function __construct()
    {
        parent::__construct(
            trans('exception.donor.donor_event_invalid'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
