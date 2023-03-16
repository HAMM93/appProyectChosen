<?php

namespace App\Types;

class PaymentTypes
{
    const ALL_TYPES = ['pendent', 'paid', 'precessing', 'refused'];
    const ALL = 'all';
    const PENDING = 'pendent';
    const PAID = 'paid';
    const PROCESSING = 'processing';
    const REFUSED = 'refused';
    const RELATION_TYPES = [
        'succeeded' => self::PAID,
        'paid' => self::PAID,
        'pendent' => self::PENDING,
        'precessing' => self::PROCESSING,
        'refused' => self::REFUSED,
        'active' => self::PAID,
        'incomplete' => self::SUBSCRIPTION_INCOMPLETE,
        'incomplete_expired' => self::SUBSCRIPTION_INCOMPLETE_EXPIRED,
        'trialing' => self::SUBSCRIPTION_TRIALING,
        'past_due' => self::SUBSCRIPTION_PAST_DUE,
        'canceled' => self::SUBSCRIPTION_CANCELED,
        'unpaid' => self::SUBSCRIPTION_UNPAID
    ]; //TODO :: (Wellington) Need to improve status, ex: pendent should be "pending"
    const SUBSCRIPTION_PAID = 'active';
    const SUBSCRIPTION_INCOMPLETE = 'incomplete';
    const SUBSCRIPTION_INCOMPLETE_EXPIRED = 'incomplete_expired';
    const SUBSCRIPTION_TRIALING = 'trialing';
    const SUBSCRIPTION_PAST_DUE = 'past_due';
    const SUBSCRIPTION_CANCELED = 'canceled';
    const SUBSCRIPTION_UNPAID = 'unpaid';
 }
