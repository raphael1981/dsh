<?php

use Illuminate\Database\Seeder;

class AgendagablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ags = \App\Entities\Agenda::all();
        $grps = \App\Entities\Group::all();
        $lkey = count($grps)-1;

        foreach($ags as $k=>$ag){

            if((bool)random_int(0, 1)){

                $k = random_int(1, $lkey);
                \App\Entities\Agenda::find($ag->id)->groups()->save(\App\Entities\Group::find($grps[$k]));

            }

        }

    }
}
