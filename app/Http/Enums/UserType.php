<?php

namespace App\Http\Enums;

enum UserType : string
{
    use Enumable;
    case USER = 'user';
    case RESTAURANT = 'restaurant';
    case SUPER_MARKET = 'super_market';



    public function t()
    {
        return match ($this) {
            self::USER => __('dashboard.user'),
            self::RESTAURANT => __('dashboard.restaurant'),
            self::SUPER_MARKET => __('dashboard.super_market'),
        };
    }
}
