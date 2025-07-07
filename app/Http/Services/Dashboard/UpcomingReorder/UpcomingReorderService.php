<?php

namespace App\Http\Services\Dashboard\UpcomingReorder;

use App\Repository\UpcomingReorderRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UpcomingReorderService
{
    public function __construct(private readonly UpcomingReorderRepositoryInterface $repository)
    {
    }

    public function index()
    {
        $upcomingReorders = $this->repository->paginate(25);
        return view('dashboard.site.upcomingreorders.index', compact('upcomingReorders'));
    }

    public function show($id)
    {
        $upcomingReorder = $this->repository->getById($id);
        return view('dashboard.site.upcomingreorders.show', compact('upcomingReorder'));
    }

    public function destroy($id)
    {
        try {
            $this->repository->delete($id);
            return redirect()->back()->with(['success' => __('messages.deleted_successfully')]);
        } catch (\Exception $e) {
            return back()->with(['error' => __('messages.Something went wrong')]);
        }
    }
}
