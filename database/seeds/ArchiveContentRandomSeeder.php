<?php

use Illuminate\Database\Seeder;

class ArchiveContentRandomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(\App\Entities\Content::all() as $c){

            if(random_int(0,1)==1){
                $c->archived = 1;
                $c->save();
            }

        }
    }
}
