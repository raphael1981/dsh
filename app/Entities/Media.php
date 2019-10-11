<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Media extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'medias';
    protected $fillable = ['title','filename', 'full_filename', 'disk', 'media_relative_path','mimetype','suffix','params'];


    public function contents()
    {
        return $this->morphToMany('App\Entities\Content', 'contentables');
    }

    public function agendas()
    {
        return $this->morphToMany('App\Entities\Agenda', 'agendagables');
    }

    public function publications()
    {
        return $this->morphToMany('App\Entities\Publication', 'publicationgables');
    }

}
