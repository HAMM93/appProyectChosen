<?php


namespace App\Helpers;


use App\Rules\ImageDecodedRule;
use App\Rules\VideoDecodedRule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class VideoHelper
{
    /**
     * @param string $videoBase64
     * @return string
     * @throws \Exception
     */
    public static function validateVideoBase64(string $videoBase64): string
    {
        $extension = explode('/', $videoBase64);
        $extension = explode(';', $extension[1]);

        $extension = '.' . $extension[0];

        $separatorFile = explode(',', $videoBase64);
        $video_encoded = $separatorFile[1] ?? [];
        $video_decoded = !empty($video_encoded) ? base64_decode($video_encoded) : [];

        $data = ['imageDecoded' => $video_decoded];
        $rules = ['imageDecoded' => new VideoDecodedRule()];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $nameFile = Str::random(10) . $extension;

        Storage::disk('local_serverless')->put($nameFile, $video_decoded);

        return '/tmp/'. $nameFile;
    }
}
