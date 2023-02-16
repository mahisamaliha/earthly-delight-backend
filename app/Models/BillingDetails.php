<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingDetails extends Model
{
    protected $fillable = [
        'user_id',
        'country',
        'fname',
        'lname',
        'address',
        'city',
        'state',
        'zip',
        'email',
        'phone',
    ];
    use HasFactory;
}
