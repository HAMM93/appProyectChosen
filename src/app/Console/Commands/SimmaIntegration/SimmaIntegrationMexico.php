<?php

namespace App\Console\Commands\SimmaIntegration;

use App\Console\Commands\SimmaIntegration\SimmaIntegration;
use App\Helpers\WordHelper;
use App\Services\Logging\Facades\Logging;
use Exception;

class SimmaIntegrationMexico extends SimmaIntegration
{
    /**
     * Overwrite signature to test just the command
     *
     * @var string
     */
    protected $signature = 'simma:chosen-sync-mx';

    /**
     * @param \Illuminate\Database\Eloquent\Model $donation
     * @return void
     */
    protected function sendDataSimma($donation)
    {
        $this->message('Sending data to simma middleware [MEX]...', 'info');

        $payload = array_merge(
            [
                "vCountryCode" => "MEX",
                "vRegionID" => 1, //TODO :: Verificar
                "vMotivationID" => 162,
                "vCategoryID" => 1,
                "vSubCategoryID" => 385,
            ],
            [
                "vIndividual" => true,
                "vHouseNumber" => $donation['donor']['address_number'],
                "vStreet1" => $donation['donor']['address_street'],
                "vStreet2" => $donation['donor']['address_complement'],
                "vStreet3" => $donation['donor']['address_neightborhood'],
                "vCity" => $donation['donor']['address_city'],
                "vState" => $donation['donor']['address_state'],
                "vPostalCode" => $donation['donor']['address_state'],
                "vReferenceID" => $donation['donor']['document'],
                "vGivenName" => WordHelper::getFirstName($donation['donor']['name']),
                "vMiddleName" => WordHelper::getMiddleName($donation['donor']['name']),
                "vFamilyName" => WordHelper::getLastName($donation['donor']['name']),
                "vGenderCode" => $donation['donor']['gender'],
                "vBirthDate" => $donation['donor']['birthdate'],
                "vPhoneNumber" => $donation['donor']['phone']['country'] . $donation['donor']['phone']['value'],
                "vEmailAddress" => $donation['donor']['email'],
                "vTypeID" => 1, //TODO :: Verificar qual valor fixo deve estar aqui
            ]
        );

        try {
            $response = $this->httpClient->post("/partners/store", $payload)->json();

            if (isset($response['message']) && $response['message'] === 'success') {
                $this->message('Message: ' . $response['message']);

                $this->addSimmaSyncStatus();

                $this->message('Integrating donor id: ' . $donation['donor']['id'], 'info');
                $this->message('Integrating donor doc: ' . $payload['vReferenceID'], 'info');

                $this->addSimmaDonorID($response['data']['partner_id']);
            } else {
                Logging::info(['message' => json_encode($response), 'error_code' => 500]);
            }
        } catch (Exception $e) {
            $this->setDonationSyncStatusError($e->getMessage());
        }
    }
}

