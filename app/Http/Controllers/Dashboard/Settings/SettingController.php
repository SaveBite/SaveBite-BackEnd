<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Auth\UpdatePasswordRequest;
use App\Http\Requests\Dashboard\Member\MemberRequest;
use App\Http\Requests\Dashboard\Settings\InfoSettingsRequest;
use App\Http\Services\Dashboard\Settings\SettingsService;
use App\Repository\SettingsRepositoryInterface;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private SettingsRepositoryInterface $settingsRepository;
    private SettingsService $settingsService;
    public function __construct(SettingsRepositoryInterface $settingsRepository,SettingsService $settingsService){
            $this->settingsService=$settingsService;
            $this->settingsRepository=$settingsRepository;
    }

    public function edit(string $id)
    {
        $user=$this->settingsRepository->getById(auth()->id());
        return view('dashboard.site.settings.edit',compact('user'));

    }
    public function update(InfoSettingsRequest $request, string $id)
    {
        return $this->settingsService->update(auth()->id(),$request);
    }
    public function updatePassword (UpdatePasswordRequest $request)
    {
        return $this->settingsService->updatePassword($request);
    }


}
