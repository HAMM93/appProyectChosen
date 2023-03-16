<?php

namespace App\Transformers;

class AddressTransformer extends AbstractTransformer
{
    protected array $format = [
        'resumed' => [
            'type' => 'string',
            'required' => true,
            'key' => 'address_resumed'
        ],
        'street' => [
            'type' => 'string',
            'required' => true,
            'key' => 'address_street'
        ],
        'complement' => [
            'type' => 'string',
            'required' => true,
            'key' => 'address_complement'
        ],
        'number' => [
            'type' => 'string',
            'required' => true,
            'key' => 'address_number'
        ],
        'zipcode' => [
            'type' => 'string',
            'required' => true,
            'key' => 'address_zipcode'
        ],
        'neighborhood' => [
            'type' => 'string',
            'required' => true,
            'key' => 'address_neightborhood'
        ],
        'city' => [
            'type' => 'string',
            'required' => true,
            'key' => 'address_city'
        ],
        'state' => [
            'type' => 'string',
            'required' => true,
            'key' => 'address_state'
        ]
    ];

    public function __construct($data, $type)
    {
        parent::__construct($data, $type);
    }

}
