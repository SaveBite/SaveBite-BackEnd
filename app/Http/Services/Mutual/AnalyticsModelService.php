<?php

namespace App\Http\Services\Mutual;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnalyticsModelService
{
    public function getAnalytics($filePath)
    {
        $response = Http::timeout(120)
            ->attach(
                'file',
                file_get_contents(public_path($filePath)),
                'file.csv'
            )
            ->post(config('analyticsModel.base_url') . '/predict');
//        Log::info($response->body());
        if ($response->successful()) {
            $predictions = $response->json();
            foreach ($predictions as $prediction) {
                Log::info($prediction);
                Log::info($prediction['ds']);
                Log::info($prediction['yhat']);
                auth('api')->user()->analyticsPredictions()->create([
                    'date' => (new \DateTime($prediction['ds']))->format('Y-m-d'),
                    'sales_predictions' => $prediction['yhat'],
                ]);
            }
            return true;
        }
        return false;
    }
}
