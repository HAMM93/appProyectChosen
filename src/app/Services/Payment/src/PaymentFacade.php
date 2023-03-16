<?php

namespace App\Services\Payment\src;

use Illuminate\Support\Facades\Facade;

/**
 * @method static createTransaction
 */
class PaymentFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'payment'; }
}
