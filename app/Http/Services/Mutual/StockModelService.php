<?php

namespace App\Http\Services\Mutual;

use Illuminate\Support\Facades\Http;

class StockModelService
{
    public function upload($filePath, $model)
    {
        $response = Http::timeout(120)
        ->attach(
            'file',
            file_get_contents(url($filePath)),
            "csv_file"
        )->post(config('stock_ai_model.base_url') . '/upload');

        if ($response->successful()) {
            if (auth('api')->user()->upcomingReorders()->exists()) {
                auth('api')->user()->upcomingReorders()->delete();
            }
            $upcomingReorders = $this->getpredictions();
            foreach ($upcomingReorders['upcoming_reorders'] as $record) {
                $record['user_id'] = auth('api')->id();
                $model->create($record);
            }
            return true;
        } else {
            return false;
        }
    }

    public function getpredictions()
    {
        $response = Http::timeout(120)
        ->get(config('stock_ai_model.base_url') . '/upcoming_reorders');
        if ($response->successful()) {
            return $response->json();
        }
    }
}
