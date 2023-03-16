<?php

namespace App\Exceptions\User;

use App\Services\Logging\Facades\Logging;
use Exception;
use Illuminate\Http\Response;
use Throwable;

class PasswordNotRedefinedException extends Exception
{
    public function __construct(Throwable $e)
    {
        parent::__construct(
            trans('exception.user.password_not_redefined', ['code_error' => Logging::critical($e)]),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
