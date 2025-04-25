<?php

namespace App\Http\Services\Api\V1\ChatMessage;

use App\Http\Services\Api\V1\ChatMessage\ChatMessageService;

class ChatMessageMobileService extends ChatMessageService
{

    public static function platform(): string
    {
        return 'mobile';
    }
}
