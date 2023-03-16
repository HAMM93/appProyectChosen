<?php

namespace App\Exceptions\Storage\Image;

use App\Services\Logging\Facades\Logging;
use Exception;
use Illuminate\Http\Response;
use Throwable;

class ImageErrorSanitizeException extends ImageException
{
    public function __construct(Throwable $e)
    {
        parent::__construct(
            trans('exception.storage.image.error'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        Logging::critical($e);
    }
}
