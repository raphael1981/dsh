<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member  extends Authenticatable implements Transformable
{
    use TransformableTrait;

    protected $table = 'members';
    protected $fillable = ['email','password','name','surname','newsletter','status','rodo','verification_token'];

}
