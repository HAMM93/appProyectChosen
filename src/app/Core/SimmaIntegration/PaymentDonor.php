<?php

namespace App\Core\SimmaIntegration;

use App\Helpers\WordHelper;
use App\Models\Donation;
use App\Models\Donor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentDonor
{
    public function __construct()
    {
    }

    public function getPendingIntegration()
    {
        $integrationPending = Donation::where('simma_sync_status', 'pendent')
            ->with('donor')
            ->orderBy('id', 'desc')
            ->take(1)
            ->get()->toArray();

        $this->sendDataToSimmaMiddleware($integrationPending[0]);
    }

    public function sendDataToSimmaMiddleware(array $integrationPending)
    {
        $payload = [
            "vIndividual" => true,
            "vHouseNumber" => $integrationPending['donor']['address_number'],
            "vStreet1" => $integrationPending['donor']['address_street'],
            "vStreet2" => $integrationPending['donor']['address_complement'],
            "vStreet3" => $integrationPending['donor']['address_neightborhood'],
            "vCity" => $integrationPending['donor']['address_city'],
            "vState" => $integrationPending['donor']['address_state'],
            "vCountryCode" => "BRA", //TODO :: Normalizar código de pais segundo tabela de 3 letras
            "vRegionID" => 3, //TODO :: Verificar
            "vPostalCode" => $integrationPending['donor']['address_state'],
            "vReferenceID" => $integrationPending['donor']['document'],
            "vGivenName" => WordHelper::getFirstName($integrationPending['donor']['name']),
            "vMiddleName" => WordHelper::getMiddleName($integrationPending['donor']['name']),
            "vFamilyName" => WordHelper::getLastName($integrationPending['donor']['name']),
            "vGenderCode" => $integrationPending['donor']['gender'],
            "vBirthDate" => $integrationPending['donor']['birthdate'],
            "vPhoneNumber" => $integrationPending['donor']['phone']['country'] . $integrationPending['donor']['phone']['value'],
            "vEmailAddress" => $integrationPending['donor']['email'],
            "vAmount" => $integrationPending['amount'],
            "vCardTypeID" => 2,
            //TODO :: Implementar criptografia dos dados de cartão e armazenamento dos mesmos
            "vCreditCard" => "4012001037141112",
            "vHolderName" => "Lucas Dev",
            "vCardExpiration" => "2022-10-01",
            "vCardValidation" => "123",
            "vPaymentDate" => "2021-06-21",
            "vTypeID" => 1, //TODO :: Verificar qual valor fixo deve estar aqui
            "vMotivationID" => 3977,
            "vDesignationID" => 237,
            "vCategoryID" => 10,
            "vPaymentDay" => Carbon::create($integrationPending['created_at'])->format('d'), //TODO :: Dia do pagamento
            "vSubCategoryID" => 1624,
            "vPartner" => 123,
            "vStatus" => 123, //TODO :: Verificar qual valor fixo deve estar aqui
            "successPaymentTransaction" => false
        ];

        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->post('http://192.168.15.58:3000/payments/save-partner-payment', $payload)->json();

        if (isset($response) && $response['message'] === 'success') {
            $donation = Donation::find($integrationPending['id']);
            $donation->update(['simma_sync_status' => 'finished']);

            $donor = Donor::find($integrationPending['donor']['id']);
            $donor->update(['simma_donor_id' => $response['data']['partner_id']]);
        }

    }

}
