<?php

namespace App\classes;

class Helper
{
    public function splitAddress(string $address): array
    {
        preg_match('/^(\d+)\s+(.*)$/', $address, $matches);
        return count($matches) ? [$matches[1], $matches[2]] : [null, $address];
    }
}
