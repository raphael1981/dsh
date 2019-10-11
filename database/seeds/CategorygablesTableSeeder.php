<?php

use Illuminate\Database\Seeder;

class CategorygablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = \App\Entities\Category::all();
        $last_key = $categories->count()-1;
        $agendas = \App\Entities\Agenda::all();

        foreach($agendas as $k=>$a){

            $key = mt_rand(0,$last_key);
            $c = $categories[$key];

            foreach( $categories as $c) {

                $is_add = (boolean) mt_rand(0,1);

                if($is_add){
                    \App\Entities\Agenda::find($a->id)->categories()->save(\App\Entities\Category::find($c->id));
                }



            }

        }

    }
}
