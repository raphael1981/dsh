<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Publication extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['language_id','title','alias','intro','content','suffix','params','status'];

    public function language()
    {
        return $this->belongsTo('App\Entities\Language');
    }

    public function medias()
    {
        return $this->morphedByMany('App\Entities\Media', 'publicationgables');
    }

}
