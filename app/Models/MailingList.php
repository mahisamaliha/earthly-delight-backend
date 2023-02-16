<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailingList extends Model
{
    protected $fillable = [
        'email', 
    ];
    use HasFactory;
}
