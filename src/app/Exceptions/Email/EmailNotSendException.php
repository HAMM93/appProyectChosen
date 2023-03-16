<?php

namespace App\Exceptions\Email;

use App\Services\Logging\Facades\Logging;
use Exception;
use Illuminate\Http\Response;
use Throwable;

class EmailNotSendException extends EmailException
{
    public function __construct(Throwable $e)
    {
        parent::__construct(
            trans('exception.general.email_not_send', ['code_error' => Logging::critical($e)]),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
