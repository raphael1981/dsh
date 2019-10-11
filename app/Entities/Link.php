<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Link extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'links';
    protected $fillable = ['language_id','template_id','ref','title','alias','path','link','params','ord','description','description_links','meta_description','meta_keywords','status'];

    public function contents()
    {
        return $this->morphedByMany('App\Entities\Content', 'linkgables');
    }

    public function language()
    {
        return $this->belongsTo('App\Entities\Language');
    }

    public function template()
    {
        return $this->belongsTo('App\Entities\Template');
    }


    public function agendas()
    {
        return $this->morphedByMany('App\Entities\Agenda', 'linkgables');
    }

}
