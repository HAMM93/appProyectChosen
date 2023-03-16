<?php

declare(strict_types=1);

namespace App\Core\Donation;

use App\Exceptions\Card\InvalidCardException;
use App\Exceptions\DonorPayment\InvalidProductException;
use App\Helpers\StringHelper;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\DonorMedia;
use App\Models\FinanceTransaction;
use App\Models\SimmaIntegrationPaymentTransaction;
use App\Services\Logging\Facades\Logging;
use App\Services\Payment\src\Payment;
use App\Types\DonationTypes;
use Illuminate\Contracts\Foundation\Application;

class DonationPayment
{
    /**
     * Abstract class that is used as a singleton mode,
     * the concrete class is instantiated on Service Provider
     * @var Payment|Application|mixed
     */
    private Payment $payProvider;


    public function __construct()
    {
        $this->payProvider = app(Payment::class);
    }

    /**
     * @throws InvalidProductException
     * @throws InvalidCardException
     */
    public function donationByInternalCheckout(array $data, Donor $donor): bool
    {
        $cart = new Cart($data['items_id'], intval($data['child_quantity']));
        $card = new Card($data['card']);

        $donor_data = array_merge(
            $donor->toArray(),
            [
                'chosen_id' => $donor->id,
                'country' => $data['address']['country'],
                'phone' => '+551199999999' //TODO :: (Cristiano) Fix phone cast
            ]);
        $customer = new Customer($donor_data);

        $address = new Address(
            '',
            $donor['address_street'] ?? '',
            $donor['address_city'] ?? '',
            $donor['address_complement'] ?? '',
            $donor['address_number'] ?? '',
            $donor['address_state'] ?? '',
            $donor['address_neightborhood'] ?? '',
            $donor['address_zipcode'] ?? '',
            '',
            $data['address']['country'] ?? ''
        );

        $transaction = $this->payProvider->createCreditCardTransaction($data, $card, $cart, $customer, $address);

        $media = $donor->donorMedia()->first();

        //save data into database one record for each item of transaction
        try {
            $finance_transaction = new FinanceTransaction();
            $finance_transaction->type = 'subscription';
            $finance_transaction->payment_id = $transaction->getTransactionId();
            $finance_transaction->save();

            $donation_data = [
                'company_id' => 1,
                'tracking_code' => StringHelper::getNewCHCode(),
                'donor_id' => $donor->id,
                'donor_media_id' => $media->id ?? null,
                'child_quantity' => intval($data['child_quantity']),
                'child_amount' => $cart->getDonationPrice(),
                'amount' => $cart->getAmount(),
                'revelation_type' => $cart->getTypeRevelation(),
                'revelation_amount' => $cart->getRevelationPrice(),
                'donation_status' => $this->payProvider->getStatus(),
                'payment_date' => $transaction->getCreatedAt()->format('Y-m-d H:m:s'),
                'status' => DonationTypes::STATUS_VALID,
                'simma_sync_status' => 'pending',
                'payment_trans_id' => $finance_transaction->id
            ];

            $donation = new Donation();
            $donation->fill($donation_data)->save();

            $cardString = http_build_query($data['card']);

            $simma_integration = new SimmaIntegrationPaymentTransaction();
            $simma_integration->fill([
                'card' => $cardString,
                'donor_id' => $donor->id,
                'donation_id' => $donation->id
            ]);
            $simma_integration->save();

            $donor_media = DonorMedia::where('donor_id', $donor->id)->first();
            $donor_media->donation_id = $donation->id;
            $donor_media->save();

        } catch (\Exception $exception) {
            //TODO :: Implementar rollback se alguma transação falhar
            Logging::critical($exception);
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

        return $this->payProvider->isSucceeded();
    }

    final private function makeCustomer(Donor $donor)
    {
        $this->customer = [
            'name' => $donor->name,
            'email' => $donor->email
        ];
    }
}
