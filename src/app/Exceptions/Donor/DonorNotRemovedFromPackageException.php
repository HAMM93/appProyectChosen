<?php

namespace App\Exceptions\Donor;


use App\Services\Logging\Facades\Logging;
use Illuminate\Http\Response;
use Throwable;

class DonorNotRemovedFromPackageException extends DonorException
{
    public function __construct(Throwable $e = null)
    {
        if (!is_null($e)) {
            parent::__construct(
                trans('exception.donor.not_removed_from_package', ['code_error' => Logging::critical($e)]),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        parent::__construct(
            trans('exception.donor.not_removed_at_package'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
