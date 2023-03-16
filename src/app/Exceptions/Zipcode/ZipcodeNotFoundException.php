<?php

namespace App\Exceptions\Zipcode;

use App\Services\Logging\Facades\Logging;
use Exception;
use Illuminate\Http\Response;
use Throwable;

class ZipcodeNotFoundException extends ZipcodeException
{
    public function __construct()
    {
        parent::__construct(
            trans('general.miss-configuration'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
