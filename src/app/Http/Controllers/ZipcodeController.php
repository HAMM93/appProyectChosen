<?php

namespace App\Http\Controllers;

use App\Helpers\Response\ResponseAPI;
use App\Helpers\ZipcodeHelper;
use App\Rules\ZipcodeRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ZipcodeController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException|\App\Exceptions\Zipcode\ZipcodeNotFoundException
     */
    public function search(Request $request)
    {
        $this->validate($request, [
            'zipcode' => ['required' , new ZipcodeRule()],
        ]);

        $response = ZipcodeHelper::getAddressByZipcode($request->get('zipcode'));

        if ($response)
            return ResponseAPI::results(['address' => $response]);

        return ResponseAPI::notFound(['message' => trans('general.zipcode-not-found')]);
    }
}
