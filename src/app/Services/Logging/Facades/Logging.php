<?php

namespace App\Services\Logging\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @param $exception (Throwable || Array['message' => string, 'error_code' => int])
 * @method static mixed info(mixed $exception)
 * @method static mixed security(mixed $exception)
 * @method static mixed critical(mixed $exception)
 * @see \App\Services\Logging\Log
 */
class Logging extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'logging'; }
}
