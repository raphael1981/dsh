<?php

use Illuminate\Database\Seeder;

class MediasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $files = \Illuminate\Support\Facades\Storage::disk('media')->files();


        foreach($files as $key=>$file){

            $m = $this->getFileInfo($file);
            $icon = $this->getIcon($m->mimetype);

            \App\Entities\Media::create(
                [
                    'title' => $m->name,
                    'filename' => $m->name,
                    'full_filename' => $file,
                    'disk' => 'media',
                    'media_relative_path' => '/'.$file,
                    'mimetype' => $m->mimetype,
                    'suffix' => $m->suffix,
                    'params'=>'{"icon":"'.$icon.'"}'
                ]
            );


        }



    }


    private function getIcon($mime){

        $icon_name = null;

        foreach(config('services')['mimeicons'] as $key=>$icon){

            foreach($icon['mimelist'] as $k=>$mt){

                if($mt==$mime){
                    $icon_name=config('services')['mimeicons'][$key]['icon'];
                }

            }

        }

        if(is_null($icon_name)){
            $icon_name = config('services')['mimeicons'][0]['icon'];
        }

        return $icon_name;

    }


    private function getFileInfo($file){

        $std = new stdClass();

        $fname_array = explode('.', $file);
        $suffix = str_replace('.','',end($fname_array));
        array_pop($fname_array);

        $name = '';

        foreach($fname_array as $k=>$f){
            $name .= $f;
        }


        $std->suffix = $suffix;
        $std->name = $name;
        $std->mimetype = mime_content_type(storage_path().'/app/media/'.$file);

        return $std;


    }


}
