<?php

namespace App\Exceptions\DonorMedia;

use Exception;
use Illuminate\Http\Response;
use Throwable;

class DonorMediaInvalidIdException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            trans('exception.donor_media.invalid_id'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
