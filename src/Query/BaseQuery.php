<?php

namespace App\Query;

abstract class BaseQuery
{
    public function __construct(array $props = [])
    {
        foreach ($props as $prop => $value) {
            if (property_exists(static::class, $prop)) {
                $this->$prop = $value;
            }
        }
    }
}
