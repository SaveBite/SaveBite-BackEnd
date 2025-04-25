<?php

namespace App\Http\Services\Api\V1\ChatMessage;

use App\Http\Services\Mutual\FileManagerService;
use App\Http\Services\PlatformService;
use App\Http\Traits\Responser;
use App\Repository\ChatMessageRepositoryInterface;
use Illuminate\Support\Facades\DB;

abstract class ChatMessageService extends PlatformService
{
    use Responser;
    public function __construct(private  readonly ChatMessageRepositoryInterface $repository,
                                private readonly FileManagerService $fileManagerService){}


}