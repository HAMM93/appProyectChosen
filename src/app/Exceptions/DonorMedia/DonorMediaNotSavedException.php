<?php

namespace App\Exceptions\DonorMedia;

use App\Services\Logging\Facades\Logging;
use Exception;
use Illuminate\Http\Response;
use Throwable;

class DonorMediaNotSavedException extends DonorMediaException
{
    public function __construct(Throwable $e)
    {
        parent::__construct(
            trans('exception.donor_media.not_saved'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        Logging::critical($e);
    }
}
