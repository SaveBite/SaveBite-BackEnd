<?php

namespace App\Repository\Eloquent;

use App\Models\User;
use App\Repository\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends Repository implements UserRepositoryInterface
{
    protected Model $model;

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getActiveUsers()
    {
        return $this->model::query()->where('is_active', true);
    }

    public function getUserByEmailAndAnswer(){
        return $this->model::query()
            ->where('email',request('email'))
            ->where('login_answer_id',request('answer'))
            ->where('is_active', true);

    }
}
