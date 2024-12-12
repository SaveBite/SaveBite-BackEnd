<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\SignInRequest;
use App\Http\Requests\Api\V1\Auth\SignUpRequest;
use App\Http\Services\Api\V1\Auth\AuthService;

/**
 * @OA\Info(
 *     title="Save-Bite API Documentation",
 *     version="1.0.0",
 *     description="API documentation for the application."
 * )
 */
class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $auth,
    )
    {
    }

/**
 * @OA\post(
 *     path="/api/v1/website/auth/sign/up",
 *     summary="Register user into the system",
 *     tags={"Authentication"},
 *     @OA\Parameter(
 *         name="user_name",
 *         in="query",
 *         description="The page number for pagination",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *
 *         )
 *      ),
 *     @OA\Parameter(
 *         name="email",
 *         in="query",
 *         description="The page number for pagination",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *
 *         )
 *      ),
 *     @OA\Parameter(
 *         name="phone",
 *         in="query",
 *         description="The page number for pagination",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *
 *         )
 *      ),
 *     @OA\Parameter(
 *         name="answer",
 *         in="query",
 *         description="The page number for pagination",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *
 *         )
 *      ),
 *     @OA\Parameter(
 *         name="type",
 *         in="query",
 *         description="The page number for pagination",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *
 *         )
 *      ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="image",
 *                     type="file",
 *                     format="binary",
 *                     description="The image file to upload"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User successfully registered"
 *     )
 * )
 */

    public function signUp(SignUpRequest $request) {
        return $this->auth->signUp($request);
    }

    public function signIn(SignInRequest $request) {
        return $this->auth->signIn($request);
    }

    public function signOut()
    {
        return $this->auth->signOut();
    }

    public function whatIsMyPlatform()
    {
        return $this->auth->whatIsMyPlatform();
    }
}
