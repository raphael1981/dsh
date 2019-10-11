<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Gallery extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'galleries';
    protected $fillable = ['title','alias','regex_tag','params', 'collection', 'serialize', 'status'];

    public function pictures()
    {
        return $this->morphedByMany('App\Entities\Picture', 'gallerygables');
    }

    public function contents()
    {
        return $this->morphToMany('App\Entities\Content', 'contentgables');
    }

    public function agendas()
    {
        return $this->morphToMany('App\Entities\Agenda', 'agendagables');
    }

}
