<?php

namespace App\Repository;

interface TrackingProductRepositoryInterface extends RepositoryInterface
{
    public function getTrackingProducts(int $perPage, array $columns = ['*'], array $relations = []);
}
