<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{

    use HasFactory;
    protected $fillable = [
        'productId',
        'userId',
        'content',
        'rating',
    ];
    public function users(){
        return $this->belongsTo('App\Models\User','userId','id')->select('id','name','email','contact','image');
    }

}
