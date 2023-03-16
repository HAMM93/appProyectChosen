<?php

namespace App\Exceptions\Donor;


use App\Services\Logging\Facades\Logging;
use Illuminate\Http\Response;
use Throwable;

class DonorNotUpdatedException extends DonorException
{
    public function __construct(Throwable $e)
    {
        parent::__construct(
            trans('exception.donor.not_updated', ['code_error' => Logging::critical($e)]),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
