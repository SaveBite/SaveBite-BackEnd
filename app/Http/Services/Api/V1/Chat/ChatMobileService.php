<?php

namespace App\Http\Services\Api\V1\Chat;

use App\Http\Services\Api\V1\Chat\ChatService;

class ChatMobileService extends ChatService
{

    public static function platform(): string
    {
        return 'mobile';
    }
}
