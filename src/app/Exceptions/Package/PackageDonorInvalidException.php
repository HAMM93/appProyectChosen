<?php

namespace App\Exceptions\Package;


use App\Services\Logging\Facades\Logging;
use Illuminate\Http\Response;
use Throwable;

class PackageDonorInvalidException extends PackageException
{
    public function __construct(Throwable $e)
    {
        parent::__construct(
            trans('exception.donor.invalid', ['code_error' => Logging::critical($e)]),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
