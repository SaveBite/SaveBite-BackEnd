<?php

namespace App\Http\Controllers\Api\V1\LoginAnswer;

use App\Http\Controllers\Controller;
use App\Http\Services\Api\V1\LoginAnswer\LoginAnswerService;


class LoginAnswerController extends Controller
{
    public function __construct(
        private readonly LoginAnswerService $answerService,
    )
    {
    }

    public function index(){
        return $this->answerService->index();
    }

}
