<?php

namespace App\Exceptions\DonorMedia;

use Illuminate\Http\Response;

class DonorMediaTokenNotCreatedException extends DonorMediaException
{
    public function __construct()
    {
        parent::__construct(
            trans('exception.donor_media.not_saved'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
