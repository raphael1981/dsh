<?php

namespace App\Services;
use App\Entities\Link;
use App\Repositories;

class LinkParseHelper{

    private $link;
    private $agenda;
    private $content;

    public function __construct(Repositories\LinkRepositoryEloquent $link, Repositories\AgendaRepositoryEloquent $agenda, Repositories\ContentRepositoryEloquent $content){

        $this->link = $link;
        $this->agenda = $agenda;
        $this->content = $content;

    }

    public function parseLink($id, $lang_id=2){



        $std = new \stdClass();

        $link = Link::find($id);


        $params = \GuzzleHttp\json_decode($link->template()->first()->params);



        if(!is_null($link->description_links)){
            $link->description_links = \GuzzleHttp\json_decode($link->description_links);
        }


        if($params->is_single){

            $link_p = \GuzzleHttp\json_decode($link->params);
            $std->params = $link_p;
            $std->link = $link;
            $std->color = $link_p->color;
            $std->folder = 'single';
            $std->master = 'singlelink';
            $std->controller = 'front/links/'.$std->folder.'.controller.js';


            $entity = $std->params->model->entity;

            $std->single = $entity::find($std->params->model->id);
            $std->viewprofile = \GuzzleHttp\json_decode($std->single->params);
            $std->links = $std->single->links()->get();
            $std->attachments = [];

            foreach($std->single->medias()->get() as $k=>$attach){

                $attach->params = \GuzzleHttp\json_decode($attach->params);
                array_push($std->attachments,$attach);

            }

            $std->galleries = [];
            foreach($std->single->galleries()->get() as $k=>$gallery){
                $el = new \stdClass();
                $el->gallery = $gallery;
                $el->pictures = [];

                $pics = $gallery->pictures();

                if($pics->count()>8){
                    $el->is_more_open = true;
                    $el->plus = $pics->count()-8;
                }else{
                    $el->is_more_open = false;
                    $el->plus = null;
                }

                foreach($pics->get() as $i=>$p){

                    $p->real_url = self::createUrlFromDiskParams($p->image_name,$p->disk,$p->image_path);
                    $p->size = getimagesize(storage_path().'/app/'.$p->real_url);
                    $p->size_to_gallery = $p->size[0].'x'.$p->size[1];
                    $p->translations = \GuzzleHttp\json_decode($p->translations);
                    array_push($el->pictures,$p);

                }


                array_push($std->galleries, $el);
            }

//            $std->footmenu = $this->getTreeRecursionRoot($link);

            return $std;

        }

        if($params->is_many){

            $link_p = \GuzzleHttp\json_decode($link->params);
            $std->params = $link_p;
            $std->link = $link;
            $std->color = $link_p->color;
            $std->folder = 'many';
            $std->master = 'many';
            $std->controller = 'front/links/'.$std->folder.'.controller.js';
//            $items = $this->link->getAllItems($link->id);
            $items = $link->contents()->orderBy('ord','asc')->get();
            $std->many_data = [];

            foreach($items as $key=>$value){

                $value->params = \GuzzleHttp\json_decode($value->params);
                array_push($std->many_data, $value);
            }

//            $std->footmenu = $this->getTreeRecursionRoot($link);

            return $std;

        }

        if($params->is_unstandard){
            $link_p = \GuzzleHttp\json_decode($link->params);
            $std->params = $link_p;
            $std->link = $link;
            $std->other_links = Link::where(['ref'=>$link->ref, 'status'=>1])->orderBy('ord','asc')->get();
            //\App\Services\DshHelper::addToFile('linczki.txt',\GuzzleHttp\json_encode($link_p));
            $std->color = $link_p->color;
            $std->folder = $link_p->config->blade;
            $std->master = 'unstandard';
            $std->controller = 'front/links/'.$std->master.'.controller.js';
            if($link_p->config->is_entity_data){
                $entity = $link_p->config->entity;
                $std->ent_data = $entity::where(['status'=>1, 'language_id'=>$lang_id])->orderBy('created_at','desc')->get();
            }

            $ot_links = [];

            foreach($std->other_links as $k=>$l){

                if($std->link->id==$l->id){
                    $l->active = true;
                }else{
                    $l->active = false;
                }

                array_push($ot_links,$l);

            }

            $std->other_links = $ot_links;

//            $std->footmenu = $this->getTreeRecursionRoot($link);

            return $std;
        }

        if($params->is_filtered){

            $link_p = \GuzzleHttp\json_decode($link->params);
            $std->params = $link_p;
            $std->link = $link;
            $std->color = $link_p->color;
            $std->filters = $link_p->filter;
            $std->folder = 'filtered';
            $std->master = 'filtered';
            $std->controller = 'front/links/'.$std->folder.'.controller.js';

//            $std->footmenu = $this->getTreeRecursionRoot($link);

            return $std;

        }


        if($params->is_advanced_filtered){

            $link_p = \GuzzleHttp\json_decode($link->params);
            $std->params = $link_p;
            $std->link = $link;
            $std->color = $link_p->color;
            $std->filters = $link_p->filter;
            $std->folder = 'filteredadvanced';
            $std->master = 'filteredadvanced';
            $std->controller = 'front/links/'.$std->folder.'.controller.js';

//            $std->footmenu = $this->getTreeRecursionRoot($link);

            return $std;

        }


        if($params->is_filtered_content){

            $link_p = \GuzzleHttp\json_decode($link->params);
            $std->params = $link_p;
            $std->link = $link;
            $std->color = $link_p->color;
            $std->filters = $link_p->filter;
            $std->is_year_in_filter = $link_p->is_year_in_filter;
            $std->folder = 'filteredcontent';
            $std->master = 'filteredcontent';
            $std->controller = 'front/links/'.$std->folder.'.controller.js';

//            $std->footmenu = $this->getTreeRecursionRoot($link);

            return $std;

        }

        if($params->is_firtsleaf){

            return $this->parseLink(Link::where('ref',$id)->orderBy('ord','asc')->first()->id);

        }

        if($params->is_filteredleaf){

            $link_p = \GuzzleHttp\json_decode($link->params);
            $std->params = $link_p;
            $std->link = $link;
            $std->color = $link_p->color;
            $std->links = Link::where('ref',$id)->get();
            $std->folder = 'filteredleaf';
            $std->master = 'filteredleaf';
            $std->controller = 'front/links/'.$std->folder.'.controller.js';

//            $std->footmenu = $this->getTreeRecursionRoot($link);

            return $std;

        }


        if($params->is_archive_content){
            $link_p = \GuzzleHttp\json_decode($link->params);
            $std->params = $link_p;
            $std->link = $link;
            $std->color = $link_p->color;
            $std->folder = 'archivecontent';
            $std->master = 'archivecontent';
            $std->controller = 'front/links/'.$std->folder.'.controller.js';

            return $std;

        }

    }


    public function getTreeFirstChildTreeRecursion($id){

        $std = new \stdClass();

        $std->root_link = Link::find($id);
        $std->lcoll =  $this->link->findWhere(['ref'=>$id,'status'=>1]);


        return $std;

    }


    private function getTreeRecursionRoot($link){

        $std = new \stdClass();

        if(is_null($link->id)){

            $std->root_link = $link;
            $std->lcoll =  $this->link->findWhere(['ref'=>$link->id,'status'=>1]);

        }else{

            $ex = explode('/',$link->path);
            $root = $this->link->findWhere(['alias'=>$ex[0], 'ref'=>null])->first();
            $std->root_link = $root;
            $std->lcoll = $this->link->findWhere(['ref'=>$root->id,'status'=>1]);

        }


        return $std;


    }


    public function getLinkMenuRecById($link){
        return $this->getTreeRecursionRoot($link);
    }

    public static function createUrlFromDiskParams($name,$disk,$path){

        $npath = '';

        if($path==':'){

            $npath .= '/'.$disk;

        }else{

            $npath .= '/'.$disk;

            $ex = explode(':',$path);
            for($i=1;$i<count($ex);$i++){

                $npath .= '/'.$ex[$i];

            }

        }

        return $npath.'/'.$name;

    }



}