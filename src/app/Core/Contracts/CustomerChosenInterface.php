<?php

namespace App\Core\Contracts;

interface CustomerChosenInterface
{

    public function getName(): string;

    public function getEmail(): string;

    public function getType(): string;

    public function getDocument(): string;

    public function getPhone(): string;

    public function getCountry(): string;

}
