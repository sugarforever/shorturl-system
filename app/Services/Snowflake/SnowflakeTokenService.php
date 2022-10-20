<?php

namespace App\Services\Snowflake;

use App\Services\TokenService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SnowflakeTokenService implements TokenService
{
    public function nextToken()
    {
        $response = Http::get(env("SNOWFLAKE_REMOTE_API"));
        $token = $response->json('value');
        Log::debug("Token from remote API: {$token}");

        return intval($token);
    }
}