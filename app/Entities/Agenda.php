<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Agenda extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'agendas';
    protected $fillable = ['language_id','place_id','title','alias','image','image_path','disk','intro','content','params','begin','end','begin_time','end_time','suffix','meta_description','meta_keywords','status'];

    public function groups()
    {
        return $this->morphedByMany('App\Entities\Group', 'agendagables');
    }

    public function place()
    {
        return $this->belongsTo('App\Entities\Place');
    }

    public function categories()
    {
        return $this->morphToMany('App\Entities\Category', 'categorygables');
    }

    public function galleries()
    {
        return $this->morphedByMany('App\Entities\Gallery', 'agendagables');
    }

    public function links()
    {
        return $this->morphToMany('App\Entities\Link', 'linkgables');
    }

    public function agendas()
    {
        return $this->morphedByMany('App\Entities\Agenda', 'agendagables');
    }

    public function medias()
    {
        return $this->morphedByMany('App\Entities\Media', 'agendagables');
    }

    public function language()
    {
        return $this->belongsTo('App\Entities\Language');
    }

    public function logotypes(){

        return $this->morphToMany('App\Entities\Logotype','logotypegables');

    }

}
