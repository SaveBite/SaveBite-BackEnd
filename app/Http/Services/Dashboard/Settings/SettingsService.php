<?php

namespace App\Http\Services\Dashboard\Settings;

use App\Http\Services\Mutual\FileManagerService;
use App\Models\User;
use App\Repository\SettingsRepositoryInterface;
use Illuminate\Support\Facades\DB;
use function App\update_model;

class SettingsService
{
    private FileManagerService $fileManagerService;
    private SettingsRepositoryInterface $settingRepository;

    public function __construct(FileManagerService $fileManagerService, SettingsRepositoryInterface $settingsRepository)
    {
        $this->fileManagerService = $fileManagerService;
        $this->settingRepository = $settingsRepository;

    }

    public function update($id, $request)
    {
        $data = $request->validated();
        if ($request->image !== null) {
            $data['image'] = $this->fileManagerService->handle('image', 'profiles/members/images');
        }
        update_model($this->settingRepository, $id, $data, '');
        return redirect()->back()->with(['success' => __('messages.updated_successfully')]);
    }

    public function updatePassword($request)
    {
        DB::beginTransaction();
        try {
            update_model($this->settingRepository, auth()->id(), ['password' => $request->new_password]);
            DB::commit();
            return redirect()->back()->with(['success' => __('messages.updated_successfully')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with(['error' => __('messages.Something went wrong')]);
        }
    }
}
