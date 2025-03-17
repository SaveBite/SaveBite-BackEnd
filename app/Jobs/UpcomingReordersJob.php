<?php

namespace App\Jobs;

use App\Http\Services\Mutual\AnalyticsModelService;
use App\Http\Services\Mutual\StockModelService;
use App\Models\UpcomingReorder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpcomingReordersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    /**
     * Create a new job instance.
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(StockModelService $stockModelService, AnalyticsModelService $analyticsModelService): void
    {
        $stockModelService->upload($this->filePath, UpcomingReorder::query());
        $analyticsModelService->getAnalytics($this->filePath);
    }
}
