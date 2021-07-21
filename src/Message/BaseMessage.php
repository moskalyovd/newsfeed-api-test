<?php

namespace App\Message;

abstract class BaseMessage
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
