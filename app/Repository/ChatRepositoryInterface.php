<?php

namespace App\Repository;

interface ChatRepositoryInterface extends RepositoryInterface
{

    public function checkChatCreated();

    public function chatRoomMessages($chat_id);


    public function favourites($chat_id);

}
