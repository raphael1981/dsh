<?php

use Illuminate\Database\Seeder;

class YoutubesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Entities\Youtube::class, 20)->create()->each(function($y){
            $y->update(['regex_tag'=>'<{youtube:'.$y->id.'}>']);
        });
    }
}
