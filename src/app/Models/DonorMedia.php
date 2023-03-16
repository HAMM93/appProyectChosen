<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Pagination;
use App\Exceptions\DonorMedia\DonorMediaNotFoundException;
use App\Types\DonorMediaTypes;
use App\Types\PaymentTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonorMedia extends Model
{
    use HasFactory;

    protected $table = 'donor_medias';

    protected $fillable = [
        'donor_id',
        'media_type',
        'media_source',
        'validation_status',
        'donation_id'
    ];

    protected $casts = [];

    private bool $has_more = false;
    private int $count;


    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function donation()
    {
        return $this->belongsTo(Donation::class, 'donation_id', 'id');
    }

    /**
     * @param string $status_validation
     * @param string $status_donation
     * @param int $per_page
     * @param int $page
     * @param string $name
     * @param string $email
     * @return object
     * @throws DonorMediaNotFoundException
     */
    public function listDonorMediasToValidate(
        string $status_validation,
        string $status_donation,
        int    $per_page,
        int    $page,
        string $name,
        string $email
    ): object
    {
        try {
            $donor_media = DonorMedia::where('media_type', 'photo')
                ->when($status_validation === 'all', function ($query) {
                    $query->whereIn('validation_status', DonorMediaTypes::ALL_TYPES);
                })
                ->when($status_validation !== 'all', function ($query) use ($status_validation) {
                    $query->where('validation_status', $status_validation);
                })
                ->join('donations', function ($join) use ($status_donation) {
                    $join->on('donation_id', '=', 'donations.id')
                        ->when($status_donation === 'all', function ($query) {
                            $query->whereIn('donations.donation_status', PaymentTypes::ALL_TYPES);
                        })
                        ->when($status_donation !== 'all', function ($query) use ($status_donation) {
                            $query->where('donations.donation_status', $status_donation);
                        });
                })->join('donors', function ($join) use ($email, $name) {
                    $join->on('donor_medias.donor_id', '=', 'donors.id')
                        ->when(!empty($email), function ($query) use ($email) {
                            $query->where('donors.email', strtolower($email));
                        })
                        ->when(!empty($name), function ($query) use ($name) {
                            $query->where('donors.name', 'LIKE', '%' . $name . '%');
                        });
                })
                ->select(
                    'donors.id', 'donors.name', 'donors.email', 'donors.simma_donor_id', 'donations.donor_media_id',
                    'donor_medias.media_source', 'donor_medias.validation_status', 'donor_medias.donation_id',
                    'donations.donation_status', 'donations.child_quantity'
                )->orderBy('id', 'desc');

        } catch (\Throwable $exception) {
            throw new DonorMediaNotFoundException($exception);
        }

        $paginate = new Pagination();

        $paginate->setPaginate($donor_media, $per_page, $page, DonorMediaTypes::DEFAULT_PER_PAGE);

        $medias = $donor_media->skip($paginate->getSkipValue())->take($paginate->getPerPage())->get();

        return (object)[
            'results' => $medias,
            'paginate' => [
                'page' => $page,
                'total' => $paginate->getCount(),
                'has_more' => $paginate->getHasMore(),
            ]
        ];
    }
}
