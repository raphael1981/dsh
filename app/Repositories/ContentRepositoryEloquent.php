<?php

namespace App\Repositories;

use App\Entities\Language;
use App\Entities\Link;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ContentRepository;
use App\Entities\Content;
use App\Validators\ContentValidator;
use App\Services\DshHelper;

/**
 * Class ContentRepositoryEloquent
 * @package namespace App\Repositories;
 */
class ContentRepositoryEloquent extends BaseRepository implements ContentRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Content::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }



    public function searchByCriteria($data){

        $lang = Language::where('id', $data['lang'])->first();

        $query = Content::where(function($q) use ($data){

            foreach($data['searchcolumns'] as $name=>$boolean){
                if($boolean){
                    $q->orWhere($name, 'LIKE', '%' . $data['frase'] . '%');
                }
            }

        });


        foreach($data['filter'] as $name=>$filter) {

            if(!is_null($filter['value'])){
                $query
                    ->where($name, $filter['value']);
            }

        }

        $query
            ->where('language_id', $lang->id);

        $total = $query->count();


        $elements = $query
            ->orderBy('created_at','desc')
            ->skip($data['start'])
            ->take($data['limit'])
            ->get();


        $std = new \stdClass();
        $std->elements = $elements;
        $std->count = $total;

        return \GuzzleHttp\json_encode($std);
    }

    public function getFullContentData($id){

        $std = new \stdClass();

        $c = $this->find($id);

        $std->id = $id;
        $std->title = $c->title;
        $std->introtext = $c->intro;
        $std->fulltext = $c->content;
        $std->author = $c->author;
        $std->published_at = $c->published_at;
        $std->type = $c->type;
        $std->external_url = $c->url;
        $std->params = $c->params;

        if(!is_null($c->suffix) && !is_null($c->params)) {
            $prstd = new \stdClass();
            $prstd->suffix = $c->suffix;
            $json_params = \GuzzleHttp\json_decode($c->params);
            $prstd->name = $json_params->name;
            $prstd->icon = $json_params->icon;
            $prstd->color = $json_params->color;

            $std->view_profile = $prstd;
        }else{
            $std->view_profile = null;
        }


        if($c->image!=''){
            $std->intro_image = new \stdClass();
            $std->intro_image->file = $c->image;
            $std->intro_image->filename = $c->image;
            $std->intro_image->disk = $c->disk;
            $std->intro_image->file = $c->image;

            if($c->image_path==':'){

                $std->path = storage_path().'/app/'.$c->disk.'/'.$c->image;

            }else{

                $ex = explode(':',$c->image_path);

                $subpath = '';

                for($i=1;$i<count($ex);$i++){
                    $subpath .= '/'.$ex[$i];
                }

                $std->intro_image->path = storage_path().'/app/'.$c->disk.$subpath.'/'.$c->image;

            }

            $std->intro_image->request = '/image/'.$c->image.'/'.$c->disk.'/'.$c->image_path.'/200';
            $std->intro_image->request_uncomplete = '/image/'.$c->image.'/'.$c->disk.'/'.$c->image_path.'/';

        }else{
            $std->intro_image = null;
        }

        $std->categories = $c->categories()->get();
        $std->logotypes = $c->logotypes()->get();
        $std->galleries = $c->galleries()->get();
        $std->attachments = $c->medias()->get();

        foreach($std->logotypes as $k=>$l){
            $std->logotypes[$k]->logotypes = \GuzzleHttp\json_decode($std->logotypes[$k]->logotypes);
        }

        return \GuzzleHttp\json_encode($std);

    }

    public function fastSearchByTitle($phrase){

        $default_color = config('services')['default_template_color'];

        $ctn = Content::where('title', 'LIKE', '%'.$phrase.'%')->get();

        foreach($ctn as $k=>$ct){

            $ctn[$k]->intro = strip_tags($ctn[$k]->intro);
            $ctn[$k]->language = Language::find($ct->language_id);
            $ctn[$k]->categories = Content::find($ct->id)->categories()->get();
            $ctn[$k]->params = \GuzzleHttp\json_decode($ctn[$k]->params);
        }

        return $ctn;

    }


    public function getFilterResultsDataYearAndCategories($filters, $current_month, $year, $current_day, $is_current_year, $is_all, $all_filters, $order){


        $array = [];

        $res = [];


        $query = DB::table('contents');
        $query->join('categorygables', 'contents.id', '=', 'categorygables.categorygables_id');
        $query->where('categorygables.categorygables_type','App\Entities\Content');


        $query->where(function($q) use($filters,$is_all,$all_filters) {

            if(!$is_all) {

                foreach ($filters as $key => $filter) {

                    if ($key == 0) {
                        $q->where('categorygables.category_id', $filter['id']);
                    } else {
                        $q->orWhere('categorygables.category_id', $filter['id']);
                    }

                }

            }else{

                foreach ($all_filters as $key => $filter) {

                    if ($key == 0) {
                        $q->where('categorygables.category_id', $filter['id']);
                    } else {
                        $q->orWhere('categorygables.category_id', $filter['id']);
                    }

                }

            }

        });


        if($is_current_year) {

            $begin_year = Carbon::create($year, $current_month, $current_day, 0, 0, 0)->toDateTimeString();
            $end_year = Carbon::create($year, 12, 31, 0, 0, 0)->toDateTimeString();


        }else{

            $begin_year = Carbon::create($year, 1, 1, 0, 0, 0)->toDateTimeString();
            $end_year = Carbon::create($year, 12, 31, 0, 0, 0)->toDateTimeString();
        }

        $order_type='published_at';



        foreach($query->where('status',1)->whereDate('published_at', '>=', $begin_year)->whereDate('published_at', '<=', $end_year)->orderBy($order_type,$order)->get()->unique('id') as $key=>$el){
//            $el->categories = $this->find($el->id)->categories()->get();
            $el->intro = strip_tags($el->intro);
            $el->content = strip_tags($el->content);
            $el->categories = Cache::get('content:categories:'.$el->id);
            $el->params = \GuzzleHttp\json_decode($el->params,true);
            array_push($res, $el);
        }



        $array['not_group'] = $res;
        if($is_current_year) {
            $array['group'] = $this->groupByMonth($res,$current_month);
        }else{
            $array['group'] = $this->groupByMonth($res,1);
        }


        return $array;

    }


    private function groupByMonth($collection,$current_month){

        $data = [];

        for($i=$current_month;$i<=12;$i++){

            $data[$i] = new \stdClass();
            $data[$i]->month = $i;
            $data[$i]->res = [];

        }

        foreach($collection as $el){

            $date_pub = date_parse($el->published_at);

            foreach($data as $mn_nr=>$item){

                if($date_pub['month']==$mn_nr){
                    array_push($data[$mn_nr]->res,$el);
                }

            }



        }


        return array_values($data);
    }



    public function getFilterResultsDataNoYearAndCategories($filters, $current_month, $year, $current_day, $is_current_year, $is_all, $all_filters, $order){


        $array = [];

        $res = [];


        $query = DB::table('contents');
        $query->join('categorygables', 'contents.id', '=', 'categorygables.categorygables_id');
        $query->where('categorygables.categorygables_type','App\Entities\Content');


        $query->where(function($q) use($filters,$is_all,$all_filters) {

            if(!$is_all) {

                foreach ($filters as $key => $filter) {

                    if ($key == 0) {
                        $q->where('categorygables.category_id', $filter['id']);
                    } else {
                        $q->orWhere('categorygables.category_id', $filter['id']);
                    }

                }

            }else{

                foreach ($all_filters as $key => $filter) {

                    if ($key == 0) {
                        $q->where('categorygables.category_id', $filter['id']);
                    } else {
                        $q->orWhere('categorygables.category_id', $filter['id']);
                    }

                }

            }

        });



        $order_type='published_at';



        foreach($query->where('status',1)->orderBy($order_type,$order)->get()->unique('id') as $key=>$el){
//            $el->categories = $this->find($el->id)->categories()->get();
            $el->intro = strip_tags($el->intro);
            $el->content = strip_tags($el->content);
            $el->categories = Cache::get('content:categories:'.$el->id);
            $el->params = \GuzzleHttp\json_decode($el->params,true);
            array_push($res, $el);
        }



        $array['not_group'] = $res;
        if($is_current_year) {
            $array['group'] = $this->groupByMonth($res,$current_month);
        }else{
            $array['group'] = $this->groupByMonth($res,1);
        }

        DshHelper::addToFile("zobaczto.json",json_encode($array));
        return $array;

    }


    public function getFilterResultsDataStaticAndCategories($all_filters, $order){

        $array = [];

        $res = [];


        $query = DB::table('contents');
        $query->join('categorygables', 'contents.id', '=', 'categorygables.categorygables_id');
        $query->where('categorygables.categorygables_type','App\Entities\Content');


        $query->where(function($q) use($all_filters) {


            foreach ($all_filters as $key => $filter) {

                if ($key == 0) {
                    $q->where('categorygables.category_id', $filter['id']);
                } else {
                    $q->orWhere('categorygables.category_id', $filter['id']);
                }

            }


        });

        $order_type='published_at';

        foreach($query->where('status',1)->orderBy($order_type,$order)->get()->unique('id') as $key=>$el){
            $el->intro = strip_tags($el->intro);
            $el->content = strip_tags($el->content);
            $el->categories = Cache::get('content:categories:'.$el->id);
            $el->params = \GuzzleHttp\json_decode($el->params,true);
            array_push($res, $el);
        }


        $array['not_group'] = $res;


        return $array;

    }


    public function getFilterResultsDataYearAndCategoriesArchive($filters, $current_month, $year, $is_all, $all_filters){


        $array = [];

        $res = [];


        $query = DB::table('contents');
        $query->join('categorygables', 'contents.id', '=', 'categorygables.categorygables_id');
        $query->where('categorygables.categorygables_type','App\Entities\Content');


        $query->where(function($q) use($filters,$is_all,$all_filters) {

            if(!$is_all) {

                foreach ($filters as $key => $filter) {

                    if ($key == 0) {
                        $q->where('categorygables.category_id', $filter['id']);
                    } else {
                        $q->orWhere('categorygables.category_id', $filter['id']);
                    }

                }

            }else{

                foreach ($all_filters as $key => $filter) {

                    if ($key == 0) {
                        $q->where('categorygables.category_id', $filter['id']);
                    } else {
                        $q->orWhere('categorygables.category_id', $filter['id']);
                    }

                }

            }

        });


        $begin_year = Carbon::create($year, 1, 1, 0, 0, 0)->toDateTimeString();
        $end_year = Carbon::create($year, 12, 31, 0, 0, 0)->toDateTimeString();



        $order_type='published_at';



        foreach($query->where('archived',1)->whereDate('published_at', '>=', $begin_year)->whereDate('published_at', '<=', $end_year)->orderBy($order_type,'asc')->get()->unique('id') as $key=>$el){
            $el->intro = strip_tags($el->intro);
            $el->content = strip_tags($el->content);
            $el->params = \GuzzleHttp\json_decode($el->params,true);
            array_push($res, $el);
        }


        return $res;


    }


}
