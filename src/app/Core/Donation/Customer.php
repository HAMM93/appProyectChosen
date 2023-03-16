<?php

declare(strict_types=1);

namespace App\Core\Donation;

use App\Services\Payment\src\Providers\Contracts\CustomerInterface;

class Customer implements CustomerInterface
{

    public string $name;
    public string $internalId;
    public string $email;
    public string $type = 'individual';
    public string $country;
    public string $document;
    public string $documentType;
    public string $phone;

    public function __construct(array $customer)
    {
        $this->internalId = (string) $customer['chosen_id'];
        $this->name = $customer['name'];
        $this->email = $customer['email'];
        $this->document = $customer['document'] ?? '';
        $this->documentType = $customer['document_type'] ?? '';
        $this->country = $customer['country'];
        $this->phone = $customer['phone'];
    }

    public function getInternalId(): string
    {
        return $this->internalId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDocument(): string
    {
        return $this->document;
    }

    /**
     * @return mixed|string
     */
    public function getDocumentType(): string
    {
        return $this->documentType;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getCountry(): string
    {
        return $this->country;
    }
}
