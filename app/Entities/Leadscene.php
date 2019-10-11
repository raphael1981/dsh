<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Leadscene extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['language_id','name','serialize','fast_serialize','active'];

    public function language()
    {
        return $this->belongsTo('App\Entities\Language');
    }
}
