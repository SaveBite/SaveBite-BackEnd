<?php

namespace App\Http\Enums;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
enum UserType : string
{
    use Enumable;
    case PHARMACY = 'pharmacy';
    case CLIENT = 'client';
    case PHARMACY_EMPLOYEE = 'pharmacy_employee';
    case HOSPITAL = 'hospital';
    case HOSPITAL_EMPLOYEE = 'hospital_employee';
    case DOCTOR = 'doctor';


    public function t()
    {
        return match ($this) {
            self::PHARMACY => __('dashboard.pharmacy'),
            self::CLIENT => __('dashboard.client'),
            self::PHARMACY_EMPLOYEE => __('dashboard.pharmacy_employee'),
            self::HOSPITAL => __('dashboard.hospital'),
            self::HOSPITAL_EMPLOYEE => __('dashboard.hospital_employee'),
            self::DOCTOR => __('dashboard.doctor'),
        };
    }
}
