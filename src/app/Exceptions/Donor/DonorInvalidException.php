<?php

namespace App\Exceptions\Donor;

use App\Services\Logging\Facades\Logging;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;

class DonorInvalidException extends DonorException
{
    public function __construct($e)
    {
        parent::__construct(
            trans('exception.donor.error-during-save'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        Logging::critical($e);
    }
}
