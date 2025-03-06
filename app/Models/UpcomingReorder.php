<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpcomingReorder extends Model
{
    use HasFactory;

    protected $table = 'upcoming_reorders';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
