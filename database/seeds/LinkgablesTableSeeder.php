<?php

use Illuminate\Database\Seeder;

class LinkgablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ag = \App\Entities\Agenda::all()->toArray();
        $ct = \App\Entities\Content::all()->toArray();

        $ag_arr = [];
        $ct_arr = [];

        foreach ($ag as $a){
            $lot = $a;
            $lot['type'] = 'agenda';
            array_push($ag_arr, $lot);
        }

        foreach ($ct as $c){
            $lot = $c;
            $lot['type'] = 'content';
            array_push($ct_arr, $lot);
        }



        $all = array_merge($ag_arr, $ct_arr);


        foreach(\App\Entities\Link::all() as $key=>$link){

            $iter = 0;

            foreach ($all as $a){

                if((bool)random_int(0, 1)){

                    $iter++;

                    switch ($a['type']){

                        case 'agenda':
                            \App\Entities\Link::find($link->id)->agendas()->save(\App\Entities\Agenda::find($a['id']));
                            \Illuminate\Support\Facades\DB::table('linkgables')
                                ->where('link_id', $link->id)
                                ->where('linkgables_id', $a['id'])
                                ->where('linkgables_type', 'LIKE', '%Agenda%')
                                ->update(['ord'=>$iter, 'status'=>random_int(0, 1)]);

                            break;

                        case 'content':
                            \App\Entities\Link::find($link->id)->contents()->save(\App\Entities\Content::find($a['id']));
                            \Illuminate\Support\Facades\DB::table('linkgables')
                                ->where('link_id', $link->id)
                                ->where('linkgables_id', $a['id'])
                                ->where('linkgables_type', 'LIKE', '%Content%')
                                ->update(['ord'=>$iter, 'status'=>random_int(0, 1)]);
                            break;

                    }

                }

            }

            $iter=0;

        }





//        $all = array_merge($ag, $ct);
//
//        shuffle($all);
//
//        foreach(\App\Entities\Link::all() as $key=>$link){
//
//            if((bool)random_int(0, 1)){
//
//                foreach($all as $c) {
//                    if ((bool)random_int(0, 1)) {
//
//                        if (isset($c['begin'])) {
//                            \App\Entities\Link::find($link->id)->agendas()->save(\App\Entities\Agenda::find($c['id']));
//                        }else{
//                            \App\Entities\Link::find($link->id)->contents()->save(\App\Entities\Content::find($c['id']));
//                        }
//
//
//                    }
//                }
//
//            }
//
//        }
    }
}
