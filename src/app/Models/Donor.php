<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\DocumentCast;
use App\Casts\PhoneNumberCast;
use App\Core\Pagination;
use App\Exceptions\Donor\DonorNotFound;
use App\Types\DonationTypes;
use App\Types\DonorEventTypes;
use App\Helpers\Response\ResponseAPI;
use App\Types\DonorMediaTypes;
use App\Types\DonorTypes;
use App\Types\PackageTypes;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

/**
 * @property mixed name
 * @property mixed email
 */
class Donor extends Model
{
    use HasFactory;

    protected $table = 'donors';

    protected $fillable = [
        'simma_donor_id', 'name', 'document', 'document_type', 'email', 'phone', 'ocupation',
        'birthdate', 'gender', 'address_resumed', 'address_street', 'address_number',
        'address_complement', 'address_zipcode', 'address_neightborhood',
        'address_city', 'address_state', 'photo', 'status', 'simma_sync_status',
        'simma_sync_date', 'simma_sync_details', 'extra_info', 'first_name', 'last_name'
    ];

    protected $casts = [
        'phone' => PhoneNumberCast::class,
        'document' => DocumentCast::class
    ];

    public function photos(): HasMany
    {
        return $this->hasMany(DonorMedia::class)->where('media_type', 'photo');
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function lastDonation(): HasOne
    {
        return $this->hasOne(Donation::class)->orderByDesc('created_at');
    }

    public function donorMedia(): HasOne
    {
        return $this->hasOne(DonorMedia::class)->orderByDesc('created_at');
    }

    public function resendPhoto(): HasMany
    {
        return $this->hasMany(ResendDonorPhoto::class);
    }

    /**
     * @param Donor $donor
     * @return Model
     * @throws DonorNotFound
     */
    public static function getDonorById(Donor $donor): Model
    {
        try {
            return $donor->where('donors.id' , $donor->id)
                ->join('donor_medias','donors.id','donor_medias.donor_id')
                ->select(
                    'donors.id', 'name', 'document', 'email', 'phone', 'ocupation', 'birthdate', 'gender',
                    'address_street', 'address_number', 'address_complement', 'address_zipcode', 'address_neightborhood',
                    'address_city', 'address_state', 'donors.created_at', 'donor_medias.media_source', 'donor_medias.validation_status'
                )->first();
        } catch (\Throwable $exception) {
            throw new DonorNotFound($exception);
        }
    }

    /**
     * @param Donor $donor
     * @return object
     * @throws DonorNotFound
     */
    public static function getDonorByIdWithAppointments(Donor $donor): object
    {
        try {
            $last_donation = $donor->lastDonation()->select('donation_status', 'id')->first();

            $result = $donor->where('donors.id', $donor->id)
                ->leftjoin('donor_medias', function ($join) {
                    $join->on('donors.id', '=', 'donor_medias.donor_id');
                })
                ->leftjoin('donor_event', function ($join) {
                    $join->on('donors.id', '=', 'donor_event.donor_id');
                })->leftjoin('events', function ( $join) {
                    $join->on('events.id', '=', 'donor_event.event_id');
                })
                ->select(
                    DB::raw('donor_event.id as donor_event_id'), 'donors.simma_donor_id','donors.id', 'name', 'document', 'email', 'phone', 'ocupation', 'birthdate', 'gender',
                    'address_street', 'address_number', 'address_complement', 'address_zipcode', 'address_neightborhood',
                    'address_city', 'address_state', 'donors.created_at', 'donor_medias.media_source','donor_medias.id as donor_media_id',
                    'donor_medias.validation_status', 'donor_event.donation_id',
                    DB::raw("CASE
                        WHEN events.event_date IS NULL THEN '" . PackageTypes::PENDING . "'
                        WHEN events.event_date < NOW() THEN '" . PackageTypes::ACCOMPLISHED . "'
                        WHEN events.event_date > NOW() THEN '" . PackageTypes::SCHEDULED . "'
                    END as event_status")
                )
//                ->where('donor_event.status', DonorEventTypes::VALID) TODO::Verificar a necessidade de filtrar o status
                ->first();

                $appointments = $result->donation_id ? DonationChildren::getDonationChildrenByDonationId($last_donation->id) : [];

                return (object)[
                    'donor_data' => $result,
                    'donation_data' => $last_donation,
                    'appointments' => $appointments,
                ];

        } catch (\Throwable $exception) {
            throw new DonorNotFound($exception);
        }
    }

    public function medias()
    {
        return DonorMedia::where('donor_id', $this->id)->first();
        //TODO :: verificar implementação abaixo
        //return $this->hasOne(DonorMedia::class);
    }

    /**
     * @param string $donation_status
     * @param string $email
     * @param string $name
     * @param int $page
     * @param int $per_page
     * @return object
     * @throws DonorNotFound
     */
    public static function getDonorsByLastDonation(
        string $donation_status,
        string $email,
        string $name,
        int    $page,
        int    $per_page
    ): object
    {
        try {
            $donors = Donor::with('donorMedia:id,donor_id,media_source,media_type','lastDonation:id,donor_id,donation_status,child_quantity,payment_date')
                ->when(!empty($email), function ($query) use ($email) {
                    $query->where('email', strtolower($email));
                })
                ->when(!empty($name), function ($query) use ($name) {
                    $query->where('name', 'LIKE', '%' . $name . '%');
                })
                ->select('id', 'name', 'simma_donor_id')
                ->get()->toArray();
        } catch (\Exception $e) {
            throw new DonorNotFound($e);
        }


        foreach ($donors as $key => $donor) {
            if ($donor['last_donation'] == null) {
                unset($donors[$key]);
            } else if ($donation_status != 'all' && $donor['last_donation']['donation_status'] != $donation_status) {
                unset($donors[$key]);
            }
        }

        $collect_donors = (new Collection($donors));

        $collect_donors = $collect_donors->values();

        $paginate = new Pagination();

        $paginate->setPaginate($collect_donors, $per_page, $page, DonorTypes::DEFAULT_PER_PAGE);

        $donors_result = $collect_donors->skip($paginate->getSkipValue())->take($paginate->getPerPage())->toArray();

        return (object)[
            'results' => array_values($donors_result),
            'paginate' => [
                'page' => $page,
                'total' => $paginate->getCount(),
                'has_more' => $paginate->getHasMore(),
            ]
        ];
    }

    public static function getDonorsWithoutPackage()
    {
        try {
            return Donor::select(DB::raw('donors.id as donor_id'))
                ->join('donations', 'donations.donor_id', 'donors.id')
                ->join('donor_medias', 'donor_medias.donation_id', 'donations.id')
                ->where('donations.donation_status', DonationTypes::PAID)
                ->where('donations.status', DonationTypes::STATUS_VALID)
                ->where('donors.simma_sync_status', DonorTypes::SIMMA_SYNCED)
                ->where('donor_medias.validation_status', DonorMediaTypes::APPROVED)
                ->groupBy('donors.id')
                ->get();
        } catch (\Exception $e) {
            throw new DonorNotFound($e);
        }
    }
}
