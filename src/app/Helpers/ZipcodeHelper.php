<?php

namespace App\Helpers;

use App\Exceptions\Zipcode\ZipcodeNotFoundException;
use App\Services\Logging\Facades\Logging;
use Illuminate\Support\Facades\Http;

class ZipcodeHelper
{
    public static function getAddressByZipcode(string $zipcode)
    {
        if (config('app.locale') === 'pt-br'){
            return self::getAddressByZipcodeBrazil($zipcode);
        }
       throw new ZipcodeNotFoundException();
    }

    private static function getAddressByZipcodeBrazil(string $zipcode)
    {
        try {
            $endpoint = config('app.api_viacep');
            $endpoint = str_replace('${zipcode}', $zipcode, $endpoint);
            $response = Http::get($endpoint)->json();

            if (isset($response['erro']) && $response['erro'] === true)
                return false;

            return [
                'cep' => $response['cep'] ?? '',
                'address_street' => $response['logradouro'] ?? '',
                'address_neightborhood' => $response['bairro'] ?? '',
                'address_city' => $response['localidade'] ?? '',
                'address_state' => $response['uf'] ?? ''
            ];

        } catch (\Throwable $e) {
            Logging::critical($e);

            return false;
        }
    }
}
