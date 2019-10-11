<?php

use Illuminate\Database\Seeder;

class SlidesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $colors = config('services')['template_colors'];

        \App\Entities\Slide::create(
            [
                'language_id'=>2,
                'title'=>'Na wakacjach',
                'url'=>'http://dsh.spaceforweb.pl/o-nas/dla-mediow',
                'image'=>'slide1.jpg',
                'description'=>'Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis.',
                'color'=>\GuzzleHttp\json_encode($colors[1]),
                'ord'=>1,
                'status'=>1
            ]
        );


        \App\Entities\Slide::create(
            [
                'language_id'=>2,
                'title'=>'Etiam ullamcorper. Suspendisse a pellentesque',
                'url'=>'http://dsh.spaceforweb.pl/o-nas/dla-mediow',
                'image'=>'slide2.jpg',
                'description'=>'Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis.',
                'color'=>\GuzzleHttp\json_encode($colors[2]),
                'ord'=>2,
                'status'=>1
            ]
        );


        \App\Entities\Slide::create(
            [
                'language_id'=>2,
                'title'=>'Aliquam erat ac ipsum. Integer aliquam purus.',
                'url'=>'http://dsh.spaceforweb.pl/o-nas/dla-mediow',
                'image'=>'slide3.jpg',
                'description'=>'Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis.Aliquam erat ac ipsum. Integer aliquam purus. ',
                'color'=>\GuzzleHttp\json_encode($colors[3]),
                'ord'=>3,
                'status'=>1
            ]
        );

    }
}
