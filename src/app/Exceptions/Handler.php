<?php

namespace App\Exceptions;

use App\Helpers\Response\ResponseAPI;
use App\Services\Logging\Facades\Logging;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of exception types that with a general message error
     * @var array|string[]
     */
    private array $defaultRenderClasses = [
        ModelNotFoundException::class => 'general.model-not-found'
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if (!env('APP_DEBUG')) {
            if ($message = $this->defaultRender($e))
                return ResponseAPI::unprocessableEntity(['message' => $message]);

            return ResponseAPI::exception($e);
        }

        return parent::render($request, $e);
    }

    protected function defaultRender(Throwable $e)
    {
        $check = array_keys($this->defaultRenderClasses);

        foreach ($check as $item)
            if ($e instanceof $item)
                return trans($this->defaultRenderClasses[$item]);

        if ($e instanceof QueryException) {
            //TODO::(Joel 07/10) adicionar comentário para documentar a decisão
//            Logging::critical($e);
            return trans('exception.database.query');
        }

        /**
         * To monitor handled exceptions and render a default message to user
         */
        if ($e instanceof RuntimeException) {
            /** When a log critical this case is created the specific exception should be handled */
            $error_code = Logging::critical($e);
            return trans('general.runtime-exception', ['error_code' => $error_code]);
        }

        return false;
    }
}
