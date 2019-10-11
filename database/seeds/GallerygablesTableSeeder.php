<?php

use Illuminate\Database\Seeder;

class GallerygablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gls = \App\Entities\Gallery::all();
        $cnts = \App\Entities\Content::all();

        foreach($cnts as $k=>$cn){

            foreach($gls as $gl){

                if((bool)random_int(0, 1)){

                    \App\Entities\Content::find($cn->id)->galleries()->save(\App\Entities\Gallery::find($gl->id));

                }

            }

        }

        $agds = \App\Entities\Agenda::all();

        foreach($agds as $k=>$ag){

            foreach($gls as $gl){

                if((bool)random_int(0, 1)){

                    \App\Entities\Agenda::find($ag->id)->galleries()->save(\App\Entities\Gallery::find($gl->id));

                }

            }

        }

    }
}
