<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Template extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'templates';
    protected $fillable = ['name','blade','params'];

    public function links()
    {
        return $this->hasMany('App\Entities\Link');
    }

}
