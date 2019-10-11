<?php

use Illuminate\Database\Seeder;

class LogotypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $logotypes = \Illuminate\Support\Facades\Storage::disk('logotypes')->files();
//        $this->command->info(print_r($logotypes));

        $how_many = 22;

        for($i=0;$i<$how_many;$i++){

            $ltypes = [];

            foreach($logotypes as $logo){

                if(random_int(0,1)==1) {

                    array_push($ltypes, [
                        'link'=>'',
                        'logo_uri'=>'/logotypes/'.$logo
                    ]);

                }

            }

            \App\Entities\Logotype::create([
                'language_id'=>2,
                'name'=>'Grupa logotypów '.$i,
                'rotor_title'=>'Nazwa widoczna '.$i,
                'logotypes'=>json_encode($ltypes)
            ]);

        }
    }
}
