<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
        return [
            'long_url' => $this->long_url,
            'short_url' => env("APP_URL") . '/s/' . $this->token,
            'created_at' => $this->created_at->format('Y-m-d H:i:s')
        ];
    }
}
