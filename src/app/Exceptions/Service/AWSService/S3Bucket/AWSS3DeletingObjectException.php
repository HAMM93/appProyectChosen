<?php

namespace App\Exceptions\Service\AWSService\S3Bucket;

use App\Services\Logging\Facades\Logging;
use Exception;
use Illuminate\Http\Response;
use Throwable;

class AWSS3DeletingObjectException extends AWSS3ServiceException
{
    public function __construct(Throwable $e)
    {
        parent::__construct(
            trans('exception.service.aws_s3.delete_error'),
            Response::HTTP_NOT_ACCEPTABLE
        );

        Logging::critical($e);
    }
}
