<?php

namespace App\Console\Commands\SimmaIntegration;

use App\Console\Commands\SimmaIntegration\SimmaIntegration;
use App\Models\SimmaIntegrationPaymentTransaction;
use App\Services\Logging\Facades\Logging;
use App\Helpers\WordHelper;
use Carbon\Carbon;
use Exception;

class SimmaIntegrationBrazil extends SimmaIntegration
{
    /**
     * Overwrite signature to test just the command
     *
     * @var string
     */
    protected $signature = 'simma:chosen-sync-br';

    /**
     * @param \Illuminate\Database\Eloquent\Model $donation
     * @return void
     */
    protected function sendDataSimma($donation)
    {
        $this->message('Sending data to simma middleware [BRA]...', 'info');

        $card_decrypted = $this->getTransaction($donation);

        $payload = array_merge(
            [
                "vCountryCode" => "BRA",
                "vRegionID" => 3, //TODO :: Verificar
                "vMotivationID" => 3977,
                "vCategoryID" => 10,
                "vSubCategoryID" => 1624,
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
            ],
            [
                "vAmount" => $donation['amount'],
                "vCardTypeID" => 2,
                "vCreditCard" => $card_decrypted['number'],
                "vHolderName" => $card_decrypted['holder_name'],
                "vCardExpiration" => $card_decrypted['expiration'],
                "vCardValidation" => $card_decrypted['cvc'],
                "vPaymentDate" => Carbon::create((string) $donation['created_at'])->format('Y-m-d'),
                "vDesignationID" => 237,
                "vPaymentDay" => Carbon::create((string) $donation['created_at'])->format('d'), //TODO :: Dia do pagamento
                "vStatus" => 123, //TODO :: Verificar qual valor fixo deve estar aqui
                "successPaymentTransaction" => false
            ]
        );

        try {
            $response = $this->httpClient->post("/payments/save-partner-payment", $payload)->json();

            if (isset($response['message']) && $response['message'] === 'success') {
                $this->message('Message: ' . $response['message']);

                $this->addSimmaSyncStatus();

                $this->message('Integrating donor id: ' . $donation['donor']['id'], 'info');
                $this->message('Integrating donor doc: ' . $payload['vReferenceID'], 'info');

                if (!isset($response['data']['partner_id'])) {
                    $this->setDonationSyncStatusError([
                        'data' => ['response' => json_encode($response)],
                        'exception' => 'Fail on get data to integration with SIMMA'
                    ]);
                }

                $this->addSimmaDonorID($response['data']['partner_id']);
            } else {
                Logging::info(['message' => json_encode($response), 'error_code' => 500]);
            }
        } catch (Exception $e) {
            $this->setDonationSyncStatusError($e->getMessage());
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $donation
     * @return void
     */
    private function getTransaction($donation): array
    {
        $transaction = SimmaIntegrationPaymentTransaction::where('donation_id', $donation['id'])
            ->where('donor_id', $donation['donor']['id'])
            ->first();

        if (!isset($transaction->card)) {
            $this->setDonationSyncStatusError([
                'data' => [
                    'donor_id' => $donation['donor']['id'],
                    'donation_id' => $donation['id'],
                ],
                'exception' => "Payment transaction of donor ({$donation['donor']['id']} - {$donation['donor']['name']}) not found"
            ]);
        }

        return $this->getCardEncrypted($transaction->card);
    }
}
