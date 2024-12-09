<?php

namespace App\Http\Services\Api\V1\Auth;

use App\Http\Requests\Api\V1\Auth\SignInRequest;
use App\Http\Requests\Api\V1\Auth\SignUpRequest;
use App\Http\Resources\V1\User\UserResource;
use App\Http\Services\Mutual\FileManagerService;
use App\Http\Services\Mutual\ImageEncryptionService;
use App\Http\Services\PlatformService;
use App\Http\Traits\Responser;
use App\Mail\SendCodeMail;
use App\Models\LoginAnswer;
use App\Repository\OtpRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

abstract class AuthService extends PlatformService
{
    use Responser;

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly FileManagerService      $fileManagerService,
        private readonly ImageEncryptionService  $imageEncryptionService,
        private readonly OtpRepositoryInterface $otpRepository,
    )
    {
    }

    public function signUp(SignUpRequest $request) {
        DB::beginTransaction();
        try {
            $data = $request->except(['answer','password_confirmation','image']);
            $data['login_answer_id'] = $request->answer;
            $data['sec_photo']=$this->fileManagerService->handle('image', 'Users/SecPhotos');

            $user = $this->userRepository->create($data);
            $this->generate($user,$request);

            $this->imageEncryptionService->embed('image',$data['email'], $data['password']);
            DB::commit();
            return $this->responseSuccess(message: __('messages.created successfully'), data: new UserResource($user, true));
        } catch (Exception $e) {
            DB::rollBack();
           return $e->getMessage();
            return $this->responseFail(message: __('messages.Something went wrong'));
        }
    }

    public function signIn(SignInRequest $request) {
        if($request->hasFile('image') && ! empty($request->file('image'))){
            $credentials = $this->imageEncryptionService->extract('image', $request->email);
        }else{
            $credentials = $request->only('email', 'password');
        }
        $token = auth('api')->attempt($credentials);
        if ($token) {
            return $this->responseSuccess(message: __('messages.Successfully authenticated'), data: new UserResource(auth('api')->user(), true));
        }

        return $this->responseFail(status: 401, message: __('messages.wrong credentials'));
    }

    public function signOut() {
        auth('api')->logout();
        return $this->responseSuccess(message: __('messages.Successfully loggedOut'));
    }

    public function generate($user = null, $request = null)
    {
        if ($request && !empty($request->email)) {
            $email = $request->email;
        } else {
            $email = $user ? $user->email : auth('api')->user()->email;
        }

        $otp = $this->otpRepository->generateOtp($user);
        auth('api')->user()?->update([
            'otp_verified' => false
        ]);
        Mail::to($user->email)->send(new SendCodeMail($user,$otp->code));
        return $this->responseSuccess(message: __('messages.OTP_Is_Send'), data: OtpResource::make($otp));
    }

    public function checkOtp($request)
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
