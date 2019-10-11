<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class ViewProfile extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['language_id', 'profile_name', 'suffix','type','params'];

    public function language(){
        return $this->belongsTo('App\Entities\Language');
    }

}
