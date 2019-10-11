<?php

use Illuminate\Database\Seeder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $langs = LaravelLocalization::getSupportedLocales();

        foreach($langs as $key=>$value){

            \App\Entities\Language::create([
                'tag'=>$key,
                'name'=>$value['name'],
                'regional'=>$value['regional']
            ]);

        }
    }
}
