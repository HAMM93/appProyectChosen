<?php

namespace App\Exceptions\HubspotIntegration;

use App\Services\Logging\Facades\Logging;
use Illuminate\Http\Response;
use Throwable;

class HubspotIntegrationNotFoundException extends HubspotIntegrationException
{
    public function __construct(Throwable $e)
    {
        parent::__construct(
            trans('exception.hubspot_integration.error_on_consult'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
