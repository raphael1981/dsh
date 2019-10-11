<?php

use App\Entities\Gallery;
use App\Entities\Picture;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OldBaseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $default_view_config_content = config('view_profiles')['default_view_profile_content'];

        $std_content = new stdClass();
        $std_content->name = $default_view_config_content['p_name'];
        $std_content->color = $default_view_config_content['color'];
        $std_content->icon = $default_view_config_content['icon'];

        $json_content = json_encode($std_content);


        $json_content = json_encode($std_content);

        $default_view_config_agenda = config('view_profiles')['default_view_profile_agenda'];


        $std_agenda = new stdClass();
        $std_agenda->name = $default_view_config_agenda['p_name'];
        $std_agenda->color = $default_view_config_agenda['color'];
        $std_agenda->icon = $default_view_config_agenda['icon'];

        $json_agenda = json_encode($std_agenda);


        $http_host = 'http://'.config('services')['domains']['admin'];

        if(!is_dir(storage_path() . '/app/pictures/archive')) {
            mkdir(storage_path() . '/app/pictures/archive', 0777);
        }


        /*
         * Wystawy
         */

        $exibitions = file_get_contents($http_host.'/get/olditems/exhib');
        $exibitions = \GuzzleHttp\json_decode($exibitions);

        $exibit_category_id = 4;

        foreach($exibitions as $key=>$item){

            $pic = $this->prepareImage($item);
            $gallery = $this->prepeareGallery($item);
            $content = $this->preapareContent($item);

            $ex_ag = \App\Entities\Agenda::create([
                'language_id'=>2,
                'title'=>$item->title,
                'alias'=>str_slug($item->title,'-'),
                'image'=>(!is_null($pic))?$pic->image:null,
                'image_path'=>(!is_null($pic))?$pic->image_path:null,
                'disk'=>(!is_null($pic))?$pic->disk:null,
                'intro'=>$content->intro,
                'content'=>$content->content,
                'params'=>$json_agenda,
                'begin'=>$this->checkDateFormat($item->begin)?$item->begin:null,
                'end'=>$this->checkDateFormat($item->end)?$item->end:null,
                'suffix'=>$default_view_config_agenda['suffix'].'pl',
                'meta_description'=>'',
                'meta_keywords'=>''
            ]);

            if($this->checkDateFormat($item->begin)){
                $ex_ag->created_at = $item->begin;
                $ex_ag->save();
            }


            if(!is_null($gallery)){
                $ex_ag->galleries()->save($gallery);
            }
            $ex_ag->categories()->attach($exibit_category_id);

        }

        /*
         * Wystawy
         */


        /*
         * Wydarzenia
         */

        $ags = file_get_contents($http_host.'/get/olditems/event');
        $ags = \GuzzleHttp\json_decode($ags);

        $ag_category_id = [15];

        foreach($ags as $key=>$item){

            $pic = $this->prepareImage($item);
            $gallery = $this->prepeareGallery($item);
            $content = $this->preapareContent($item);

            $ag = \App\Entities\Agenda::create([
                'language_id'=>2,
                'title'=>$item->title,
                'alias'=>str_slug($item->title,'-'),
                'image'=>(!is_null($pic))?$pic->image:null,
                'image_path'=>(!is_null($pic))?$pic->image_path:null,
                'disk'=>(!is_null($pic))?$pic->disk:null,
                'intro'=>$content->intro,
                'content'=>$content->content,
                'params'=>$json_agenda,
                'begin'=>$this->checkDateFormat($item->begin)?$item->begin:null,
                'end'=>$this->checkDateFormat($item->end)?$item->end:null,
                'suffix'=>$default_view_config_agenda['suffix'].'pl',
                'meta_description'=>'',
                'meta_keywords'=>''
            ]);

            if($this->checkDateFormat($item->begin)){
                $ag->created_at = $item->begin;
                $ag->save();
            }

            if(!is_null($gallery)){
                $ag->galleries()->save($gallery);
            }

            $ag->categories()->attach($ag_category_id);

        }

        /*
         * Wydarzenia
         */


        /*
         * Wydawnictwo
         */

        $wyd = file_get_contents($http_host.'/get/oldeditions');
        $wyd = \GuzzleHttp\json_decode($wyd);

        $link_id = 13;


        foreach($wyd as $key=>$item){

            $pic = $this->prepareImage($item);
            $gallery = $this->prepeareGallery($item);
            $content = $this->preapareContentAndIntro($item);

            $wd = \App\Entities\Content::create([
                'language_id'=>2,
                'title'=>$item->title,
                'alias'=>str_slug($item->title,'-'),
                'image'=>(!is_null($pic))?$pic->image:null,
                'image_path'=>(!is_null($pic))?$pic->image_path:null,
                'disk'=>(!is_null($pic))?$pic->disk:null,
                'intro'=>$content->intro,
                'content'=>$content->content,
                'suffix'=>$default_view_config_content['suffix'].'pl',
                'params'=>$json_content,
                'meta_description'=>'',
                'meta_keywords'=>'',
                'published_at'=>\Carbon\Carbon::now()
            ]);


            if(!is_null($gallery)){
                $wd->galleries()->save($gallery);
            }


            \App\Entities\Link::find($link_id)->contents()->save($wd);


        }


        /*
         * Wydawnictwo
         */

    }


    private function get_http_response_code($url) {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }

    private function checkDateFormat($date){
        $ex = explode('-', $date);
        return checkdate($ex[1], $ex[2], $ex[0]);
    }

    private function preapareContent($item){

        $std = new stdClass();
        $regex = '/\[gallery ids="[0-9,]*"(| [a-z]+="[a-z]+")\]/';

        if(preg_match_all($regex,$item->content,$matches)){
            for($i=0;$i<count($matches[0]);$i++){
                $item->content = str_replace($matches[0][$i],'',$item->content);
            }
        }



        $ex = explode(' ',$item->content);
        $intro = '';

        foreach($ex as $key=>$word){

            if($key==0){
                $intro .= $word;
            }elseif($key<8){
                $intro .= ' '.$word;
            }else{
                break;
            }

        }

        $std->intro = $intro.'...';
        $std->content = $item->content;

        return $std;

    }

    private function preapareContentAndIntro($item){

        $std = new stdClass();
        $regex = '/\[gallery ids="[0-9,]*"(| [a-z]+="[a-z]+")\]/';

        if(preg_match_all($regex,$item->content,$matches)){
            for($i=0;$i<count($matches[0]);$i++){
                $item->content = str_replace($matches[0][$i],'',$item->content);
            }
        }



        $ex = explode(' ',$item->intro);
        $intro = '';

        foreach($ex as $key=>$word){

            if($key==0){
                $intro .= $word;
            }elseif($key<8){
                $intro .= ' '.$word;
            }else{
                break;
            }

        }

        $std->intro = $intro.'...';
        $std->content = $item->content;

        return $std;

    }

    private function prepeareGallery($item){

        if(count($item)>0) {

            $pic_collect = [];
            $translations = new \stdClass();
            $translations->en = '';
            $translations->pl = '';
            $translations = \GuzzleHttp\json_encode($translations);

            $folder = str_slug($item->title,'-');
            if(!is_dir(storage_path() . '/app/pictures/archive/'. $folder)) {
                mkdir(storage_path() . '/app/pictures/archive/' . $folder, 0777);
            }

            foreach ($item->gallery as $key => $pic) {

                if ($this->get_http_response_code($pic->file) == 200) {

                    $path_ex = explode('/', $pic->file);
                    $name = end($path_ex);
                    $image_content = file_get_contents($pic->file);
                    file_put_contents(storage_path() . '/app/pictures/archive/'. $folder . '/' . $name, $image_content);

                    $p = Picture::create([
                        'image_name' => $name,
                        'image_path' => ':archive:'.$folder,
                        'disk' => 'pictures',
                        'translations' => $translations
                    ]);

                    array_push($pic_collect, $p->id);

                }

            }


            $gal = Gallery::create([
                'title' => $item->title,
                'alias' => str_slug($item->title, '-'),
                'regex_tag' => '',
                'params' => '{}',
                'collection' => \GuzzleHttp\json_encode($pic_collect)
            ]);

            $gal->update(['regex_tag' => '<{gallery:' . $gal->id . '}>']);

            $ord = 1;

            foreach ($pic_collect as $i => $pic) {

                DB::table('gallerygables')->insert(
                    [
                        'gallery_id' => $gal->id,
                        'gallerygables_id' => $pic,
                        'gallerygables_type' => 'App\Entities\Picture',
                        'ord' => $ord
                    ]
                );

                $ord++;

            }

            return $gal;

        }

        return null;

    }

    private function prepareImage($item){

        if(!is_null($item->picture)) {

            if ($this->get_http_response_code($item->picture) == 200) {

                $path_ex = explode('/', $item->picture);
                $name = end($path_ex);
                $image_content = file_get_contents($item->picture);
                file_put_contents(storage_path() . '/app/pictures/archive/' . $name, $image_content);

                $std = new \stdClass();
                $std->image = $name;
                $std->image_path = ':archive';
                $std->disk = 'pictures';

                return $std;
            }

            return null;

        }

        return null;
    }

}
