<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticable;

use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticable implements JWTSubject
{
    use HasFactory;

    public function getJWTIdentifier(){
        return $this->getKey();
    }
    public function getJWTCustomClaims(){
        return [];
    }

    protected $fillable = [
        'name', 'email', 'password','userType','username','contact','store_id','employee_id','national_id','passport_no','user_role_id','passwordToken',
        'isActive', 'reset_pass_code', 'token_expired_at'
    ];
    public function setPasswordAttribute($password){
        if ( $password !== null ) {
            if ( is_null(request()->bcrypt) ) {
                $this->attributes['password'] = bcrypt($password);
            } else {
                $this->attributes['password'] = $password;
            }
        }
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function customer(){
        return $this->belongsTo('App\Models\Customer', 'id', 'userId');
    }
    public function cart(){
        return $this->hasMany('App\Models\Cart','userId');
    }
}
