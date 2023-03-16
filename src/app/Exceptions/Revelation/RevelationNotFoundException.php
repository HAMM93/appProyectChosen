<?php

namespace App\Exceptions\Revelation;

use App\Services\Logging\Facades\Logging;
use Exception;
use Illuminate\Http\Response;
use Throwable;

class RevelationNotFoundException extends RevelationException
{
    public function __construct(Throwable $e)
    {
        parent::__construct(
            trans('exception.package.not_found'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        Logging::critical($e);
    }
}
