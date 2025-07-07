<?php

namespace App\Http\Controllers\Dashboard\UpcomingReorder;

use App\Http\Controllers\Controller;
use App\Http\Services\Dashboard\UpcomingReorder\UpcomingReorderService;

class UpcomingReorderController extends Controller
{
    public function __construct(private readonly UpcomingReorderService $service)
    {
    }

    public function index()
    {
        return $this->service->index();
    }

    public function show($id)
    {
        return $this->service->show($id);
    }

    public function destroy($id)
    {
        return $this->service->destroy($id);
    }
}
