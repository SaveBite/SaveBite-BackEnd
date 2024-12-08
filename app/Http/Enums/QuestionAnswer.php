<?php

namespace App\Http\Enums;

enum QuestionAnswer : string
{
    use Enumable;

    case TEA = "5arbosh shay";
    case MANGO = "Mango";
    case COFFEE = "Coffee";
    case SAHLAB = "Sahlab";
    case FARAWLA = "Farawla";

    public function t(){
        return match($this){
            self::TEA => "5arbosh shay",
            self::MANGO => "Mango",
            self::COFFEE => "Coffee",
            self::SAHLAB => "Sahlab",
            self::FARAWLA => "Farawla",
        };
    }
}
