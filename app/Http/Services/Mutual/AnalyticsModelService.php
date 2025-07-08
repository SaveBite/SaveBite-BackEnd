<?php

namespace App\Http\Services\Mutual;

use Exception;
use Illuminate\Support\Facades\Http;

class AnalyticsModelService
{
    /**
     * @throws Exception
     */
    public function getAnalytics($filePath): bool
    {
        $response = Http::timeout(120)
            ->attach(
                'file',
                file_get_contents(public_path($filePath)),
                'file.csv'
            )
            ->post(config('analyticsModel.base_url').'/predict');
        if ($response->successful()) {
            $predictions = $response->json();
            foreach ($predictions as $prediction) {

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
