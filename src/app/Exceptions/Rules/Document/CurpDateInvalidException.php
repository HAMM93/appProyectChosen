<?php

namespace App\Exceptions\Rules\Document;

use App\Services\Logging\Facades\Logging;
use Exception;
use Illuminate\Http\Response;
use Throwable;

class CurpDateInvalidException extends Exception
{
    public function __construct(Throwable $e)
    {
        parent::__construct(
            trans('exception.general.curp_invalid_date', ['code_error' => Logging::critical($e)]),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
