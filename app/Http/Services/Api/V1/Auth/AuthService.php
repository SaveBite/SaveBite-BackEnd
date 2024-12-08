<?php

namespace App\Http\Services\Api\V1\Auth;

use App\Http\Requests\Api\V1\Auth\SignInRequest;
use App\Http\Requests\Api\V1\Auth\SignUpRequest;
use App\Http\Resources\V1\User\UserResource;
use App\Http\Services\Mutual\FileManagerService;
use App\Http\Services\Mutual\ImageEncryptionService;
use App\Http\Services\PlatformService;
use App\Http\Traits\Responser;
use App\Models\LoginAnswer;
use App\Repository\UserRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

abstract class AuthService extends PlatformService
{
    use Responser;

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private FileManagerService $fileManagerService,
        private ImageEncryptionService $imageEncryptionService,
    )
    {
    }

    public function signUp(SignUpRequest $request) {
        DB::beginTransaction();
        try {
            $data = $request->except(['answer','password_confirmation']);
            $answer = LoginAnswer::where('content', $request->answer)->first();
            $data['login_answer_id'] = $answer->id;
            // dd($data);
            $user = $this->userRepository->create($data);
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

}
