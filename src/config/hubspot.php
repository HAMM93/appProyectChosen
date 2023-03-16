<?php

return [

    'api_url' => env('HUBSPOT_API_URL', ''),
    'api_key' => env('HUBSPOT_API_KEY', ''),
    'api_uri_contacts' => '/crm/v3/objects/contacts',
    'api_uri_lists' => '/contacts/v1/lists',
    'hubspot_list' => env('HUBSPOT_LEAD_LIST_ID', ''),
    'integration_status' => env('HUBSPOT_INTEGRATION_STATUS', 'disabled'),
    'time_interval' => env('HUBSPOT_TIME_INTERVAL', '2'),
];
