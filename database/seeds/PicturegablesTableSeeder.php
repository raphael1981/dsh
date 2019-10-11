<?php

use Illuminate\Database\Seeder;

class PicturegablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach(\App\Entities\Gallery::all() as $key=>$gal){

            $iter = 1;

            foreach(\App\Entities\Picture::all() as $k=>$p){

                if((bool)random_int(0, 1)){

                    \App\Entities\Gallery::find($gal->id)->pictures()->save(\App\Entities\Picture::find($p->id));
                    \Illuminate\Support\Facades\DB::table('gallerygables')
                        ->where('gallery_id', $gal->id)
                        ->where('gallerygables_id', $p['id'])
                        ->where('gallerygables_type', 'LIKE', '%Picture%')
                        ->update(['ord'=>$iter]);

                    $iter++;

                }

            }

            $iter = 1;

        }

    }
}
