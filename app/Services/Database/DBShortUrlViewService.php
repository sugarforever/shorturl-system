<?php

namespace App\Services\Database;

use App\Models\ShortUrl;
use App\Services\ShortUrlViewService;
use Illuminate\Support\Facades\Log;

class DBShortUrlViewService implements ShortUrlViewService
{
    public function increment($token, $count)
    {
        ShortUrl::where('token', $token)->first()->view->increment('count', $count);
    }
}