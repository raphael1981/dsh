<?php

namespace App\Http\Controllers\Front\Ajax;

use App\Entities\Agenda;
use App\Entities\Content;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories;

class ManyListController extends Controller
{

    private $link;

    public function __construct(Repositories\LinkRepositoryEloquent $link)
    {
        $this->link = $link;
    }


    public function getLinkData($prefix, $id){

        $std = new \stdClass();

        $link = $this->link->find($id);
        $link->params = \GuzzleHttp\json_decode($link->params);

        $arr = [];

        $items = $this->link->getAllItems($id);

        foreach($items as $key=>$item){

            if($item->lgb_linkgables_type=='App\Entities\Agenda'){
                $ag = Agenda::find($item->ag_id);
                $item->categories = $ag->categories()->get();
                $item->image = new \stdClass();
                $item->image->disk = $ag->disk;
                $item->image->image = $ag->image;
                $item->image->image_path = $ag->image_path;
                $item->params = \GuzzleHttp\json_decode($item->ag_params);
                $item->id = $ag->id;
                $item->alias = $ag->alias;
                $item->suffix = $ag->suffix;
            }else{
                $ct = Content::find($item->cnt_id);
                $item->categories = null;
                $item->image = new \stdClass();
                $item->image->disk = $ct->disk;
                $item->image->image = $ct->image;
                $item->image->image_path = $ct->image_path;
                $item->params = \GuzzleHttp\json_decode($item->cnt_params);
                $item->id = $ct->id;
                $item->alias = $ct->alias;
                $item->suffix = $ct->suffix;
            }

            array_push($arr,$item);

        }

        $std->link = $link;
        $std->list = $arr;


        return \GuzzleHttp\json_encode($std);

    }

}
