<?php

namespace App\Services\Payment\src\Providers\Contracts;

interface CartInterface
{
    public function getItems(): array;

    public function getAmount(): int;

    public function getDonationQuantity(): int;
}
