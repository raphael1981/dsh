<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Place extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'places';
    protected $fillable = ['name','alias','image','image_path','disk','description', 'lat', 'lng', 'params'];

    public function agendas()
    {
        return $this->hasMany('App\Entities\Agenda');
    }

}
