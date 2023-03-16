<?php

namespace App\Core\Contracts;

interface CartChosenInterface
{
    public function getTypeRevelation(): string;

    /**
     * Verify if the cart has just one item of revelation type
     */
    public function validateRevelation(): void;
}
