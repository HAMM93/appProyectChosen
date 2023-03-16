<?php

namespace App\Exceptions\Children;


use App\Services\Logging\Facades\Logging;
use Illuminate\Http\Response;
use Throwable;

class ChildrenNotFoundException extends ChildrenException
{
    public function __construct(Throwable $e = null)
    {
        if (!is_null($e)) {
            parent::__construct(
                trans('exception.children.not_found', ['code_error' => Logging::critical($e)]),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        parent::__construct(trans('exception.children.not_found'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
