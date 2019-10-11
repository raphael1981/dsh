<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $cts = [
            'galeria dsh',
            'plenerowe',
            'impresariat wystaw',
            'wystawy archiwalne',
            'cykle',
            'wokół wystaw',
            'film',
            'książka',
            'spotkanie',
            'spacer',
            'warsztaty',
            'dla dzieci',
            'inne',
            'mapy',
            'wydarzenia archiwalne'
        ];


        foreach($cts as $c){

            if($c=='wystawy archiwalne' || $c=='wydarzenia archiwalne'){

                \App\Entities\Category::create([
                    'language_id'=>2,
                    'name'=>$c,
                    'alias'=>str_slug($c,'-'),
                    'params'=>'{}',
                    'status'=>0
                ]);

            }else{

                \App\Entities\Category::create([
                    'language_id'=>2,
                    'name'=>$c,
                    'alias'=>str_slug($c,'-'),
                    'params'=>'{}',
                    'status'=>1
                ]);

            }

        }

    }
}
