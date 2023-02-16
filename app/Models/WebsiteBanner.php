<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteBanner extends Model
{
    protected $fillable = [
        'image',
        'type',
        'title',
        'link',
        'btn_text'
    ];
    use HasFactory;
}
