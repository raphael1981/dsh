<?php

use Illuminate\Database\Seeder;

class MediagablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $cts = \App\Entities\Content::all();
        $ags = \App\Entities\Agenda::all();

        $medias = \App\Entities\Media::all();


        foreach($cts as $key=>$ct){

            foreach($medias as $k=>$m){

                if((bool)random_int(0, 1)){
                    \App\Entities\Content::find($ct->id)->medias()->save(\App\Entities\Media::find($m->id));
                }

            }

        }

        foreach($ags as $key=>$ag){

            foreach($medias as $k=>$m){

                if((bool)random_int(0, 1)){
                    \App\Entities\Agenda::find($ag->id)->medias()->save(\App\Entities\Media::find($m->id));
                }

            }

        }

    }
}
