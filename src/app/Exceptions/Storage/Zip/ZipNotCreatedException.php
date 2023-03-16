<?php

namespace App\Exceptions\Storage\Zip;

use App\Services\Logging\Facades\Logging;
use Exception;
use Illuminate\Http\Response;
use Throwable;

class ZipNotCreatedException extends Exception
{
    public function __construct(Throwable $e)
    {
        parent::__construct(
            trans('exception.general.zip_not_created', ['code_error' => Logging::critical($e)]),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
