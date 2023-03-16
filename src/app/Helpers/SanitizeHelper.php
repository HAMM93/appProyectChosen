<?php

namespace App\Helpers;

class SanitizeHelper
{
    /**
     * @param array $values
     * @param array $rules
     * @return array
     */
    public static function make(array $values, array $rules): array
    {
        $sanitized = [];

        foreach ($rules as $field => $type) {
            switch ($type) {
                case 'bool':
                    if (isset($values[$field]) && !is_bool($values[$field])) {
                        $sanitized[$field] = strtolower(trim($values[$field])) === 'true';
                    }
                    break;
                case 'string':
                    $sanitized[$field] = preg_replace( '/[^[:print:]]/', '', $field);
                    break;
            }
        }

        return $sanitized;
    }
}
