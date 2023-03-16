<?php

namespace App\Exceptions\DonorDocument;

use App\Services\Logging\Facades\Logging;
use Exception;
use Illuminate\Http\Response;
use Throwable;

class DonorDocumentNotConfiguredException extends Exception
{
    public function __construct(Throwable $e = null)
    {
        parent::__construct(
            trans('exception.donor_document.type_not_configured', ['code_error' => Logging::security($e)]),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
