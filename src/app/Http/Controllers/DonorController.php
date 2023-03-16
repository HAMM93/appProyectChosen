<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\Donor\DonorInvalidException;
use App\Exceptions\Donor\DonorNotFound;
use App\Exceptions\Donor\DonorNotUpdatedException;
use App\Exceptions\DonorEvent\DonorEventInvalidException;
use App\Exceptions\DonorEvent\DonorEventNotUpdatedException;
use App\Helpers\Response\ResponseAPI;
use App\Http\Requests\DonorRequest;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\DonorEvent;
use App\Types\DocumentTypes;
use App\Types\DonationTypes;
use App\Types\DonorEventTypes;
use App\Types\RegionTypes;
use App\Types\PaymentTypes;
use Illuminate\Http\JsonResponse;

class DonorController extends Controller
{
    /**
     * @param DonorRequest $request
     * @return JsonResponse
     * @throws DonorInvalidException
     */
    public function store(DonorRequest $request): JsonResponse
    {
        try {
            if (config('app.default_country') == RegionTypes::MEXICO && !$request->filled('document_data')) {
                if ($request->get('foreign_person') == true)
                    $request->merge(['document' => DocumentTypes::MEXICAN_RFC_FOREIGN_PERSON_DEFAULT_VALUE]);
                else
                    $request->merge(['document' => DocumentTypes::MEXICAN_RFC_DEFAULT_VALUE]);

                $request->merge(['document_type' => DocumentTypes::MEXICAN_RFC]);
            }

            $document_data = $request->filled('document_data') ? $request->get('document_data') : null;

            if ($document_data){
                $request->merge(['document' => $document_data['value']]);
                $request->merge(['document_type' => $document_data['type']]);
            }

            $request->merge(['name' => $request->get('first_name'). ' '. $request->get('last_name')]);

            $donor = new Donor();
            $donor->fill($request->all())->save();

            return ResponseAPI::created(['donor' => ['id' => $donor->id]]);

        } catch (\Throwable $e) {
            throw new DonorInvalidException($e);
        }
    }

    /**
     * @param Donor $donor
     * @return JsonResponse
     * @throws DonorNotFound
     */
    public function show(Donor $donor): JsonResponse
    {
        $donor_data = Donor::getDonorById($donor);

        return ResponseAPI::results(['result' => $donor_data]);
    }

    /**
     * @param Donor $donor
     * @return JsonResponse
     * @throws DonorNotFound
     */
    public function showProfileWithAppointments(Donor $donor): JsonResponse
    {
        $result = Donor::getDonorByIdWithAppointments($donor);

        return ResponseAPI::results(['result' => $result]);
    }

    /**
     * @param DonorRequest $request
     * @return JsonResponse
     * @throws DonorNotFound
     */
    public function listDonorsByLastDonation(DonorRequest $request): JsonResponse
    {
        if ($request->filled('term')) {
            $name = (!filter_var(strtolower($request->get('term')), FILTER_VALIDATE_EMAIL)) ? $request->get('term') : '';
            $email = !$name ? $request->get('term') : '';
        } else {
            $name = '';
            $email = '';
        }

        $donation_status = $request->get('donation_status');

        $per_page = (int)$request->get('pp');

        $page = (int)$request->get('pg');

        $result = Donor::getDonorsByLastDonation($donation_status, $email, $name, $page, $per_page);

        $filters = [
            'donation_status' => [
                PaymentTypes::ALL => trans('general.filter.donation.all'),
                PaymentTypes::PENDING => trans('general.filter.donation.pendent'),
                PaymentTypes::PAID => trans('general.filter.donation.paid'),
                PaymentTypes::PROCESSING => trans('general.filter.donation.processing'),
                PaymentTypes::REFUSED => trans('general.filter.donation.refused')
            ]
        ];

        $response = [
            'results' => $result->results,
            'filters' => $filters,
            'paginate' => $result->paginate,
        ];

        return ResponseAPI::results($response);
    }

    /**
     * @param DonorRequest $request
     * @param Donor $donor
     * @return JsonResponse
     * @throws DonorNotUpdatedException
     */
    public function update(DonorRequest $request, Donor $donor): JsonResponse
    {
        try {
            $donor->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'ocupation' => $request->ocupation,
                'birthdate' => $request->birthdate,
                'gender' => $request->gender,
                'address_street' => $request->address_street,
                'address_number' => $request->address_number,
                'address_complement' => $request->address_complement,
                'address_zipcode' => $request->address_zipcode,
                'address_neightborhood' => $request->address_neightborhood,
                'address_city' => $request->address_city,
                'address_state' => $request->address_state,
            ]);
        } catch (\Exception $e) {
            throw new DonorNotUpdatedException($e);
        }

        return ResponseAPI::results(['message' => 'ok']);
    }

    /**
     * @param DonorEvent $event
     * @return JsonResponse
     * @throws DonorEventInvalidException
     * @throws DonorEventNotUpdatedException
     */
    public function removeDonorFromPackage(DonorEvent $event): JsonResponse
    {
        if ($event->status !== DonorEventTypes::VALID) {
            throw new DonorEventInvalidException();
        }

        Donation::where('id', $event->donation_id)
            ->where('donation_status', DonationTypes::PAID)
            ->update(['status' => DonationTypes::STATUS_VALID]);

        if (!$event->update(['status' => DonorEventTypes::INVALID])) {
            throw new DonorEventNotUpdatedException();
        }

        return ResponseAPI::results(['message' => trans('general.donor.removed_from_package', ['package' => $event->id])]);
    }
}
