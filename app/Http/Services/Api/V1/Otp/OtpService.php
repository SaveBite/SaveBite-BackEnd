<?php

namespace App\Http\Services\Api\V1\Otp;

use App\Http\Services\Mutual\FileManagerService;
use App\Http\Services\PlatformService;
use App\Http\Traits\Responser;
use App\Repository\OtpRepositoryInterface;
use Illuminate\Support\Facades\DB;

abstract class OtpService extends PlatformService
{
    use Responser;
    public function __construct(private  readonly OtpRepositoryInterface $repository,
                                private readonly FileManagerService $fileManagerService){}


}