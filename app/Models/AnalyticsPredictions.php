<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyticsPredictions extends Model
{
    use HasFactory;

    protected $table = 'analytics_predictions';
    protected $fillable = ['user_id', 'date', 'sales_predictions'];
}
