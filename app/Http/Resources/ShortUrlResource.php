<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class ShortUrlResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $token = $this->token;
        $qrCodePath = public_path() . "/{$token}.svg";
        Log::debug("QR code path: {$qrCodePath}");
        return [
            'long_url' => $this->long_url,
            'short_url' => env("APP_URL") . '/s/' . $token,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'token' => $this->token,
            'qr_code' => file_exists($qrCodePath) ? "/{$token}.svg" : null
        ];
    }
}
