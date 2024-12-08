<?php

namespace App\Repository\Eloquent;

use App\Models\EncodedImage;
use Illuminate\Database\Eloquent\Model;
use App\Repository\EncodedImageRepositoryInterface;

class EncodedImageRepository extends Repository implements EncodedImageRepositoryInterface
{
    protected Model $model;

    public function __construct(EncodedImage $model)
    {
        parent::__construct($model);
    }
}
