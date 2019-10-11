<?php

namespace App\Repositories;

use App\Entities\Agenda;
use App\Entities\Content;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\DB;
use App\Repositories\LinkRepository;
use App\Entities\Link;
use App\Validators\LinkValidator;

/**
 * Class LinkRepositoryEloquent
 * @package namespace App\Repositories;
 */
class LinkRepositoryEloquent extends BaseRepository implements LinkRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Link::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }




    public function getItems($id){
        $items = new \stdClass;
        $items->contents = Link::find($id)->contents()->get();
        $items->agendas = Link::find($id)->agendas()->get();
        return $items;
    }
    


    public function getAllItems($id){
    $query = "SELECT lnk.id as link_id,
                     lnk.language_id as lang_id,
                     lnk.ref as link_ref,
                     lnk.title as link_title,
                     lnk.alias as link_alias,
                     lnk.path as link_path,
                     lnk.params as link_params,
                     lnk.meta_description as link_meta_description,
                     lnk.meta_keywords as link_meta_keywords,
                     lgb.linkgables_type as lgb_linkgables_type,
                     lgb.ord as lgb_ord,
                     lgb.status as status,
                     ag.id as ag_id,
                     cnt.id as cnt_id,
                     ag.title as ag_title,
                     ag.alias as ag_alias,
                     ag.suffix as ag_suffix,
                     ag.begin as data_start,
                     ag.end as data_end,
                     ag.disk as ag_disk,
                     ag.image as ag_image,
                     ag.image_path as ag_image_path,
                     ag.intro as ag_intro,
                     ag.created_at as created_at,
                     ag.params as ag_params,
                     cnt.title as cnt_title,
                     cnt.alias as cnt_alias,
                     cnt.author as cnt_author,
                     cnt.suffix as cnt_suffix,
                     cnt.disk as cnt_disk,
                     cnt.image as cnt_image,
                     cnt.image_path as cnt_image_path,
                     cnt.intro as cnt_intro,
                     cnt.created_at as created_at,
                     cnt.params as cnt_params
                     FROM links as lnk
                     LEFT JOIN linkgables as lgb ON lnk.id=lgb.link_id
                     LEFT JOIN agendas as ag ON lgb.linkgables_id=ag.id
                     LEFT JOIN contents as cnt ON lgb.linkgables_id=cnt.id
                     WHERE lnk.id='".$id."' ORDER BY lgb.ord ASC, ag.begin DESC, cnt.created_at, ag.created_at";

    $result = DB::select( DB::raw($query));

    return $result;

  }

  public function changeActive($idLink,$status){
      $query = "UPDATE links SET status='".$status."' WHERE id='".$idLink."'";
      DB::update($query);
  }


    public function setNewStatus($idLink, $idcnt, $type, $status){
        $query = "UPDATE linkgables SET status='".$status."'
                     WHERE link_id='".$idLink."'
                     AND linkgables_id='".$idcnt."'
                     AND linkgables_type like '%".$type."%'";

        DB::update($query);
    }
    public function removeFromLink($linkId, $itemId,$type){
        $query = "DELETE FROM linkgables WHERE link_id='".$linkId."'
                  AND linkgables_id='".$itemId."'
                  AND linkgables_type like '%".$type."%'";

        DB::delete($query);
        return $query;
    }
    public function setNewOrder($data, $link_id){

        $iter = 1;

        foreach($data as $key=>$item){

            $query = "UPDATE linkgables SET ord='".$iter."'
                     WHERE link_id='".$link_id."'
                     AND linkgables_id='".$item['id']."'
                     AND linkgables_type like '%".$item['row_type']."%'";

            DB::update($query);

            $iter++;

        }

    }

    public function createTreeFromArray($array, $lang_id){
        $this->treeSaveRecursionFromArray(null, $lang_id, $array);
    }

    private function treeSaveRecursionFromArray($path, $lang_id, $array, $parent_id=null){

        foreach($array as $key=>$value){

//            if(is_null($path) && is_null($parent_id)){
//                $path = str_slug($value['title'],'-');
//            }else{
//                $path .= '/'.str_slug($value['title'],'-');
//            }

            $model = $this->create([
                'language_id'=>$lang_id,
                'template_id'=>$value['template_id'],
                'ref'=>$parent_id,
                'title'=>$value['title'],
                'alias'=>str_slug($value['title'],'-'),
                'path'=>'',
                'link'=>'',
                'description'=>$value['description'],
                'description_links'=>$value['description_links'],
                'ltype'=>$value['ltype'],
                'params'=>$value['params'],
                'ord'=>$key+1,
                'meta_description'=>'',
                'meta_keywords'=>''
            ]);

            $linkpath = $this->getPathForward($model->id, $apath=[]);

            $model->update(
                [
                    'path'=>$linkpath->path,
                    'link'=>$linkpath->link
                ]);

            if(array_key_exists('children',$value)){
                $this->treeSaveRecursionFromArray($path, $lang_id, $value['children'], $model->id);
            }
        }

    }


    private function getPathForward($id, $apath){


        if(!is_null($id)){

            $link = Link::find($id);

            array_push(
                $apath,
                $link->alias
            );

            return $this->getPathForward($link->ref, $apath);

        }

        $std = new \stdClass();

        $apath = array_reverse($apath);

        $spath = '';
        $link = '';

        foreach($apath as $key=>$p){

            if(end($apath)!=$p){

                $spath .= $p.'/';
                if($key==0){
                    $link .= $p.'/';
                }

            }else{
                $spath .= $p;
                $link .= $p;
            }

        }

        $std->path = $spath;
        $std->link = $link;

        return $std;

    }




    public function getTreeArray($lang_id){
        return $this->getTreeArrayRecursion($path='', $array = [], $parent_id=null, $lang_id);
    }


    private function getTreeArrayRecursion($path='', $array = [], $parent_id=null, $lang_id){

//        $links = $this->findWhere(['ref'=>$parent_id, 'language_id'=>$lang_id]);
        $links = Link::where(['ref'=>$parent_id, 'language_id'=>$lang_id])->orderBy('ord', 'asc')->get();

        if(count($links)>0) {

            foreach ($links as $key=>$value){

                $more = $this->findWhere(['ref'=>$value->id]);

                if(count($more)>0) {

                    $array[$key] = [
                        'name'=>$value['title'],
                        'path'=>$value['path'],
                        'all'=>$value,
                        'children'=>[]
                    ];

                    $array[$key]['children'] = $this->getTreeArrayRecursion($path, $array[$key]['children'], $value->id, $lang_id);

                }else{

                    $array[$key] = [
                        'name'=>$value['title'],
                        'path'=>$value['path'],
                        'all'=>$value,
                    ];
                }
            }

        }

        return $array;


    }


    public function getTreeArrayTreeUi($lang_id){
        return $this->getTreeArrayRecursionTreeUi($path='', $array = [], $parent_id=null, $lang_id);
    }


    private function getTreeArrayRecursionTreeUi($path='', $array = [], $parent_id=null, $lang_id){

//        $links = $this->findWhere(['ref'=>$parent_id, 'language_id'=>$lang_id]);
        $links = Link::where(['ref'=>$parent_id, 'language_id'=>$lang_id])->orderBy('ord', 'asc')->get();

        if(count($links)>0) {

            foreach ($links as $key=>$value){

                $more = $this->findWhere(['ref'=>$value->id]);

                if(count($more)>0) {

                    $array[$key] = [
                        'id'=>$value['id'],
                        'title'=>$value['title'],
                        'ref'=>$value['ref'],
                        'status'=>$value['status'],
                        'params'=>\GuzzleHttp\json_decode($value['params']),
                        'nodes'=>[]
                    ];

                    $array[$key]['nodes'] = $this->getTreeArrayRecursionTreeUi($path, $array[$key]['nodes'], $value->id, $lang_id);

                }else{

                    $array[$key] = [
                        'id'=>$value['id'],
                        'title'=>$value['title'],
                        'ref'=>$value['ref'],
                        'status'=>$value['status'],
                        'params'=>\GuzzleHttp\json_decode($value['params']),
                        'nodes'=>[]
                    ];
                }
            }

        }

        return $array;


    }





    public function updateTree($tree, $lang_id){

        $now_all = $this->findWhere(['language_id'=>$lang_id]);

        $now_ids = [];

        foreach($now_all as $key=>$value){
            array_push($now_ids, $value->id);
        }

        $update_all =  $this->updateTreeArrayRecursionTreeUi($path=null, $tree, $parent_id=null, $lang_id, $all=[], $color=null);

        $update_ids = [];

        foreach($update_all as $key=>$value){
            array_push($update_ids, $value->id);
        }


        $to_remove = array_diff($now_ids, $update_ids);


        foreach($to_remove as $key=>$value){

            $this->delete($value);

        }

        return '{"success":true}';

    }


    private function updateTreeArrayRecursionTreeUi($path=null, $array = [], $parent_id=null, $lang_id, $all=[], $color=null){

        $colors = config('services')['template_colors'];

        foreach($array as $key=>$value){

//            if(is_null($path) && is_null($parent_id)){
//                $path = str_slug($value['title'],'-');
//            }else{
//                $path .= '/'.str_slug($value['title'],'-');
//            }

            if(is_null($value['id'])){

                $nl = $this->create([
                    'language_id'=>$lang_id,
                    'template_id'=>null,
                    'ref'=>$parent_id,
                    'title'=>$value['title'],
                    'alias'=>str_slug($value['title'],'-'),
                    'path'=>'',
                    'link'=>'',
                    'params'=>(is_null($parent_id))?$this->paramsGetColor($value['id']):$this->createParamsFromParentColorNew($color),
                    'ord'=>$key+1,
                    'meta_description'=>'',
                    'meta_keywords'=>''
                ]);

                $linkpath = $this->getPathForwardUi($nl->id, $spath=[]);

                $this->update(
                    [
                        'path'=>$linkpath->path,
                        'link'=>$linkpath->link
                    ],
                    $nl->id);

                array_push($all, $nl);

                if(count($value['nodes'])>0){
                    $all = $this->updateTreeArrayRecursionTreeUi($path, $value['nodes'], $nl->id, $lang_id, $all, $color);
                }

            }else{



                $old = $this->update([
                    'language_id'=>$lang_id,
                    'ref'=>$parent_id,
                    'title'=>$value['title'],
                    'alias'=>str_slug($value['title'],'-'),
                    'path'=>'',
                    'link'=>'',
                    'params'=>(is_null($parent_id))?$this->paramsGetColor($value['id']):$this->createParamsFromParentColor($color, $value['id']),
                    'ord'=>$key+1,
                    'meta_description'=>'',
                    'meta_keywords'=>''
                ], $value['id']);

                $linkpath = $this->getPathForwardUi($value['id'], $spath=[]);

                $this->update(
                    [
                        'path'=>$linkpath->path,
                        'link'=>$linkpath->link
                    ],
                    $value['id']);

                $prms = \GuzzleHttp\json_decode($this->find($value['id'])->params);

                array_push($all, $old);

                if(count($value['nodes'])>0){
                    $all = $this->updateTreeArrayRecursionTreeUi($path, $value['nodes'], $value['id'], $lang_id, $all, $color=\GuzzleHttp\json_encode($prms->color));
                }

            }

        }


        return $all;

    }


    private function getPathForwardUi($id, $apath){

        if(!is_null($id)){

            $link = Link::find($id);

            array_push(
                $apath,
                $link->alias
            );

            return $this->getPathForwardUi($link->ref, $apath);

        }

        $std = new \stdClass();

        $apath = array_reverse($apath);

        $spath = '';
        $link = '';

        foreach($apath as $key=>$p){

            if(end($apath)!=$p){

                $spath .= $p.'/';
                if($key==0){
                    $link .= $p.'/';
                }

            }else{
                $spath .= $p;
                $link .= $p;
            }

        }

        $std->path = $spath;
        $std->link = $link;

        return $std;


    }


    private function paramsGetColor($id){

        $colors = config('services')['template_colors'];
        $p = \GuzzleHttp\json_decode($this->find($id)->params);

        if(!isset($p->color)) {
            $p->color = $colors[0];
            return \GuzzleHttp\json_encode($p);
        }

        return $this->find($id)->params;
    }

    private function createParamsFromParentColor($c, $id){

        $c = \GuzzleHttp\json_decode($c);
        $p = \GuzzleHttp\json_decode($this->find($id)->params);
        $p->color = $c;

        return \GuzzleHttp\json_encode($p);

    }

    private function createParamsFromParentColorNew($c){

        $std = new \stdClass();
        $std->color = \GuzzleHttp\json_decode($c);

        return \GuzzleHttp\json_encode($std);

    }

    private function findElementOutOfTree($now, $upd){

        $del_array = [];

        foreach($now as $key=>$no){

            if($this->checkIsInArray($no, $upd)){

            }else{
                array_push($del_array, $this->checkIsInArray($no, $upd));
            }

        }

        return $del_array;

    }


    public function getTreeArrayContentLinks($langid, $conid){

        return $this->getTreeArrayContentTreeUi($array = [], $parent_id=null, $langid, $conid);
    }

    private function getTreeArrayContentTreeUi($array = [], $parent_id=null, $langid, $conid){

        $links = Link::where(['ref'=>$parent_id, 'language_id'=>$langid])->orderBy('ord', 'asc')->get();

        if(count($links)>0) {

            foreach ($links as $key=>$value){

                $more = $this->findWhere(['ref'=>$value->id]);

                if(count($more)>0) {

                    $array[$key] = [
                        'id'=>$value['id'],
                        'name'=>$value['title'],
                        'ref'=>$value['ref'],
                        'checked'=>(Link::find($value['id'])->contents()->where('id', $conid)->count()>0)?true:false,
                        'children'=>[]
                    ];

                    $array[$key]['children'] = $this->getTreeArrayContentTreeUi($array[$key]['children'], $value->id, $langid,$conid);

                }else{

                    $array[$key] = [
                        'id'=>$value['id'],
                        'name'=>$value['title'],
                        'ref'=>$value['ref'],
                        'checked'=>(Link::find($value['id'])->contents()->where('id', $conid)->count()>0)?true:false,
                        'children'=>[]
                    ];
                }
            }

        }

        return $array;

    }


    public function getTreeArrayContentLinksEmpty($langid){

        return $this->getTreeArrayContentEmptyTreeUi($array = [], $parent_id=null, $langid);
    }

    public function getTreeArrayContentEmptyTreeUi($array = [], $parent_id=null, $langid){

        $links = Link::where(['ref'=>$parent_id, 'language_id'=>$langid])->orderBy('ord', 'asc')->get();


        if(count($links)>0) {

            foreach ($links as $key=>$value){

                $more = $this->findWhere(['ref'=>$value->id]);

                if(count($more)>0) {

                    $array[$key] = [
                        'id'=>$value['id'],
                        'name'=>$value['title'],
                        'ref'=>$value['ref'],
                        'checked'=>false,
                        'children'=>[]
                    ];

                    $array[$key]['children'] = $this->getTreeArrayContentEmptyTreeUi($array[$key]['children'], $value->id, $langid);

                }else{

                    $array[$key] = [
                        'id'=>$value['id'],
                        'name'=>$value['title'],
                        'ref'=>$value['ref'],
                        'checked'=>false,
                        'children'=>[]
                    ];
                }
            }

        }

        return $array;

    }


    public function updateTreeArrayContentLinks($langid, $conid, $tree){

        $this->updateTreeArrayRecursionContent($tree, $parent_id=null, $langid, $conid);

    }


    private function updateTreeArrayRecursionContent($array = [], $parent_id=null, $lang_id, $conid, $all=[]){


        foreach($array as $key=>$value){

            if($value['checked']){

                if(!$this->checkIsLinkGablesExist($conid, $value['id'])){
                    Content::find($conid)->links()->save(Link::find($value['id']));
                }else{

                }


            }else{
                Link::find($value['id'])->contents()->detach($conid);
            }

            if(count($value['children'])>0){
                $all = $this->updateTreeArrayRecursionContent($value['children'], $value['id'], $lang_id, $conid, $all);
            }


        }


        return $all;

    }


    private function checkIsLinkGablesExist($cid, $lid){

        $bool = false;

        foreach(Content::find($cid)->links()->get() as $k=>$l){

            if($l->id==$lid){
                $bool=true;
            }

        }

        return $bool;

    }


    public function getTreeArrayAgendaLinksEmpty($langid){

        return $this->getTreeArrayAgendaEmptyTreeUi($array = [], $parent_id=null, $langid);
    }

    private function getTreeArrayAgendaEmptyTreeUi($array = [], $parent_id=null, $langid){

        $links = Link::where(['ref'=>$parent_id, 'language_id'=>$langid])->orderBy('ord', 'asc')->get();

        if(count($links)>0) {

            foreach ($links as $key=>$value){

                $more = $this->findWhere(['ref'=>$value->id]);

                if(count($more)>0) {

                    $array[$key] = [
                        'id'=>$value['id'],
                        'name'=>$value['title'],
                        'ref'=>$value['ref'],
                        'checked'=>false,
                        'children'=>[]
                    ];

                    $array[$key]['children'] = $this->getTreeArrayAgendaEmptyTreeUi($array[$key]['children'], $value->id, $langid);

                }else{

                    $array[$key] = [
                        'id'=>$value['id'],
                        'name'=>$value['title'],
                        'ref'=>$value['ref'],
                        'checked'=>false,
                        'children'=>[]
                    ];
                }
            }

        }

        return $array;

    }

    public function getTreeArrayAgendaLinks($langid, $aid){

        return $this->getTreeArrayAgendaTreeUi($array = [], $parent_id=null, $langid, $aid);
    }



    private function getTreeArrayAgendaTreeUi($array = [], $parent_id=null, $langid, $aid){

        $links = Link::where(['ref'=>$parent_id, 'language_id'=>$langid])->orderBy('ord', 'asc')->get();

        if(count($links)>0) {

            foreach ($links as $key=>$value){

                $more = $this->findWhere(['ref'=>$value->id]);

                if(count($more)>0) {

                    $array[$key] = [
                        'id'=>$value['id'],
                        'name'=>$value['title'],
                        'ref'=>$value['ref'],
                        'checked'=>(Link::find($value['id'])->agendas()->where('id', $aid)->count()>0)?true:false,
                        'children'=>[]
                    ];

                    $array[$key]['children'] = $this->getTreeArrayAgendaTreeUi($array[$key]['children'], $value->id, $langid, $aid);

                }else{

                    $array[$key] = [
                        'id'=>$value['id'],
                        'name'=>$value['title'],
                        'ref'=>$value['ref'],
                        'checked'=>(Link::find($value['id'])->agendas()->where('id', $aid)->count()>0)?true:false,
                        'children'=>[]
                    ];
                }
            }

        }

        return $array;

    }


    public function updateTreeArrayAgendaLinks($langid, $aid, $tree){

        return $this->updateTreeArrayRecursionAgendas($tree, $parent_id=null, $langid, $aid);

    }



    private function updateTreeArrayRecursionAgendas($array = [], $parent_id=null, $lang_id, $aid, $all=[]){


        foreach($array as $key=>$value){

            if($value['checked']){
                Agenda::find($aid)->links()->save(Link::find($value['id']));
            }else{
                Link::find($value['id'])->agendas()->detach($aid);
            }

            if(count($value['children'])>0){
                $all = $this->updateTreeArrayRecursionAgendas($value['children'], $value['id'], $lang_id, $aid, $all);
            }


        }


        return $all;

    }


    public function getLinkLeafs($id){

//        return $this->getLinkLeafRecursion($id);
        $fils = $this->getLinkLeafRecursion($id);
        $flink = Link::find($id);
        array_unshift($fils, $flink);

        return $fils;

    }

    private function getLinkLeafRecursion($ref, $array=[]){

        $lkn = Link::where(['ref'=>$ref,'status'=>1]);

        if($lkn->count()>0) {

            foreach ($lkn->get() as $key => $link) {

                array_push($array, $link);
                $array = $this->getLinkLeafRecursion($link->id, $array);


            }

        }

        return $array;

    }


    public function getAllItemsArrayLinks($array, $year){

        $dates = $this->getFirstAndLastDayOfYear($year);


        $str_in = "(";

        foreach($array as $key=>$id){

            if($key==0){
                $str_in .= $id;
            }else{
                $str_in .= ','.$id;
            }

        }

        $str_in .= ")";



        $query = "SELECT lnk.id as link_id,
                     lnk.language_id as lang_id,
                     lnk.ref as link_ref,
                     lnk.title as link_title,
                     lnk.alias as link_alias,
                     lnk.path as link_path,
                     lnk.params as link_params,
                     lnk.meta_description as link_meta_description,
                     lnk.meta_keywords as link_meta_keywords,
                     lgb.linkgables_type as lgb_linkgables_type,
                     lgb.ord as lgb_ord,
                     lgb.status as status,
                     ag.id as ag_id,
                     cnt.id as cnt_id,
                     ag.title as ag_title,
                     ag.alias as ag_alias,
                     ag.suffix as ag_suffix,
                     ag.begin as data_start,
                     ag.end as data_end,
                     ag.disk as ag_disk,
                     ag.image as ag_image,
                     ag.image_path as ag_image_path,
                     ag.intro as ag_intro,
                     ag.created_at as created_at,
                     ag.params as ag_params,
                     cnt.title as cnt_title,
                     cnt.alias as cnt_alias,
                     cnt.suffix as cnt_suffix,
                     cnt.author as cnt_author,
                     cnt.disk as cnt_disk,
                     cnt.image as cnt_image,
                     cnt.image_path as cnt_image_path,
                     cnt.intro as cnt_intro,
                     cnt.created_at as created_at,
                     cnt.params as cnt_params
                     FROM links as lnk
                     LEFT JOIN linkgables as lgb ON lnk.id=lgb.link_id
                     LEFT JOIN agendas as ag ON lgb.linkgables_id=ag.id
                     LEFT JOIN contents as cnt ON lgb.linkgables_id=cnt.id
                     WHERE lnk.id IN ".$str_in."
                     AND cnt.created_at>'".$dates->start."' AND cnt.created_at<'".$dates->end."'
                     AND ag.created_at>'".$dates->start."' AND ag.created_at<'".$dates->end."'
                     ORDER BY lgb.ord ASC, ag.begin DESC, cnt.created_at, ag.created_at";

        $result = DB::select( DB::raw($query));

        return $result;

    }


    public function templateUpdateLinkData($ttype, $tid, $lid, $params, $desc){


        if($ttype['is_filtered']){

            $obj = \GuzzleHttp\json_decode($this->find($lid)->params);
            $obj->filter = [];


            foreach($params['categories'] as $k=>$c){

                $std = new \stdClass();
                $std->entity = 'App\Entities\Category';
                $std->id = $c;

                array_push($obj->filter, $std);

            }

            $obj->elastic_view = $params['elastic_view'];
            $obj->group = $params['group'];
            $obj->order = $params['order'];

            $this->update([
                'template_id'=>$tid,
                'params'=>\GuzzleHttp\json_encode($obj)
            ], $lid);


        }

        if($ttype['is_advanced_filtered']){

            $obj = \GuzzleHttp\json_decode($this->find($lid)->params);
            $obj->filter = [];


            foreach($params['categories'] as $k=>$c){

                $std = new \stdClass();
                $std->entity = 'App\Entities\Category';
                $std->id = $c;

                array_push($obj->filter, $std);

            }

            $this->update([
                'template_id'=>$tid,
                'params'=>\GuzzleHttp\json_encode($obj)
            ], $lid);


        }

        if($ttype['is_filtered_content']){

            $obj = \GuzzleHttp\json_decode($this->find($lid)->params);
            $obj->filter = [];


            foreach($params['categories'] as $k=>$c){

                $std = new \stdClass();
                $std->entity = 'App\Entities\Category';
                $std->id = $c;

                array_push($obj->filter, $std);

            }

            $obj->order = $params['order'];
            $obj->is_year_in_filter = $params['is_year_in_filter'];
            $obj->is_filters_active = $params['is_filters_active'];
            $obj->config = $params['config'];

            $this->update([
                'template_id'=>$tid,
                'params'=>\GuzzleHttp\json_encode($obj)
            ], $lid);

        }

        if($ttype['is_filteredleaf']){

            $obj = \GuzzleHttp\json_decode($this->find($lid)->params);
            $obj->config = $params['config'];

            $this->update([
                'template_id'=>$tid,
                'params'=>\GuzzleHttp\json_encode($obj)
            ], $lid);


        }

        if($ttype['is_firtsleaf']){

            $obj = \GuzzleHttp\json_decode($this->find($lid)->params);

            $this->update([
                'template_id'=>$tid,
                'params'=>\GuzzleHttp\json_encode($obj)
            ], $lid);


        }

        if($ttype['is_many']){

            $obj = \GuzzleHttp\json_decode($this->find($lid)->params);
            $obj->config = $params['config'];

            $this->update([
                'template_id'=>$tid,
                'params'=>\GuzzleHttp\json_encode($obj)
            ], $lid);


        }

        if($ttype['is_single']){

            $p = \GuzzleHttp\json_decode($this->find($lid)->params);

            $std = new \stdClass();
            $std->id = $params['entity_model']['id'];
            $std->entity = $params['entity_model']['entity'];
            $p->model = $std;

            $this->update([
                'template_id'=>$tid,
                'params'=>\GuzzleHttp\json_encode($p)
            ], $lid);

        }

        if($ttype['is_unstandard']){

            $obj = \GuzzleHttp\json_decode($this->find($lid)->params);

            $this->update([
                'template_id'=>$tid,
                'params'=>\GuzzleHttp\json_encode($obj)
            ], $lid);

        }


        if($ttype['is_archive_content']){

            $obj = \GuzzleHttp\json_decode($this->find($lid)->params);
            $obj->filter = [];

            foreach($params['categories'] as $k=>$c){

                $std = new \stdClass();
                $std->entity = 'App\Entities\Category';
                $std->id = $c;

                array_push($obj->filter, $std);

            }

            $this->update([
                'template_id'=>$tid,
                'params'=>\GuzzleHttp\json_encode($obj)
            ], $lid);


        }

        ///////////////Update Desc//////////////////////////////////////

        $obj_desc = \GuzzleHttp\json_decode($this->find($lid)->params);
        $obj_desc->is_show_desc = $desc['is_show_desc'];



        $this->update([
            'description'=>$desc['desc'],
            'description_links'=>\GuzzleHttp\json_encode($desc['links']),
            'params'=>\GuzzleHttp\json_encode($obj_desc)
        ], $lid);

        return true;

    }


    private function getFirstAndLastDayOfYear($year){

        $std = new \stdClass();
        $std->start = $year.'-01-01';
        $std->end = $year.'-12-31';

        return $std;

    }


}
