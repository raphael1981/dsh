<?php

use Illuminate\Database\Seeder;

class GalleriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Entities\Gallery::class, 40)->create()->each(function($g){
            $g->update(['regex_tag'=>'<{gallery:'.$g->id.'}>']);
        });
    }
}
