<?php

namespace App\Services\Payment\src\Providers\Contracts;

interface CustomerInterface
{
    public function getInternalId(): string;

    public function getName(): string;

    public function getEmail(): string;

    public function getType(): string;

    public function getDocument(): string;

    public function getDocumentType(): string;

    public function getPhone(): string;

    public function getCountry(): string;

}
