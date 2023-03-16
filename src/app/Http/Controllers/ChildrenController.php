<?php

namespace App\Http\Controllers;

use App\Exceptions\Children\InvalidChildException;
use App\Exceptions\Children\InvalidChildIdException;
use App\Exceptions\Storage\Zip\ZipNotCreatedException;
use App\Helpers\Response\ResponseAPI;
use App\Helpers\ZipHelper;
use App\Http\Requests\ChildrenRequest;
use App\Models\DonationChildren;
use App\Services\Storage\AWSS3Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChildrenController extends Controller
{
    /**
     * @param ChildrenRequest $request
     * @return JsonResponse
     * @throws InvalidChildException
     * @throws ZipNotCreatedException
     */
    public function generateZipWithChildrenImages(ChildrenRequest $request): JsonResponse
    {
        try {
            $childs = DonationChildren::select('child_photo')
                ->whereIn('id', $request->get('children_id'))
                ->get();

            foreach ($childs as $child) {
                if ($child->child_photo !== null) {
                    $links[] = $child->child_photo;
                }
            }

        } catch (\Exception $e) {
            throw new InvalidChildException($e);
        }

        $s3 = new AWSS3Service();

        $zip = ZipHelper::generateZipByLink($links);

        $s3->putObject($zip, config('files.paths_s3.zip_children'));

        return ResponseAPI::results(['link' => $s3->getUriFile()]);
    }

    public function getDataFromSimma($project_id, $child_id)
    {
        try {
            $uri = sprintf('%s/childs/show/%s/%s', config('simma.api_base_url'), $project_id, $child_id);

            $client = Http::withHeaders(['Content-Type' => 'application/json'])
                ->get($uri)
                ->json();

            return ResponseAPI::results($client['data']);

        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), 422);
        }
    }
}
