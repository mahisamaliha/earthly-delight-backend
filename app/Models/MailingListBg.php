<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailingListBg extends Model
{
    protected $fillable = [
        'image',
        'title',
    ];
    use HasFactory;
}
