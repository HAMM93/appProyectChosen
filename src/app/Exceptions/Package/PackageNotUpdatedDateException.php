<?php

namespace App\Exceptions\Package;


use App\Services\Logging\Facades\Logging;
use Illuminate\Http\Response;
use Throwable;

class PackageNotUpdatedDateException extends PackageException
{
    public function __construct(Throwable $e)
    {
        parent::__construct(
            trans('exception.package.date.not_updated'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        Logging::critical($e);
    }
}
