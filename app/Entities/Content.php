<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Content extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'contents';
    protected $fillable = ['language_id','title','alias','image','image_path','disk','intro','content','author','type','url', 'suffix', 'params','meta_description','meta_keywords','status', 'archived','published_at'];

    public function links()
    {
        return $this->morphToMany('App\Entities\Link', 'linkgables');
    }

    public function categories()
    {
        return $this->morphToMany('App\Entities\Category', 'categorygables');
    }


    public function galleries()
    {
        return $this->morphedByMany('App\Entities\Gallery', 'contentgables');
    }

    public function language()
    {
        return $this->belongsTo('App\Entities\Language');
    }

    public function medias()
    {
        return $this->morphedByMany('App\Entities\Media', 'contentgables');
    }

    public function logotypes(){

        return $this->morphToMany('App\Entities\Logotype','logotypegables');

    }

}
