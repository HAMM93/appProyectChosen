<?php

declare(strict_types=1);

namespace App\Services\Payment\src\Providers\Pagarme;


use App\Services\Payment\src\Providers\Contracts\CustomerInterface;

class PagarmeCustomer
{

    private $externalId;

    private $name;

    private $type;

    private $country;

    private $email;

    private $documents;

    private $phone_numbers;

    private $birthday;

    public function __construct(CustomerInterface $customer)
    {
        $this->externalId = $customer->getInternalId();
        $this->name = $customer->getName();
        $this->type = $customer->getType();
        $this->country = strtolower($customer->getCountry());
        $this->email = $customer->getEmail();
        $this->documents = [
            [
                'type' => $customer->getDocumentType(),
                'number' => $customer->getDocument()
            ]
        ];
        $this->phone_numbers = $customer->getPhone();
//        $this->birthday = $customer['birthday'];
    }

    private function setExternalId(string $externalId)
    {
        $this->external_id = $externalId;
    }

    public function getDataToCreate(): array
    {
        return $this->getDataToCreate();
    }

    public function getCustomer()
    {
        return [
            'external_id' => $this->externalId,
            'name' => $this->name,
            'email' => $this->email,
            'type' => $this->type,
            'country' => $this->country,
            'documents' => $this->documents,
            'phone_numbers' => [$this->phone_numbers]
        ];
    }

    public function getName()
    {
        return $this->name;
    }
}

