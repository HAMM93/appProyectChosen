<?php

namespace App\Exceptions\DonorMedia;

use App\Exceptions\Donor\DonorNotFound;
use Exception;
use Illuminate\Http\Response;
use Throwable;

class DonorMediaAlreadyRegisteredException extends DonorMediaException
{
    public function __construct()
    {
        parent::__construct(
            trans('exception.donor_media.already_registered'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
