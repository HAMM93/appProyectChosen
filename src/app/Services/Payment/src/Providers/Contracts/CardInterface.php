<?php

namespace App\Services\Payment\src\Providers\Contracts;

interface CardInterface
{
    public function getNumber(): string;

    public function getCVC(): string;

    public function getExpMonth(): string;

    public function getExpYear(): string;

    public function getHolderName(): string;

    public function validateCard();
}
