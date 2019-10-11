<?php

use Illuminate\Database\Seeder;

class TemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /*
         * First children leaf
         */
        $p1 = new stdClass();
        $p1->is_single=false;
        $p1->is_many=false;
        $p1->is_unstandard=false;
        $p1->is_filtered=false;
        $p1->is_firtsleaf=true;
        $p1->is_filteredleaf=false;
        \App\Entities\Template::create([
            'name'=>'Pierwszy potomny',
            'params'=>\GuzzleHttp\json_encode($p1)
        ]);
        /*
         * First children leaf
         */

        /*
         * Unstandard
         */
        $p2 = new stdClass();
        $p2->is_single=false;
        $p2->is_many=false;
        $p2->is_unstandard=true;
        $p2->is_filtered=false;
        $p2->is_firtsleaf=false;
        $p2->is_filteredleaf=false;
        \App\Entities\Template::create([
            'name'=>'Nie standardowy',
            'params'=>\GuzzleHttp\json_encode($p2)
        ]);
        /*
         * Unstandard
         */


        /*
         * Single
         */
        $p3 = new stdClass();
        $p3->is_single=true;
        $p3->is_many=false;
        $p3->is_unstandard=false;
        $p3->is_filtered=false;
        $p3->is_firtsleaf=false;
        $p3->is_filteredleaf=false;
        \App\Entities\Template::create([
            'name'=>'Pojedyncza odsłona',
            'params'=>\GuzzleHttp\json_encode($p3)
        ]);
        /*
         * Single
         */


        /*
         * List
         */
        $p4 = new stdClass();
        $p4->is_single=false;
        $p4->is_many=true;
        $p4->is_unstandard=false;
        $p4->is_filtered=false;
        $p4->is_firtsleaf=false;
        $p4->is_filteredleaf=false;
        \App\Entities\Template::create([
            'name'=>'Lista treści',
            'params'=>\GuzzleHttp\json_encode($p4)
        ]);
        /*
         * List
         */


        /*
         * Filtered
         */
        $p5 = new stdClass();
        $p5->is_single=false;
        $p5->is_many=false;
        $p5->is_unstandard=false;
        $p5->is_filtered=true;
        $p5->is_firtsleaf=false;
        $p5->is_filteredleaf=false;
        \App\Entities\Template::create([
            'name'=>'Filtry wybranych kategorii',
            'params'=>\GuzzleHttp\json_encode($p5)
        ]);
        /*
         * Filtered
         */


        /*
         * Filtered
         */
        $p6 = new stdClass();
        $p6->is_single=false;
        $p6->is_many=false;
        $p6->is_unstandard=false;
        $p6->is_filtered=false;
        $p6->is_firtsleaf=false;
        $p6->is_filteredleaf=true;
        \App\Entities\Template::create([
            'name'=>'Filtrowanie potomnych',
            'params'=>\GuzzleHttp\json_encode($p6)
        ]);
        /*
         * Filtered
         */




    }
}
