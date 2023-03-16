<?php


namespace App\Helpers;

use App\Exceptions\Storage\Image\ImageErrorSanitizeException;
use App\Rules\ImageDecodedRule;
use App\Rules\ImageDimensionsRule;
use App\Rules\ImageMimeTypeRule;
use App\Rules\ImageSizeRule;
use App\Traits\GeneralValidatesTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ImageHelper
{
    use GeneralValidatesTrait;

    static private $messages;

    /**
     * @param $imageBase64
     * @return string
     * @throws ImageErrorSanitizeException
     * @throws ValidationException
     * @throws \ImagickException
     */
    public static function validateImageBase64($imageBase64)
    {
        $extension = explode('/', $imageBase64);
        $extension = explode(';', $extension[1]);

        $extension = '.' . $extension[0];

        $separatorFile = explode(',', $imageBase64);
        $photoEncoded = $separatorFile[1] ?? [];
        $photoDecoded = !empty($photoEncoded) ? base64_decode($photoEncoded) : [];

        $data = ['imageDecoded' => $photoDecoded];
        $rules = ['imageDecoded' => new ImageDecodedRule()];

       $validator = Validator::make($data, $rules);

       if ($validator->fails()) {
           throw new ValidationException($validator);
       }

        $nameFile = Str::random(10) . $extension;

        Storage::put($nameFile, $photoDecoded);

        $image = storage_path('app/') . $nameFile;

        $imagick = new \Imagick($image);

        $mimeType = $imagick->getImageMimeType();

        $sizeFile = $imagick->getImageLength();

        $height = $imagick->getImageHeight();

        $width = $imagick->getImageWidth();

        $data = [
            'mimeType' => $mimeType,
            'size' => $sizeFile,
            'dimensions' => ['height' => $height, 'width' => $width]
        ];

        $rules = [
            'mimeType' => new ImageMimeTypeRule(),
            'size' => new ImageSizeRule(),
            'dimensions' => new ImageDimensionsRule()
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        self::sanitize($image);

        return $image;
    }

    /**
     * @param string $imagePath
     * @throws ImageErrorSanitizeException
     */
    private static function sanitize(string $imagePath): void
    {
        try {
            $imagick = new \Imagick($imagePath);

            $imagick->stripImage();
            $imagick->writeImage($imagePath);
            $imagick->clear();
            $imagick->destroy();
        } catch (\Exception $e) {
            throw new ImageErrorSanitizeException($e);
        }
    }
}
