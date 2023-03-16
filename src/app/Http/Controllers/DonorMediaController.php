<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\Donor\DonorNotFound;
use App\Exceptions\DonorMedia\DonorMediaAlreadyRegisteredException;
use App\Exceptions\DonorMedia\DonorMediaInvalidTokenException;
use App\Exceptions\DonorMedia\DonorMediaNotFoundException;
use App\Exceptions\DonorMedia\DonorMediaNotSavedException;
use App\Exceptions\Storage\Zip\ZipNotCreatedException;
use App\Helpers\Response\ResponseAPI;
use App\Helpers\ZipHelper;
use App\Http\Requests\DonorMediaRequest;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\DonorMedia;
use App\Models\ResendDonorPhoto;
use App\Services\Storage\AWSS3Service;
use App\Types\DonorMediaTypes;
use App\Types\PaymentTypes;
use Aws\S3\Exception\S3Exception;
use Carbon\Carbon;
use \Illuminate\Http\JsonResponse;

class DonorMediaController extends Controller
{
    /**
     * @param DonorMediaRequest $request
     * @return JsonResponse
     * @throws DonorMediaNotFoundException
     */
    public function index(DonorMediaRequest $request): JsonResponse
    {
        if ($request->filled('term')) {
            $name = (!filter_var(strtolower($request->get('term')), FILTER_VALIDATE_EMAIL)) ? $request->get('term') : '';
            $email = !$name ? $request->get('term') : '';
        } else {
            $name = '';
            $email = '';
        }

        $status_validation = $request->get('validation_status');

        $donation_status = $request->get('donation_status');

        $per_page = (int) $request->get('pp');

        $page = (int) $request->get('pg');

        $donor_media = new DonorMedia();
        $result = $donor_media->listDonorMediasToValidate(
            $status_validation,
            $donation_status,
            $per_page,
            $page,
            $name,
            $email
        );

        $filters = [
            'donation_status' => [
                PaymentTypes::ALL => trans('general.filter.donation.all'),
                PaymentTypes::PENDING => trans('general.filter.donation.pendent'),
                PaymentTypes::PAID => trans('general.filter.donation.paid'),
                PaymentTypes::PROCESSING => trans('general.filter.donation.processing'),
                PaymentTypes::REFUSED => trans('general.filter.donation.refused')
            ],
            'validation_status' => [
                DonorMediaTypes::ALL => trans('general.filter.donor_media.all'),
                DonorMediaTypes::PENDING => trans('general.filter.donor_media.pending'),
                DonorMediaTypes::APPROVED => trans('general.filter.donor_media.approved'),
                DonorMediaTypes::REPROVED => trans('general.filter.donor_media.reproved'),
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
     * @param DonorMediaRequest $request
     * @param Donor $donor
     * @return JsonResponse
     * @throws DonorMediaAlreadyRegisteredException
     * @throws DonorMediaNotSavedException
     */
    public function store(DonorMediaRequest $request, Donor $donor): JsonResponse
    {
        if ($donor->medias()) {
            throw new DonorMediaAlreadyRegisteredException();
        }

        try {
            $s3 = new AWSS3Service();
            $s3->putImageFromBase64($request->get('donor_photo_base64'), 'donors/checkout');

            DonorMedia::create([
                'donor_id' => $donor->id,
                'media_source' => $s3->getUriFile(),
                'media_type' => 'photo'
            ]);
        } catch (S3Exception $exception) {
            throw new \Exception($exception->getMessage(), 200);
        } catch (\Throwable $exception) {
            //TODO:: Criar Lógica para apagar a imagem do S3
            throw new DonorMediaNotSavedException($exception);
        }

        //TODO::Alterar Retorno Padrozinado
        return ResponseAPI::created(['message' => trans('general.donor-media-successfully-created')]);
    }

    /**
     * @param DonorMediaRequest $request
     * @param Donor $donor
     * @param string $token
     * @return JsonResponse
     * @throws DonorMediaInvalidTokenException
     * @throws DonorMediaNotSavedException
     */
    public function changeDonorPhoto(DonorMediaRequest $request, Donor $donor, string $token): JsonResponse
    {
        $token_validation = $donor->resendPhoto()->where('token', $token)
            ->where('expired_at', '>', Carbon::now()->format('Y-m-d H:m:s'))
            ->where('status', 'valid')
            ->first();

        $last_donation = $donor->lastDonation()->first();

        if (!$token_validation || !$last_donation) {
            throw new DonorMediaInvalidTokenException();
        }

        $s3 = new AWSS3Service();

        try {
            $s3->putImageFromBase64($request->get('photo_b64'), config('files.paths_s3.donor_photo'));

            $new_media = DonorMedia::create([
                'donor_id' => $donor->id,
                'donation_id' => $last_donation->id,
                'media_source' => $s3->getUriFile(),
                'media_type' => 'photo',
            ]);

            Donation::where('id', $last_donation->id)->update(['donor_media_id' => $new_media->id]);

            ResendDonorPhoto::where('token', $token)->update(['status' => "finished"]);
        } catch (\Exception $e) {
            throw new DonorMediaNotSavedException($e);
        }
        return ResponseAPI::results(['messages' => trans('general.donor-media-successfully-created')]);
    }

    /**
     * @throws ZipNotCreatedException
     * @throws \Exception
     */
    public function generateZipWithDonorImages(DonorMediaRequest $request): JsonResponse
    {
        try {
            foreach ($request->get('donors_id') as $donor) {
                $donor_media = DonorMedia::select('media_source')->where('donor_id', $donor)->first();

                if ($donor_media->media_source !== null) {
                    $links[] = $donor_media->media_source;
                }
            }
        } catch (\Exception $e) {
            throw new DonorNotFound($e);
        }

        $zip = ZipHelper::generateZipWithDonorPhotosByLink($links);

        $s3 = new AWSS3Service();

        $s3->putObject($zip, config('files.paths_s3.zip_donor'));

        return ResponseAPI::results(['link' => $s3->getUriFile()]);
    }
}
