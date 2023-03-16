<?php

namespace App\Services\CRM;

use Illuminate\Support\Facades\Http;
use App\Exceptions\HubspotIntegration\HubspotIntegrationNotCreatedException;
use App\Exceptions\HubspotIntegration\HubspotIntegrationNotFoundException;
use App\Exceptions\HubspotIntegration\HubspotIntegrationNotAddedToList;

class HubSpot
{
    private $api_key;
    private $api_url;
    private $httpClient;


    public function __construct()
    {
        $this->api_url = config('hubspot.api_url');
        $this->api_key = config('hubspot.api_key');

        $this->httpClient = Http::withHeaders(['Content-Type'=>'application/json'])
            ->baseUrl(config('hubspot.api_url'));
    }

    public function createNewContact(array $data): \stdClass
    {
        try {
            $contact_data = ['properties' => $data];

            $response = $this->httpClient
                ->post((config('hubspot.api_uri_contacts')).'?hapikey='.$this->api_key, $contact_data);

            if ( $response->failed() ) {
                $response->throw();
            }

            return json_decode($response->body());

        } catch (\Exception $exception) {
            throw new HubspotIntegrationNotCreatedException($exception);
        }
    }

    public function spliteContactName(string $name): array
    {
        $name_parts = explode(" ", $name);
        if ( count($name_parts) > 1 ) {
            $last_name = $name_parts[count($name_parts)-1];
            unset($name_parts[count($name_parts)-1]);
            $first_name = implode(" ", $name_parts);

        } else {
            $first_name = implode(" ", $name_parts);
            $last_name = "";
        }

        return ['first_name'=>$first_name, 'last_name'=>$last_name];
    }

    public function checkIfContactExistsByEmail(string $email): bool
    {
        try {
            $contact = $this->getContactByEmail($email);
            return ( isset($contact->id) );

        } catch (\Exception $exception) {
            throw new HubspotIntegrationNotFoundException($exception);
        }
    }

    public function getContactByEmail(string $email): \stdClass
    {
        $filters = [
            'filterGroups' => [
                [
                    'filters' => [
                        [
                           'propertyName' => 'email',
                            'value' => $email,
                            'operator' => 'EQ'
                        ]
                    ]
                ]
            ]
        ];

        try {
            $response = $this->httpClient
                ->post((config('hubspot.api_uri_contacts')).'/search?hapikey='.$this->api_key, $filters);

            if ( $response->failed() ) {
                $response->throw();
            }

            $contacts = json_decode($response->body());

            if ( $contacts->total < 1 || !isset($contacts->results[0]) ) {
                return (new \stdClass());
            }

            return $contacts->results[0];

        } catch (\Exception $exception) {
            throw new HubspotIntegrationNotFoundException($exception);
        }
    }

    public function updateContact($hubspot_register, $data)
    {
        $contact_data = ['properties' => $data];

        return $this->httpClient
            ->patch((config('hubspot.api_uri_contacts')).'/'.$hubspot_register->id.'?hapikey='.$this->api_key, $contact_data);
    }

    public function addContactToList(array $data):bool
    {
        try {
            $response = $this->httpClient
                ->post((config('hubspot.api_uri_lists')).'/'.$data['list_id'].'/add'.'?hapikey='.$this->api_key, $data['contacts']);

            if ( $response->failed() ) {
                $response->throw();
            }

        } catch (\Exception $exception) {
            throw new HubspotIntegrationNotAddedToList($exception);
        }

        return true;
    }

    public function removeContactToList($data)
    {
        return $this->httpClient
            ->post((config('hubspot.api_uri_lists')).'/'.$data['list_id'].'/remove'.'?hapikey='.$this->api_key, $data['contacts']);
    }

}
