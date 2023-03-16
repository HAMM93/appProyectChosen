<?php

namespace App\Exceptions\HubspotIntegration;

use App\Services\Logging\Facades\Logging;
use Illuminate\Http\Response;
use Throwable;

class HubspotIntegrationNotAddedToList extends HubspotIntegrationException
{
    public function __construct(Throwable $e)
    {
        parent::__construct(
            trans('exception.hubspot_integration.error_on_add_contact_to_list'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
