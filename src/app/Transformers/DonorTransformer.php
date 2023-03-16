<?php

declare(strict_types=1);

namespace App\Transformers;


class DonorTransformer extends AbstractTransformer
{
    protected array $format = [
        'name' => [
            'type' => 'string',
            'required' => true,
            'key' => 'name'
        ],
        'document' => [
            'type' => 'string',
            'required' => true,
            'key' => 'document'
        ],
        'email' => [
            'type' => 'string',
            'required' => true,
            'key' => 'email'
        ],
        'phone' => [
            'type' => 'string',
            'required' => true,
            'key' => 'phone'
        ],
        'occupation' => [
            'type' => 'string',
            'required' => true,
            'key' => 'ocupation'
        ],
        'birthdate' => [
            'type' => 'string',
            'required' => true,
            'key' => 'birthdate'
        ],
        'gender' => [
            'type' => 'string',
            'required' => true,
            'key' => 'gender'
        ],
        'address' => AddressTransformer::class
    ];

    public function __construct($data, $type)
    {
        parent::__construct($data, $type);
    }

}
