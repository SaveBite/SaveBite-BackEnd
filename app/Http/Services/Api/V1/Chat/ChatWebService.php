<?php

namespace App\Http\Services\Api\V1\Chat;

use App\Http\Services\Api\V1\Chat\ChatService;

class ChatWebService extends ChatService
{

    public static function platform(): string
    {
        return 'web';
    }
}
