<?php

namespace App\Exceptions\DonorMedia;


use App\Services\Logging\Facades\Logging;
use Illuminate\Http\Response;
use Throwable;

class DonorMediaNotUpdatedException extends DonorMediaException
{
    public function __construct(Throwable $e)
    {
        parent::__construct(
            trans('exception.donor_media.not_updated', ['code_error' => Logging::critical($e)]),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
