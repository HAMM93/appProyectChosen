<?php

namespace App\Exceptions\Donor;

use App\Services\Logging\Facades\Logging;
use Illuminate\Http\Response;
use Throwable;

class DonorOrChildrenNotFound extends DonorException
{
    public function __construct(Throwable $e = null)
    {
        if (!is_null($e)) {
            parent::__construct(
                trans('exception.general.donor_or_children_not_found', ['code_error' => Logging::critical($e)]),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        parent::__construct(
            trans('exception.general.donor_or_children_not_found'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
