<?php /** @noinspection PhpUndefinedVariableInspection */

namespace App\Http\Controllers;

use App\Exceptions\Children\ChildrenNotFoundException;
use App\Exceptions\Donation\DonationNotFoundException;
use App\Exceptions\Package\PackageInvalidTypeException;
use App\Exceptions\Revelation\RevelationNotFoundException;
use App\Helpers\Response\ResponseAPI;
use App\Types\RevelationTypes;
use App\Exceptions\Donor\{
    DonorOrChildrenNotFound,
    DonorNotFound
};
use App\Helpers\VideoHelper;
use App\Http\Requests\RevelationRequest;
use App\Models\Donation;
use App\Models\DonationChildren;
use App\Models\Donor;
use App\Models\Event;
use App\Models\TokenRevelation;
use App\Repositories\RevelationRepository;
use App\Services\Storage\AWSS3Service;
use App\Types\DonationTypes;
use App\Types\PackageTypes;
use Dompdf\Dompdf;
use Exception;
use App\Services\Logging\Facades\Logging;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class RevelationController extends Controller
{
    private RevelationRepository $revelationRepository;

    public function __construct()
    {
        $this->revelationRepository = new RevelationRepository();
    }

    /**
     * @param RevelationRequest $request
     * @return JsonResponse
     * @throws PackageInvalidTypeException
     */
    public function index(RevelationRequest $request): JsonResponse
    {
        $term = $request->filled('term') ? $request->get('term') : null;

        $per_page = (int)$request->get('pp');

        $page = (int)$request->get('pg');

        try {
            if (isset($term)) {
                if (str_contains(strtoupper($term), PackageTypes::CH_CODE)) {
                    $results = $this->revelationRepository->getRevelationByCHCode($term, $per_page, $page);
                }

                if (strtoupper(preg_replace('/[0-9]+/', '', $term)) === DonationTypes::CH_TRACKING_PREFIX) {
                    $results = $this->revelationRepository->getRevelationByTrackingCode($term, $per_page, $page);
                }

                if (is_numeric($term)) {
                    $results = $this->revelationRepository->getRevelationByPartnerID($term, $per_page, $page);
                }

                if (ctype_alpha($term)) {
                    $results = $this->revelationRepository->getRevelationByName($term, $per_page, $page);
                }

                if (filter_var($term, FILTER_VALIDATE_EMAIL)) {
                    $results = $this->revelationRepository->getRevelationByEmail($term, $per_page, $page);
                }
            } else {
                $results = $this->revelationRepository->getRevelationsAll($per_page, $page);
            }
        } catch (Exception $e) {
            throw new PackageInvalidTypeException($e);
        }

        $results = collect($results)->transform(function ($item) {
            if ($item['event_date'] == null) {
                $item['event_date'] = PackageTypes::PENDING;
            }

            return $item;
        });

        $response = [
            'results' => $results,
            'paginate' => [
                'page' => (int)$request->get('pg'),
                'total' => $this->revelationRepository->count,
                'has_more' => $this->revelationRepository->has_more
            ]
        ];

        return ResponseAPI::results($response);
    }

    /**
     * @param RevelationRequest $request
     * @throws RevelationNotFoundException
     * @throws DonationNotFoundException
     * @throws DonorNotFound
     */
    public function store(RevelationRequest $request): void
    {
        $donor = Donor::find($request->get('donor_id'));

        if(!$donor)
            throw new DonorNotFound();

        $donation = Donation::where('donor_id', $donor->id)->first();

        if(!$donation)
            throw new DonationNotFoundException();

        $child_id = $request->get('child_id');
        $child_name = $request->get('child_name');
        $child_city = $request->get('child_city');

        $s3 = new AWSS3Service();

        $video = VideoHelper::validateVideoBase64($request->get('child_video'));
        $s3->putObject($video, 'children/' . trim(strtolower($request->get('child_name'))) . '/');
        $child_video = $s3->getUriFile();

        $s3->putImageFromBase64($request->get('child_photo'), 'children/' . trim(strtolower($request->get('child_name'))) . '/');
        $child_photo = $s3->getUriFile();

        if ($request->get('letter_type') === RevelationTypes::DOCUMENT_PDF){
            $s3->putDocumentFromBase64($request->get('letter_photo'), 'children/' . trim(strtolower($request->get('child_name'))) . '/');
            $letter_child = $s3->getUriFile();
        } else {
            $s3->putImageFromBase64($request->get('letter_photo'), 'children/' . trim(strtolower($request->get('child_name'))) . '/');
            $letter_child = $s3->getUriFile();
        }

        $child = $this->revelationRepository->linkChildToDonation(
            $donation->id,
            $child_id,
            $child_name,
            $child_city,
            $child_photo,
            $child_video,
            $letter_child
        );
//        $html = view('pdf', compact('child'));
//
//        $pdf = new Dompdf(array('enable_remote' => true));
//        $pdf->loadHtml($html);
//        $pdf->setPaper('A4', 'portrait');
//        $pdf->render();
//        $output = $pdf->output();
//
//        file_put_contents('/tmp/test.pdf', $output);
        //TODO:: (Joel 21/10) Implement PDF generation
    }

    /**
     * @param RevelationRequest $request
     * @param Event $revelation
     * @return JsonResponse
     * @throws RevelationNotFoundException
     */
    public function show(RevelationRequest $request, Event $revelation): JsonResponse
    {
        $per_page = (int)$request->get('pp');

        $page = (int)$request->get('pg');

        $results = $this->revelationRepository->getContentFromRevelation($revelation, $per_page, $page);

        $results = collect($results)->transform(function ($item) {
            foreach ($item['package_donors'] as $donor) {
                if ($donor->revelation_type == RevelationTypes::DIGITAL) {
                    $donor->revelation_type = trans('general.filter.revelation.' . RevelationTypes::DIGITAL);
                }

                if ($donor->revelation_type == RevelationTypes::PHYSICAL) {
                    $donor->revelation_type = trans('general.filter.revelation.' . RevelationTypes::DIGITAL);
                }
            }

            return $item;
        });

        $response = [
            'results' => $results,
            'paginate' => [
                'page' => (int)$request->get('pg'),
                'total' => $this->revelationRepository->count,
                'has_more' => $this->revelationRepository->has_more
            ]
        ];

        return ResponseAPI::results($response);
    }

    /**
     * @param DonationChildren $child
     * @return JsonResponse
     * @throws ChildrenNotFoundException
     */
    public function showChildren(DonationChildren $child): JsonResponse
    {
        return ResponseAPI::results(['results' => $this->revelationRepository->getChildrenInfo($child)]);
    }

    /**
     * @param Donor $donor
     * @param DonationChildren $child
     * @return JsonResponse
     * @throws DonorOrChildrenNotFound
     */
    public function sendRevelationMail(Donor $donor, DonationChildren $child): JsonResponse
    {
        $donation = Donation::select(['donors.name', 'donors.email', 'donors.id', 'donations.id'])
            ->where('donations.id', $child->donation_id)
            ->join('donors', 'donations.donor_id', 'donors.id')
            ->where('donors.id', $donor->id)
            ->first();

        if (!$donation) {
            throw new DonorOrChildrenNotFound();
        }

        try {
            Mail::to($donor->email)->queue(new \App\Mail\RevelationMail($donor, $child));

            return response()->json(['message' => trans('general.revelation_email_sending_to_donor')], Response::HTTP_OK);
        } catch (Exception $e) {
            Logging::critical($e);

            return ResponseAPI::unprocessableEntity(['message' => trans('general.error_to_send_email')]);
        }
    }

    /**
     * @param string $token
     * @return JsonResponse
     */
    public function showChildrenMuralRevelation(string $token): JsonResponse
    {
        $token = TokenRevelation::where('token', $token)
            ->where('active', true)
            // ->where('expired_at', '>', Carbon::now()->format('Y-m-d H:m:s'))
            ->first();

        if (!$token) {
            return response()->json([
                'message' => trans('general.token_invalid')
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!$child = DonationChildren::find($token->child_id))
            throw new ChildrenNotFoundException();

        if (!$donor = Donor::find($token->donor_id))
            throw new DonorNotFound();

        try {
            return response()->json(['results' => [
                'donor' => ['id' => $donor->id, 'name' => $donor->name],
                'child' => [
                    'id' => $child->id,
                    'name' => $child->child_name,
                    'age' => $child->child_age,
                    'city' => $child->child_city,
                    'photo' => $child->child_photo,
                    'video' => $child->child_video,
                    'letter' => $child->letter_photo
                ]
            ]], Response::HTTP_OK);
        } catch (Exception $e) {
            Logging::critical($e);

            return ResponseAPI::unprocessableEntity(['message' => trans('general.something_wrong')]);
        }
    }

    /**
     * @param RevelationRequest $request
     * @return JsonResponse
     * @throws RevelationNotFoundException
     */
    public function getRevelationsOccurred(RevelationRequest $request): JsonResponse
    {
        $donor_id = $request->filled('donor_id') ? $request->get('donor_id') : 0;

        $child_id = $request->filled('child_id') ? $request->get('child_id') : 0;

        $revelations = $this->revelationRepository->getAllRevelationOccurred($donor_id, $child_id);

        return ResponseAPI::results(['results' => $revelations]);
    }
}
