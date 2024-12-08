<?php

namespace App\Repository\Eloquent;

use App\Models\LoginAnswer;
use App\Repository\LoginAnswerRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class LoginAnswerRepository extends Repository implements LoginAnswerRepositoryInterface
{
    protected Model $model;

    public function __construct(LoginAnswer $model){
        parent::__construct($model);
    }
}
