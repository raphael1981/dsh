<?php

namespace App\Http\Controllers\Front\Ajax;

use App\Entities\Agenda;
use App\Entities\Content;
use App\Entities\Link;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class FilterLeafListController extends Controller
{
    private $link;

    public function __construct(Repositories\LinkRepositoryEloquent $link)
    {
        $this->link = $link;
    }


    public function getLinkLeafs($prefix, $id){

//        dd($this->link->getAllItemsArrayLinks($this->link->getLinkLeafs($id)));
        return $this->link->getLinkLeafs($id);
    }


    public function getLeafFilterData(Request $request){

        $arr = [];

        $items = DB::table('linkgables')
                    ->whereIn('linkgables.link_id', $request->get('filters'))
                    ->where(function ($query) {
                        $query->where('linkgables.linkgables_type', 'App\Entities\Content');
                        $query->orWhere('linkgables.linkgables_type', 'App\Entities\Agenda');
                    })
                    ->orderBy('ord','asc')
                    ->get();



        foreach($items as $key=>$item){

            if($item->linkgables_type=='App\Entities\Agenda'){
                $ag = Agenda::find($item->linkgables_id);
//                $ag = Cache::get('agenda:entity:'.$item->linkgables_id);
                $item->entity = $ag;
                $item->categories = Cache::get('agenda:categories:'.$ag->id);
                $item->image = new \stdClass();
                $item->image->disk = $ag->disk;
                $item->image->image = $ag->image;
                $item->image->image_path = $ag->image_path;
                $item->params = \GuzzleHttp\json_decode($ag->params);
                $item->clear_content = strip_tags($item->entity->content);
                $item->id = $ag->id;
                $item->alias = $ag->alias;
                $item->suffix = $ag->suffix;
                $item->slug = $ag->id.'-'.$ag->alias;
            }else{
                $ct = Content::find($item->linkgables_id);
//                $ct = Cache::get('content:entity:'.$item->linkgables_id);
                $item->entity = $ct;
                $item->categories = Cache::get('content:links:'.$ct->id);
                $item->image = new \stdClass();
                $item->image->disk = $ct->disk;
                $item->image->image = $ct->image;
                $item->image->image_path = $ct->image_path;
                $item->params = \GuzzleHttp\json_decode($ct->params);
                $item->clear_content = strip_tags($item->entity->content);
                $item->id = $ct->id;
                $item->alias = $ct->alias;
                $item->suffix = $ct->suffix;
                $item->slug = $ct->id.'-'.$ct->alias;
            }

            array_push($arr, \GuzzleHttp\json_decode(\GuzzleHttp\json_encode($item),true));

        }
        $arr = collect($arr);
        $arr = $arr->unique('slug');

        return \GuzzleHttp\json_encode($arr);


        return $items;
//        $items = $this->link->getAllItemsArrayLinks($request->get('filters'),$request->get('year'));



//        foreach($items as $key=>$item){
//
//            if($item->lgb_linkgables_type=='App\Entities\Agenda'){
//                $ag = Agenda::find($item->ag_id);
//                $item->categories = Cache::get('agenda:categories:'.$item->ag_id);
//                $item->image = new \stdClass();
//                $item->image->disk = $ag->disk;
//                $item->image->image = $ag->image;
//                $item->image->image_path = $ag->image_path;
//                $item->params = \GuzzleHttp\json_decode($item->ag_params);
//                $item->id = $ag->id;
//                $item->alias = $ag->alias;
//                $item->suffix = $ag->suffix;
//            }else{
//                $ct = Content::find($item->cnt_id);
//                $item->categories = Cache::get('content:categories:'.$item->cnt_id);
//                $item->image = new \stdClass();
//                $item->image->disk = $ct->disk;
//                $item->image->image = $ct->image;
//                $item->image->image_path = $ct->image_path;
//                $item->params = \GuzzleHttp\json_decode($item->cnt_params);
//                $item->id = $ct->id;
//                $item->alias = $ct->alias;
//                $item->suffix = $ct->suffix;
//            }
//
//            array_push($arr,$item);
//
//        }



        return \GuzzleHttp\json_encode($arr);

    }


}
