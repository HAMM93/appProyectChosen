<?php

namespace App\Exceptions\DonorMedia;


use Illuminate\Http\Response;

class DonorMediaInvalidTokenException extends DonorMediaException
{
    public function __construct()
    {
        parent::__construct(
            trans('exception.donor_media.invalid_token'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
