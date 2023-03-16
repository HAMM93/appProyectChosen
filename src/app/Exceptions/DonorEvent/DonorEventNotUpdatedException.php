<?php

namespace App\Exceptions\DonorEvent;

use Illuminate\Http\Response;

class DonorEventNotUpdatedException extends DonorEventException
{
    public function __construct()
    {
        parent::__construct(
            trans('exception.donor.not_removed_at_package'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
