<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Category extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'categories';
    protected $fillable = ['language_id','name','alias','params','status'];

    public function language()
    {
        return $this->belongsTo('App\Entities\Language');
    }

    public function agendas()
    {
        return $this->morphedByMany('App\Entities\Agenda', 'categorygables');
    }

    public function contents()
    {
        return $this->morphedByMany('App\Entities\Content', 'categorygables');
    }

}
