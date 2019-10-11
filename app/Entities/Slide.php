<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Slide extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['language_id', 'title', 'url', 'image', 'description', 'color', 'ord', 'status'];

    public function language()
    {
        return $this->belongsTo('App\Entities\Language');
    }

}
