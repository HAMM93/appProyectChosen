<?php

namespace App\Http\Controllers;

use App\Exceptions\Donor\DonorWithoutMediaException;
use App\Exceptions\DonorMedia\DonorMediaNotUpdatedException;
use App\Exceptions\DonorMedia\DonorMediaTokenNotCreatedException;
use App\Helpers\Response\ResponseAPI;
use App\Mail\DisapprovedPhotoMail;
use App\Models\Donor;
use App\Models\DonorMedia;
use App\Types\DonorMediaTypes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ValidateImageController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws DonorMediaNotUpdatedException
     * @throws ValidationException
     */
    public function validateImage(Request $request)
    {
        $this->validate($request, [
            'image_id' => 'required|array',
            'image_id.*' => 'integer'
        ]);

        $image_id = $request->get('image_id');

        if (count($image_id) > DonorMediaTypes::MIN_QUANTITY_PHOTOS && count($image_id) <= DonorMediaTypes::MAX_QUANTITY_PHOTOS) {
            foreach ($image_id as $id) {
                try {
                    DonorMedia::where('id', $id)
                        ->where('validation_status', DonorMediaTypes::PENDING)
                        ->update(['validation_status' => DonorMediaTypes::APPROVED]);
                } catch (\Exception $e) {
                    //TODO:: implementar exception
                    return response()->json(['errors' => $e->getMessage()], $e->getCode());
                }
            }

            return ResponseAPI::results(['message' => trans('image.approved.multiple')]);
        } else {
            $this->validate($request, [
                'status' => 'required|in:' . DonorMediaTypes::APPROVED . ',' . DonorMediaTypes::REPROVED
            ]);

            try {
                $media = DonorMedia::where('id', $image_id[0])
                ->with('donor')->first();

                if (!isset($media->donor)) {
                    throw new DonorWithoutMediaException();
                }

                $media->update(['validation_status' => $request->get('status')]);

                if ($request->get('status') === DonorMediaTypes::REPROVED) {
                    $token = $this->createToken($media->donor)->token;

                    Mail::to($media->donor->email)->send( new DisapprovedPhotoMail($media->donor->id, $media->donor->name, $media->donor->email, $token));
                }
            } catch (\Exception $e) {
                throw new DonorMediaNotUpdatedException($e);
            }

            return ResponseAPI::results(['messages' => trans("image.{$request->get('status')}.single")]);
        }
    }

    /**
     * @param Donor $donor
     * @return Model
     * @throws DonorMediaTokenNotCreatedException
     */
    private function createToken(Donor $donor): Model
    {
        $token = $donor->resendPhoto()->create([
            'token' => Str::uuid(),
            'expired_at' => Carbon::now()->addDay()
        ]);

        if (!$token) {
            throw new DonorMediaTokenNotCreatedException();
        }

        return $token;
    }
}
