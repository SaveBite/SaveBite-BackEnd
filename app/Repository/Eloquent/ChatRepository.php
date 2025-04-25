<?php

namespace App\Repository\Eloquent;

use App\Models\Chat;

use App\Models\ChatMessage;
use App\Repository\ChatRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class ChatRepository extends Repository implements ChatRepositoryInterface
{
    protected Model $model;

    public function __construct(Chat $model){
        parent::__construct($model);
    }

    public function checkChatCreated()
    {

        $user = auth('api')->user();
        $data = [
            'user_id' => $user->id,
        ];
        return Chat::query()->firstOrCreate($data);

    }

    public function chatRoomMessages($chat_id)
    {
        return ChatMessage::query()->where('chat_id',$chat_id)->get();
    }

    public function favourites($chat_id)
    {
        return ChatMessage::query()->where('chat_id',$chat_id)->where('is_favorite',1)->get();
    }

}
