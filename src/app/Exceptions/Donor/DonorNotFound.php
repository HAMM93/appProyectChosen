<?php

namespace App\Exceptions\Donor;

use App\Services\Logging\Facades\Logging;
use Illuminate\Http\Response;
use Throwable;

class DonorNotFound extends DonorException
{
    public function __construct(Throwable $e = null)
    {
        if (!is_null($e)) {
            parent::__construct(
                trans('exception.donor.not_found', ['code_error' => Logging::critical($e)]),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } else {
            parent::__construct(
                trans('exception.donor.not_found'),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }
}
