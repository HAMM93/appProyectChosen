<?php

namespace App\Rules;

use App\Models\Donor;
use App\Services\Storage\AWSS3Service;
use Aws\S3\Exception\S3Exception;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ImagickException;

class ImageRule implements Rule
{
    private $fileDecoded;
    private $extension;
    private $messages = [];
    private $validated = true;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->decodeImageBase64($value);

        $nameFile = Str::random(6) . $this->extension;

        Storage::put($nameFile, $this->fileDecoded);

        $this->validate($nameFile);

        if ($this->validated) {
            $this->sanitize($nameFile);
        }

        return $this->validated;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('exception.imageFromBase64.invalid_image');
    }

    private function decodeImageBase64(string $imageBase64) : void
    {
        $extension = explode('/', $imageBase64);
        $extension = explode(';', $extension[1]);

        $this->extension = '.' . $extension[0];

        $separatorFile = explode(',', $imageBase64);
        $photoEncoded = isset($separatorFile[1]) ? $separatorFile[1] : [];
        $photoDecoded = !empty($photoEncoded) ? base64_decode($photoEncoded) : [];

        if (empty($photoDecoded))
            throw new \Exception(trans('validation.base64Image'), 422);

        $this->fileDecoded = $photoDecoded;
    }

    /**
     * @throws ImagickException
     */
    private function validate($nameFile): void
    {
        $image = storage_path('app/') . $nameFile;
        $imagick = new \Imagick($image);

        $mimeType = $imagick->getImageMimeType();
        $acceptedExtensions = config('files.extensions_allowed');

        $sizeFile = $imagick->getImageLength();
        $height = $imagick->getImageHeight();
        $width = $imagick->getImageWidth();

        if (!(in_array($mimeType, $acceptedExtensions))) {
            Storage::delete($nameFile);

            $this->validated = false;

            $this->messages = array_merge($this->messages, trans('validation.imageFromBase64.mime_type'));
        }

        if ($sizeFile < config('files.size.min') || $sizeFile > config('files.size.max')) {
            Storage::delete($nameFile);

            $this->validated = false;

            $this->messages = array_merge($this->messages, trans('validation.imageFromBase64.size'));
        }

        if ($height > config('files.dimensions.height') || $width > config('files.dimensions.width')) {
            Storage::delete($nameFile);

           $this->validated = false;

           $this->messages = array_merge($this->messages, trans('validation.imageFromBase64.dimension'));
        }
    }

    /**
     * @throws ImagickException
     */
    private static function sanitize(string $nameFile): void
    {
        try {
            $imagePath = storage_path('app/'). $nameFile;
            $imagick = new \Imagick($imagePath);

            $imagick->stripImage();
            $imagick->writeImage($imagePath);
            $imagick->clear();
            $imagick->destroy();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 422);
        }
    }

}
