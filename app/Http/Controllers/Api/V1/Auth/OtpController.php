<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\OtpVerifyRequest;
use App\Http\Services\Api\V1\Auth\OtpService;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    public function __construct(
        private readonly OtpService $otpService
    )
    {

    }

    public function send(Request $request)
    {
        return $this->otpService->generate( $request);
    }
    public function verify(OtpVerifyRequest $request)
    {
        return $this->otpService->verify($request);
    }
}
