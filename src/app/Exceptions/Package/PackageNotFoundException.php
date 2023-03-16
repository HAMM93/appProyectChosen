<?php

namespace App\Exceptions\Package;

use App\Services\Logging\Facades\Logging;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class PackageNotFoundException extends Exception
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
