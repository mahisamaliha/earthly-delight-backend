<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotDeal extends Model
{
    protected $fillable = [
        'title',
        'percentageTitle',
        'startTime',
        'endTime',
        'duration',
        'isHotSale',
    ];
    use HasFactory;
}
