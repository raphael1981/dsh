<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Picture extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'pictures';
    protected $fillable = ['image_name','image_path','disk','translations'];


    public function galleries()
    {
        return $this->morphToMany('App\Entities\Gallery', 'gallerygables');
    }


}
