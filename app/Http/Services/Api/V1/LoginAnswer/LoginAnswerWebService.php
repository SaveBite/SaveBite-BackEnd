<?php

namespace App\Http\Services\Api\V1\LoginAnswer;

class LoginAnswerWebService extends LoginAnswerService
{
    public static function platform(): string
    {
        return 'website';
    }

}
