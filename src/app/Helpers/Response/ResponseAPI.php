<?php

declare(strict_types=1);

namespace App\Helpers\Response;

use App\Transformers\AbstractTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ResponseAPI
{
    private static int $exceptionStatusCode = 422;

    public static function exception($e): JsonResponse
    {
        return response()->json(['error' => self::getBodyException($e)], self::$exceptionStatusCode);
    }

    private static function getBodyException($exception)
    {
        if (is_string($exception))
            return $exception;

        if ($exception instanceof ValidationException)
            return ['validation' => $exception->errors()];

        if ($exception instanceof \Throwable) {
            if (in_array($exception->getCode(), [401, 403]))
                self::$exceptionStatusCode = $exception->getCode();
            else
                self::$exceptionStatusCode = Response::HTTP_UNPROCESSABLE_ENTITY;

            return $exception->getMessage();
        }


        throw new \InvalidArgumentException(trans('general.exception-not-handled'), 500);
    }

    public static function results($data): JsonResponse
    {
        return response()->json(self::getBodyResults($data), Response::HTTP_OK);
    }

    public static function created($data): JsonResponse
    {
        return response()->json(self::getBodyResults($data), Response::HTTP_CREATED);
    }

    public static function updated($data): JsonResponse
    {
        return response()->json(self::getBodyResults($data), Response::HTTP_OK);
    }

    public static function noContent($data): JsonResponse
    {
        return response()->json(self::getBodyResults($data), Response::HTTP_NO_CONTENT);
    }

    public static function badRequest($data): JsonResponse
    {
        return response()->json(self::getBodyResults($data), Response::HTTP_BAD_REQUEST);
    }

    public static function unprocessableEntity($data): JsonResponse
    {
        return response()->json(self::getBodyResults($data), Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public static function notFound($data): JsonResponse
    {
        return response()->json(self::getBodyResults($data), Response::HTTP_NOT_FOUND);
    }


    private static function getBodyResults($data): array
    {
        //TODO :: (Wellington) Should a good practice if all data is an instance os AbstractTransformer
        if (is_array($data))
            return $data;

        if ($data instanceof AbstractTransformer)
            return $data->transform();

        throw new \InvalidArgumentException(trans('general.exception-not-handled'), 500);
    }
}
