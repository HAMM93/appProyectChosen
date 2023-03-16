<?php

namespace App\Http\Controllers;

use App\Exceptions\Package\PackageInvalidTypeException;
use App\Exceptions\Package\PackageNotFoundException;
use App\Exceptions\Package\PackageNotUpdatedDateException;
use App\Helpers\Response\ResponseAPI;
use App\Http\Requests\PackageRequest;
use App\Models\Event;
use App\Repositories\PackageRepository;
use App\Types\DonationTypes;
use App\Types\PackageTypes;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;

class PackageController extends Controller
{
    private PackageRepository $packageRepository;

    public function __construct()
    {
        $this->packageRepository = new PackageRepository();
    }

    /**
     * @param PackageRequest $request
     * @return JsonResponse
     * @throws PackageInvalidTypeException
     */
    public function index(PackageRequest $request): JsonResponse
    {
        $status = $request->get('status');

        $per_page = $request->get('pp');

        $page = $request->get('pg');

        $results = null;

        try {
            if ($request->filled('term')) {
                $term = $request->get('term');

                if (str_contains(strtoupper($term), PackageTypes::CH_CODE)) {
                    $results = $this->packageRepository->getPackagesByCHCode($term, $status, $per_page, $page);
                }

                if (is_numeric($term)) {
                    $results = $this->packageRepository->getPackagesByPartnerID($term, $status, $per_page, $page);
                }

                if (ctype_alpha($term)) {
                    $results = $this->packageRepository->getPackagesByName($term, $status, $per_page, $page);
                }

                if (filter_var($term, FILTER_VALIDATE_EMAIL)) {
                    $results = $this->packageRepository->getPackagesByEmail($term, $status, $per_page, $page);
                }
            } else {
                $results = $this->packageRepository->getPackagesByStatus($status, $per_page, $page);
            }
        } catch (Exception $e) {
            throw new PackageInvalidTypeException($e);
        }

        $results = collect($results)->transform(function ($item) {
            if ($item['status'] == PackageTypes::PENDING) {
                $item['status'] = trans('general.filter.package.' . PackageTypes::PENDING);
            }

            if ($item['status'] == PackageTypes::ACCOMPLISHED) {
                $item['status'] = trans('general.filter.package.' . PackageTypes::ACCOMPLISHED);
            }

            if ($item['status'] == PackageTypes::SCHEDULED) {
                $item['status'] = trans('general.filter.package.' . PackageTypes::SCHEDULED);
            }

            return $item;
        });

        $response = [
            'results' => $results,
            'filters' => [
                'package_status' => [
                    PackageTypes::ALL => trans('general.filter.package.' . PackageTypes::ALL),
                    PackageTypes::PENDING => trans('general.filter.package.' . PackageTypes::PENDING),
                    PackageTypes::ACCOMPLISHED => trans('general.filter.package.' . PackageTypes::ACCOMPLISHED),
                    PackageTypes::SCHEDULED => trans('general.filter.package.' . PackageTypes::SCHEDULED)
                ]
            ],
            'paginate' => [
                'page' => (int)$request->get('pg'),
                'total' => $this->packageRepository->count,
                'has_more' => $this->packageRepository->has_more,
            ]
        ];

        return ResponseAPI::results($response);
    }

    /**
     * @param PackageRequest $request
     * @param Event $package
     * @return JsonResponse
     * @throws PackageNotFoundException
     */
    public function show(PackageRequest $request, Event $package): JsonResponse
    {
        $per_page = $request->get('pp');

        $page = $request->get('pg');

        $results = $this->packageRepository->getPackageContent($package, $per_page, $page);

        $results = collect($results)->transform(function ($item) {
            foreach ($item['package_donors'] as $donor) {
                if ($donor->donation_status == DonationTypes::PAID) {
                    $donor->donation_status = trans('general.filter.donation.' . DonationTypes::PAID);
                }

                if ($donor->donation_status == DonationTypes::REFUSED) {
                    $donor->donation_status = trans('general.filter.donation.' . DonationTypes::REFUSED);
                }

                if ($donor->donation_status == DonationTypes::PROCESSING) {
                    $donor->donation_status = trans('general.filter.donation.' . DonationTypes::PROCESSING);
                }
            }

            return $item;
        });

        $response = [
            'results' => $results,
            'paginate' => [
                'page' => (int)$request->get('pg'),
                'total' => $this->packageRepository->count,
                'has_more' => $this->packageRepository->has_more
            ]
        ];

        return ResponseAPI::results($response);
    }

    /**
     * @param PackageRequest $request
     * @param Event $package
     * @return JsonResponse
     * @throws PackageNotUpdatedDateException
     */
    public function update(PackageRequest $request, Event $package): JsonResponse
    {
        $date = Carbon::parse($request->get('event_date'));

        Event::updateDateEvent($package, $date);

        return ResponseAPI::results(['message' => trans('general.date_updated')]);
    }
}
