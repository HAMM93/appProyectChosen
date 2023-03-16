<?php

declare(strict_types=1);

namespace App\Transformers;

class ProductTransformer extends AbstractTransformer
{
    protected array $format = [
        'id' => [
            'type' => 'int',
            'required' => true,
            'key' => 'id'
        ],
        'item' => [
            'type' => 'string',
            'required' => true,
            'key' => 'item'
        ],
        'price' => [
            'type' => 'int',
            'required' => true,
            'key' => 'price'
        ],
        'type' => [
            'type' => 'string',
            'required' => true,
            'key' => 'type'
        ],
        'description_type' => [
            'type' => 'string',
            'required' => true,
            'key' => 'description_type'
        ]
    ];

    public function __construct($data, $type)
    {
        parent::__construct($data, $type);
    }
}
