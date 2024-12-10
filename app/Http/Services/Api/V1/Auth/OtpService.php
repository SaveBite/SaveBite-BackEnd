<?php

namespace App\Http\Services\Api\V1\Auth;

use App\Http\Resources\V1\Otp\OtpResource;
use App\Http\Traits\Responser;
use App\Mail\SendCodeMail;
use App\Repository\OtpRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    use Responser;

    public function __construct(
        private readonly OtpRepositoryInterface $otpRepository,
    )
    {

    }

    public function generate($user)
    {
        $otp = $this->otpRepository->generateOtp($user);
        auth('api')->user()?->update([
            'otp_verified' => false
        ]);
        Mail::to($user->email)->send(new SendCodeMail($user,$otp->otp));
        return $this->responseSuccess(message: __('messages.OTP_Is_Send'), data: OtpResource::make($otp));
    }

    public function verify($request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            if (!$this->otpRepository->check($data['otp'], $data['otp_token']))
                return $this->responseFail(message: __('messages.Wrong OTP code or expired'));

            auth('api')->user()?->otp()?->delete();
            auth('api')->user()?->update([
                'otp_verified' => true
            ]);
            DB::commit();
            return $this->responseSuccess(message: __('messages.Your account has been verified successfully'));
        } catch (\Exception $e) {
            // return $e;
            DB::rollBack();
            return $this->responseFail(message: __('messages.Something went wrong'));
        }
    }

}
