<?php

namespace App\Http\Services\Api\V1\Chat;

use App\Http\Requests\Api\V1\Chat\StoreMessageRequest;
use App\Http\Resources\V1\ChatMessage\ChatMessageResource;
use App\Http\Services\Mutual\FileManagerService;
use App\Http\Services\PlatformService;
use App\Http\Traits\Responser;
use App\Repository\ChatMessageRepositoryInterface;
use App\Repository\ChatRepositoryInterface;
use Illuminate\Support\Facades\DB;

abstract class ChatService extends PlatformService
{
    use Responser;
    public function __construct(private  readonly ChatRepositoryInterface $repository,
                                private readonly ChatMessageRepositoryInterface $chatMessageRepository,
                                private readonly FileManagerService $fileManagerService){}


    public function chatMessages(){
        $messages = $this->repository->chatRoomMessages($this->repository->checkChatCreated()->id);

        if ($messages->isEmpty()) {
            return $this->responseSuccess(data: [], message: __('messages.no_messages_found'));
        }
        return $this->responseSuccess(message: __('messages.messages'),
            data: ChatMessageResource::collection($messages));
    }

    public function storeMessage( StoreMessageRequest$request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            $data = [
                'message' => $data['message'],
                'is_bot' => $data['is_bot'],
                'chat_id' => $this->repository->checkChatCreated()->id,
            ];
            $message = $this->chatMessageRepository->create($data);

            DB::commit();
            return $this->responseSuccess(data: ChatMessageResource::make($message) );
        }catch (\Exception $exception){
            DB::rollBack();
//            dd($exception->getMessage());
            return $this->responseFail(500,__('something went wrong'), );
        }

    }


    public function addToFavourites($id)
    {
        $message = $this->chatMessageRepository->getById($id);
        if (!$message) {
            return $this->responseFail(404, __('messages.message_not_found'));
        }

//        $previousMessageFound = $this->chatMessageRepository->get('message', $message->message);
//        if ($previousMessageFound && $previousMessageFound->first() && $previousMessageFound->first()->is_favorite) {
//            return $this->responseFail(422, __('messages.message_already_favourite'));
//        }

        $this->chatMessageRepository->update($message->id ,[
            'is_favorite' => !$message->is_favorite,
        ]);
        $message = $this->chatMessageRepository->getById($id);

        return $this->responseSuccess(message: __('messages.message_favourite_status_changed'),
            data: ChatMessageResource::make($message));
    }

    public function favourites()
    {
        $messages = $this->repository->favourites($this->repository->checkChatCreated()->id);

        if ($messages->isEmpty()) {
            return $this->responseSuccess(data: [], message: __('messages.no_messages_found'));
        }
        return $this->responseSuccess(message: __('messages.messages'),
            data: ChatMessageResource::collection($messages));
    }


}
