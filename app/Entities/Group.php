<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Group extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'groups';
    protected $fillable = ['name','person_limit','params'];

    public function agendas()
    {
        return $this->morphToMany('App\Entities\Agendas', 'agendagables');
    }

    public function members()
    {
        return $this->morphedByMany('App\Entities\Member', 'groupgables');
    }

}
