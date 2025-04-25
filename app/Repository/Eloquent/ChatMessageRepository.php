<?php

namespace App\Repository\Eloquent;

use App\Models\ChatMessage;
use App\Repository\Eloquent\Repository;
use App\Repository\ChatMessageRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class ChatMessageRepository extends Repository implements ChatMessageRepositoryInterface
{
    protected Model $model;

    public function __construct(ChatMessage $model){
        parent::__construct($model);
    }
}