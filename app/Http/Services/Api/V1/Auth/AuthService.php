<?php

namespace App\Http\Services\Api\V1\Auth;

use App\Http\Requests\Api\V1\Auth\SignInRequest;
use App\Http\Requests\Api\V1\Auth\SignUpRequest;
use App\Http\Resources\V1\User\UserResource;
use App\Http\Services\Mutual\FileManagerService;
use App\Http\Services\Mutual\ImageEncryptionService;
use App\Http\Services\PlatformService;
use App\Http\Traits\Responser;
use App\Repository\OtpRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

abstract class AuthService extends PlatformService
{
    use Responser;

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly FileManagerService $fileManagerService,
        private readonly ImageEncryptionService $imageEncryptionService,
        private readonly OtpService $otpService,
        private readonly OtpRepositoryInterface $otpRepository,

    ) {
    }

    public function signUp(SignUpRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except(['answer', 'password_confirmation', 'image']);
            $data['login_answer_id'] = $request->answer;
            $user = $this->userRepository->create($data);
            $imageUrl = $this->imageEncryptionService->embed('image', $data['email'], $data['password']);
            $this->otpService->generate($user);
            $user->load('otp');
            DB::commit();
            return $this->responseSuccess(message: __('messages.created successfully'), data: new UserResource($user,
                true, $imageUrl));
        } catch (Exception $e) {
            DB::rollBack();
//           return $e->getMessage();
            return $this->responseFail(message: __('messages.Something went wrong'));
        }
    }

    public function signIn(SignInRequest $request)
    {

        try {
            if ($request->hasFile('image')) {
                $credentials = $this->imageEncryptionService->extract('image', $request->email);

                $token = auth('api')->attempt($credentials);
            } else {
                $credentials = $request->only('email', 'password');
                $token = auth('api')->attempt($credentials);
            }


            if ($token) {
                if (!auth('api')->user()->is_verified) {
                    $otp = $this->otpService->generate(auth('api')->user());
                    return $this->responseFail(status: 401, message: __('messages.verify_your_email_first'),
                        data: $otp['data']);

                }
                return $this->responseSuccess(message: __('messages.Successfully authenticated'),
                    data: new UserResource(auth('api')->user(), true));
            }

            return $this->responseFail(status: 401, message: __('messages.wrong credentials'));
        } catch (Exception $e) {
            $this->responseFail(503, $e->getMessage());
        }

    }


    public function signOut()
    {
        auth('api')->logout();
        return $this->responseSuccess(message: __('messages.Successfully loggedOut'));
    }

}
