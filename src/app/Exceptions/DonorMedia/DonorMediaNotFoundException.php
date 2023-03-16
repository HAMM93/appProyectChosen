<?php

namespace App\Exceptions\DonorMedia;

use App\Services\Logging\Facades\Logging;
use Exception;
use Illuminate\Http\Response;
use Throwable;

class DonorMediaNotFoundException extends Exception
{
    public function __construct(Throwable $e = null)
    {
        if (!is_null($e)) {
            parent::__construct(
                trans('general.unexpected-error', ['code_error' => Logging::critical($e)]),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        parent::__construct(
            trans('general.unexpected-error'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        Logging::critical($e);
    }
}
