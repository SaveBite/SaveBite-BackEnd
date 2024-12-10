<?php

namespace App\Http\Services\Api\V1\LoginAnswer;

class LoginAnswerMobileService extends LoginAnswerService
{
    public static function platform(): string
    {
        return 'mobile';
    }

}
