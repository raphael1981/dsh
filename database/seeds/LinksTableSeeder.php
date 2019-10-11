<?php

use Illuminate\Database\Seeder;

class LinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(\App\Repositories\LinkRepositoryEloquent $link)
    {
        $data = file_get_contents(url('/test/1'));
        $array = \GuzzleHttp\json_decode($data, true);

        $link->createTreeFromArray($array,2);

        $data = file_get_contents(url('/test/3'));
        $array = \GuzzleHttp\json_decode($data, true);

        $link->createTreeFromArray($array,1);
    }
}
