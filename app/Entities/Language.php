<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Language extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'languages';
    protected $fillable = ['tag','name','regional'];
    public $timestamps = false;

    public function links()
    {
        return $this->hasMany('App\Entities\Links');
    }

    public function contents()
    {
        return $this->hasMany('App\Entities\Content');
    }

    public function agendas()
    {
        return $this->hasMany('App\Entities\Agenda');
    }

    public function categories()
    {
        return $this->hasMany('App\Entities\Categories');
    }

    public function leadscenes()
    {
        return $this->hasMany('App\Entities\Leadscene');
    }

    public function slides()
    {
        return $this->hasMany('App\Entities\Slide');
    }

    public function viewprofiles()
    {
        return $this->hasMany('App\Entities\ViewProfile');
    }

    public function publications()
    {
        return $this->hasMany('App\Entities\Publication');
    }

    public function logotypes()
    {
        return $this->hasMany('App\Entities\Logotype');
    }

}
