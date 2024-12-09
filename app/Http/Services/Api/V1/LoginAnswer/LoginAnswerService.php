<?php

namespace App\Http\Services\Api\V1\LoginAnswer;


use App\Http\Resources\V1\LoginAnswer\LoginAnswerResource;
use App\Http\Services\PlatformService;
use App\Http\Traits\Responser;
use App\Repository\LoginAnswerRepositoryInterface;


abstract class LoginAnswerService extends PlatformService
{
    use Responser;

    public function __construct(
        private readonly LoginAnswerRepositoryInterface $answerRepository,

    )
    {
    }


    public function index(){

        $data =$this->answerRepository->getAll();
        return $this->responseSuccess(data: LoginAnswerResource::collection($data));
        
    }

}
