<?php

declare(strict_types=1);

namespace App\Exceptions\Card;

use Symfony\Component\HttpFoundation\Response;

class InvalidCardException extends CardException
{
    public function __construct()
    {
        parent::__construct(
            trans('validation.card.invalid'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
