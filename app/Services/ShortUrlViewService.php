<?php

namespace App\Services;

interface ShortUrlViewService
{
    public function increment($token, $count);
}