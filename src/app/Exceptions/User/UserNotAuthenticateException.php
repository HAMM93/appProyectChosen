<?php

namespace App\Exceptions\User;


use Exception;
use Illuminate\Http\Response;

class UserNotAuthenticateException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            trans('auth.user-not-authenticated'),
            Response::HTTP_UNAUTHORIZED
        );
    }
}
