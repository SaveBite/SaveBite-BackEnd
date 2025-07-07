<?php

namespace App\Repository\Eloquent;

use App\Models\UpcomingReorder;
use App\Repository\Eloquent\Repository;
use App\Repository\UpcomingReorderRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class UpcomingReorderRepository extends Repository implements UpcomingReorderRepositoryInterface
{
    protected Model $model;

    public function __construct(UpcomingReorder $model)
    {
        parent::__construct($model);
    }
    // Add custom methods if needed
}
