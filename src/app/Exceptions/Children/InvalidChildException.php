<?php

namespace App\Exceptions\Children;

use App\Services\Logging\Facades\Logging;
use Exception;
use Illuminate\Http\Response;
use Throwable;

class InvalidChildException extends Exception
{
    public function __construct(Throwable $e)
    {
        parent::__construct(
            trans('exception.children.not_found', ['code_error' => Logging::critical($e)]),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
