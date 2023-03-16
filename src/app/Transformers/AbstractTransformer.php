<?php

namespace App\Transformers;

abstract class AbstractTransformer
{
    const STRING = ['string' => 'String'];
    const INTEGER = ['int' => 'Number'];

    protected $data;
    protected array $format = [];

    public function __construct($data, string $type)
    {
        if ($type === 'collection') {
            $this->data = !is_array($data) ? $data->toArray() : $data;
        } else if ($type === 'item') {
            $this->data = !is_array($data) ? [$data->toArray()] : [$data];
        }
    }

    final public function getResource()
    {
        $format = $this->format;

        $resource = collect($this->data)->transform(function ($item, $key) use ($format) {
            return $this->makeFormat($format, $item);
        });

        if ($resource->count() > 1)
            return $resource->toArray();
        else
            return $resource->first();
    }

    final public function makeFormat($format, $data)
    {
        return collect($format)->transform(function ($item, $key) use ($data) {

            if (isset($item['type'])) {
                if (isset($data[$item['key']])) {
                    return $this->sanitize($item['type'], $data[$item['key']]);
                }
            }

            if (is_string($item) && class_exists($item)) {
                if ($instance = new \ReflectionClass($item)) {
                    if ($instance->isSubclassOf(AbstractTransformer::class)) {
                        return (new $item($data, 'item'))->transform();
                    }
                }
            }

        })->toArray();
    }

    final public function transform(): array
    {
        return $this->getResource();
    }

    //TODO:: (Wellington) Criar método abstrato para retornar os tipos dos campos de cada transformer
    public function getTypes()
    {
        $resource['types'] = collect($this->format)->transform(function ($value, $key) {
            return $this->defineFormat($key, $value);
        })->toArray();

    }

    private function defineFormat($key, $value)
    {
        if (is_array($value)) {
            if ($value['type'] === 'string') {
                return $value['required'] ? 'String' : '?String';
            }
        }

        if (is_string($value) && class_exists($value))
            if ($instance = new $value() instanceof AbstractTransformer) {
                //..
            }
    }

    final private function sanitize($type, $value)
    {
        switch ($type) {
            case 'int' : return (int)$value;
            default: return (string)$value;
        }
    }
}
