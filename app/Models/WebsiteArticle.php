<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteArticle extends Model
{
    protected $fillable = [
        'image',
        'title',
        'details',
    ];
    use HasFactory;
}
