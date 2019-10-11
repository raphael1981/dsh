<?php

use Illuminate\Database\Seeder;

class GroupgablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $mbs = \App\Entities\Member::all();
        $grs = \App\Entities\Group::all();

        foreach($grs as $k=>$gr){

            foreach($mbs as $mb){

                if((bool)random_int(0, 1)){

                    \App\Entities\Group::find($gr->id)->members()->save(\App\Entities\Member::find($mb->id));

                }

            }

        }


    }
}
