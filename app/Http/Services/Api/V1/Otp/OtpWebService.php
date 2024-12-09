<?php

namespace App\Http\Services\Api\V1\Otp;

use App\Http\Services\Api\V1\Otp\OtpService;

class OtpWebService extends OtpService
{

    public static function platform(): string
    {
        return 'web';
    }
}
