<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\CheckCodeRequest;
use App\Http\Requests\Api\V1\Auth\ForgetPasswordRequest;
use App\Http\Requests\Api\V1\Auth\ResetPasswordRequest;
use App\Http\Services\Api\V1\Auth\PasswordService;
use Illuminate\Http\JsonResponse;

class PasswordController extends Controller
{


    public function __construct(
        private readonly PasswordService $PasswordService
    )
    {
    }

    public function forgetPassword(ForgetPasswordRequest $request)
    {

        return  $this->PasswordService->forgetPassword($request);
    }


    public function checkCode(CheckCodeRequest $request)
    {

        return  $this->PasswordService->checkCode($request);

    }


    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {

        return  $this->PasswordService->Password($request);

    }

}
