<?php

namespace App\Services\Snowflake;

use App\Services\TokenService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SnowflakeTokenService implements TokenService
{
    public function nextToken()
    {
        Log::debug(env("SNOWFLAKE_REMOTE_API"));
        $response = Http::get(env("SNOWFLAKE_REMOTE_API"));
        Log::debug($response->json());
        $token = $response->json('value');
        Log::debug("Token from remote API: {$token}");

        return intval($token);
    }
}