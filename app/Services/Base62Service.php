<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class Base62Service
{
    const CHARACTERS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public function encode($decimal)
    {
        Log::debug("Decimal to encode: {$decimal}");
        $encoded_str = '';
        $len = strlen(self::CHARACTERS);
        while ($decimal > 0) {
            $encoded_str = self::CHARACTERS[$decimal % $len] . $encoded_str;
            $decimal = intval($decimal / $len);
        }

        return $encoded_str;
    }
}
