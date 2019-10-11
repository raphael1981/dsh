<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Logotype.
 *
 * @package namespace App\Entities;
 */
class Logotype extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['language_id', 'name','rotor_title','logotypes'];


    public function agendas(){

        return $this->morphedByMany('App\Entities\Agenda','logotypegables');

    }

    public function contents(){

        return $this->morphedByMany('App\Entities\Content','logotypegables');

    }

    public function language()
    {
        return $this->belongsTo('App\Entities\Language');
    }

}
