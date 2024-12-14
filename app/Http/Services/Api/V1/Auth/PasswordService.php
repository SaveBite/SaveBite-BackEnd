<?php

namespace App\Http\Services\Api\V1\Auth;


use App\Http\Resources\V1\Otp\OtpResource;
use App\Http\Resources\V1\User\UserResource;
use App\Http\Services\Mutual\GetService;
use App\Http\Traits\Responser;
use App\Mail\SendCodeMail;
use App\Mail\SendLoginPhotoMail;
use App\Repository\OtpRepositoryInterface;

use App\Repository\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordService
{

    use Responser;

    public function __construct(
        private readonly GetService $getService,
        private readonly OtpRepositoryInterface $otpRepository,
        private readonly UserRepositoryInterface $userRepository
    )
    {
    }


    public function forgetPassword(Request $request): JsonResponse
    {

        DB::beginTransaction();
        try {

            $request->validated();
            $user = $this->userRepository->getUserByEmailAndAnswer();

            $otp = $this->otpRepository->generateOtp($user);
            auth('api')->user()?->update([
                'is_verified' => false
            ]);
            Mail::to($user->email)->send(new SendCodeMail($user,$otp->code));

            DB::commit();
            return  $this->responseSuccess(message: __('messages.forget_password'), data: new OtpResource($otp));

        } catch (\Exception $exception) {
            DB::rollback();
            return $this->responseFail(status: 422, message: __('messages.Something went wrong'));
        }
    }


    public function checkCode(Request $request): JsonResponse
    {

        DB::beginTransaction();
        try {
            $data = $request->validated();
            if (!$this->otpRepository->check($data['otp'], $data['otp_token']))
                return $this->responseFail(message: __('messages.Wrong OTP code or expired'));

            auth('api')->user()?->otp()?->delete();
            auth('api')->user()?->update([
                'is_verified' => true
            ]);

            Mail::to(auth('api')->user()->email)->send(new SendLoginPhotoMail(auth('api')->user()));

            DB::commit();
            return $this->responseSuccess(message: __('messages.Your account has been verified successfully'));
        } catch (\Exception $e) {
            // return $e;
            DB::rollBack();
            return $this->responseFail(message: __('messages.Something went wrong'));
        }
    }


    public function resetPassword(Request $request): JsonResponse
    {


        $data = $request->validated();
        if (!$data['is_verified'])
            return $this->responseFail(message: __('messages.verify otp at first'));
        $user = $this->userRepository->get('email', $data['email'])->first();
        $user = $this->userRepository->update($user['id'], ['password' => $data['new_password']]);
        if ($user) {
            return $this->responseSuccess(message: __('passwords.reset'));
        } else {
            return $this->responseFail(message: __('messages.Something went wrong'));
        }

    }

}
