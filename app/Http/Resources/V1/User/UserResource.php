<?php

namespace App\Http\Resources\V1\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\UrlGenerator;

class UserResource extends JsonResource
{
    public function __construct($resource, private readonly bool $withToken, private readonly ?string $imageUrl = null)
    {
        parent::__construct($resource);
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->user_name,
            'email' => $this->email,
            'otp_token' => $this->whenNotNull($this->otp?->token),
            'type'=>$this->type,
            'is_verified' => $this->whenNotNull($this->is_verified),
            'token' => $this->when($this->withToken, $this->token()),
            'image_url' => $this->whenNotNull($this->imageUrl),
        ];
    }
}
