<?php

namespace App\Exceptions\Donor;


use Illuminate\Http\Response;
use Throwable;

class DonorWithoutMediaException extends DonorException
{
    public function __construct()
    {
        parent::__construct(
            trans('exception.donor.without_media'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
