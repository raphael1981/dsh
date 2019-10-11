<?php

namespace App\Console\Commands;

use App\Entities\Agenda;
use App\Entities\Category;
use App\Entities\Content;
use App\Entities\Language;
use App\Entities\Publication;
use Illuminate\Console\Command;
use Mmanos\Search\Facade as Search;

class IndexSearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:search {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index search results';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $action = $this->argument('action');
        $this->info($action);


        switch($action){

            case 'index':

                foreach(Agenda::all() as $key=>$value){

                    $ar = [];
                    $ar['entid'] = $value->id;
                    $ar['title'] = $value->title;
                    $ar['intro'] = strip_tags($value->intro);
                    $ar['content'] = strip_tags($value->content);
                    $ar['params'] = $value->params;
                    $ar['suffix'] = $value->suffix;
                    $ar['categories'] = json_encode(Agenda::find($value->id)->categories()->get());

                    if(!is_null($value->disk) && !is_null($value->image) && !is_null($value->image_path)){

                        $ar['is_image'] = true;
                        $ar['image'] = $this->getImageLink($value->disk, $value->image, $value->image_path);

                    }else{

                        $ar['is_image'] = false;

                    }

                    $ar['rgb'] = \GuzzleHttp\json_decode($value->params)->color->rgb;
                    $ar['icon'] = \GuzzleHttp\json_decode($value->params)->icon;
                    $ar['link'] = $value->id.'-'.$value->alias.','.$value->suffix;
                    $ar['type'] = 'agenda';
                    $ar['is_external'] = false;
                    $ar['url'] = null;
                    $ar['language'] = json_encode(Language::find($value->language_id));
                    $ar['language_id'] = \GuzzleHttp\json_decode($ar['language'])->id;
                    $ar['tags'] = json_encode($value->categories()->get());

                    Search::index('sindex')->insert('agenda-'.$value->id, $ar);

                }

                foreach(Content::all() as $key=>$value){

                    $ar = [];
                    $ar['entid'] = $value->id;
                    $ar['title'] = $value->title;
                    $ar['intro'] = strip_tags($value->intro);
                    $ar['content'] = strip_tags($value->content);
                    $ar['params'] = $value->params;
                    $ar['suffix'] = $value->suffix;
                    $ar['categories'] = json_encode(Content::find($value->id)->categories()->get());

                    if(!is_null($value->disk) && !is_null($value->image) && !is_null($value->image_path)){

                        $ar['is_image'] = true;
                        $ar['image'] = $this->getImageLink($value->disk, $value->image, $value->image_path);

                    }else{

                        $ar['is_image'] = false;

                    }

                    $ar['rgb'] = \GuzzleHttp\json_decode($value->params)->color->rgb;
                    $ar['icon'] = \GuzzleHttp\json_decode($value->params)->icon;
                    $ar['link'] = $value->id.'-'.$value->alias.','.$value->suffix;
                    $ar['type'] = 'content';

                    if($value->type=='external'){
                        $ar['is_external'] = true;
                        $ar['url'] = $value->url;
                    }else{
                        $ar['is_external'] = false;
                        $ar['url'] = null;
                    }


                    $ar['language'] = json_encode(Language::find($value->language_id));
                    $ar['language_id'] = \GuzzleHttp\json_decode($ar['language'])->id;
                    $ar['tags'] = json_encode($value->categories()->get());

                    Search::index('sindex')->insert('content-'.$value->id, $ar);

                }

//                foreach(Publication::all() as $key=>$value){
//
//                    $ar = [];
//                    $ar['title'] = $value->title;
//                    $ar['intro'] = strip_tags($value->intro);
//                    $ar['content'] = strip_tags($value->content);
//                    $ar['params'] = $value->params;
//                    $ar['suffix'] = $value->suffix;
//                    $ar['is_image'] = false;
//                    $ar['image'] = null;
//                    $ar['rgb'] = \GuzzleHttp\json_decode($value->params)->color->rgb;
//                    $ar['icon'] = \GuzzleHttp\json_decode($value->params)->icon;
//                    $ar['link'] = $value->id.'-'.$value->alias.','.$value->suffix;
//                    $ar['type'] = 'publication';
//                    $ar['language'] = json_encode(Language::find($value->language_id));
//                    $ar['language_id'] = \GuzzleHttp\json_decode($ar['language'])->id;
//                    $ar['tags'] = null;
//
//                    Search::index('sindex')->insert('publication-'.$value->id, $ar);
//
//                }

                break;


            case 'delete':

                Search::index('sindex')->deleteIndex();

                break;

        }


    }


    private function getImageLink($disk, $image, $path){

        $l = '/'.$disk;

        if($path==':'){

            $l .= '/'.$image;

        }else{

            $l .= '/';

            $ex = explode(':', $path);
            for($i=1;$i<count($ex);$i++){
                $l .= $ex[$i].'/';
            }

            $l .= $image;

        }


        return $l;

    }

}
