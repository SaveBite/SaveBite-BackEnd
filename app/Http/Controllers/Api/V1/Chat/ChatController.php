<?php

namespace App\Http\Controllers\Api\V1\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Chat\StoreMessageRequest;
use App\Http\Services\Api\V1\Chat\ChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function __construct(private readonly ChatService $service)
    {
    }

    public function chatMessages()
    {
        return $this->service->chatMessages();
    }

    public function storeMessage(StoreMessageRequest $request)
    {
        return $this->service->storeMessage($request);
    }

    public function addToFavourites($id)
    {
        return $this->service->addToFavourites($id);
    }

    public function favourites(){
        return $this->service->favourites();
    }
}
