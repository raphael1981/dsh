<?php

namespace App\Repositories;

use App\Entities\Language;
use App\Entities\Link;
use App\Entities\Place;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\AgendaRepository;
use App\Entities\Agenda;
use App\Validators\AgendaValidator;

/**
 * Class AgendaRepositoryEloquent
 * @package namespace App\Repositories;
 */
class AgendaRepositoryEloquent extends BaseRepository implements AgendaRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Agenda::class;
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

        $query = Agenda::where(function($q) use ($data){

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


    public function getFullAgendaData($id){

        $std = new \stdClass();

        $ag = $this->find($id);

        //$ag = Agenda::where('id', '=', $id)->where('status','=',1)->first();


        $std->id = $id;
        $std->title = $ag->title;
        $std->intro = $ag->intro;
        $std->content = $ag->content;
        $std->begin = $ag->begin;
        $std->end = $ag->end;
        $std->begin_time = $ag->begin_time;
        $std->end_time = $ag->end_time;
        $std->place_id = $ag->place_id;
        $std->params = $ag->params;
        $std->place = Place::find($ag->place_id);
        $std->logotypes = $ag->logotypes()->get();

        foreach($std->logotypes as $k=>$l){
            $std->logotypes[$k]->logotypes = \GuzzleHttp\json_decode($l->logotypes);
        }

        if(!is_null($ag->suffix) && !is_null($ag->params)) {
            $prstd = new \stdClass();
            $prstd->suffix = $ag->suffix;
            $json_params = \GuzzleHttp\json_decode($ag->params);

            $prstd->name = $json_params->name;
            $prstd->icon = $json_params->icon;
            $prstd->color = $json_params->color;

            $std->view_profile = $prstd;
        }else{
            $std->view_profile = null;
        }

        if($ag->image!=''){
            $std->intro_image = new \stdClass();
            $std->intro_image->file = $ag->image;
            $std->intro_image->filename = $ag->image;
            $std->intro_image->disk = $ag->disk;
            $std->intro_image->file = $ag->image;

            if($ag->image_path==':'){

                $std->path = storage_path().'/app/'.$ag->disk.'/'.$ag->image;

            }else{

                $ex = explode(':',$ag->image_path);

                $subpath = '';

                for($i=1;$i<count($ex);$i++){
                    $subpath .= '/'.$ex[$i];
                }

                $std->intro_image->path = storage_path().'/app/'.$ag->disk.$subpath.'/'.$ag->image;

            }

            $std->intro_image->request = '/image/'.$ag->image.'/'.$ag->disk.'/'.$ag->image_path.'/200';
            $std->intro_image->request_uncomplete = '/image/'.$ag->image.'/'.$ag->disk.'/'.$ag->image_path.'/';

        }else{
            $std->intro_image = null;
        }

        $std->galleries = $ag->galleries()->get();
        $std->categories = $ag->categories()->get();
        $std->attachments = $ag->medias()->get();

        return \GuzzleHttp\json_encode($std);

    }

    public function fastSearchByTitle($phrase){

        $default_color = config('services')['default_template_color'];

        $data = [];

        $ags = Agenda::where('title', 'LIKE', '%'.$phrase.'%')->orderBy('created_at','desc')->get();

        foreach($ags as $k=>$ag){

            $ags[$k]->intro = strip_tags($ags[$k]->intro);
            $ags[$k]->categories = Agenda::find($ag->id)->categories()->get();
            $ags[$k]->language = Language::find($ag->language_id);
            $ags[$k]->params = \GuzzleHttp\json_decode($ags[$k]->params);


        }

        return $ags;

    }


//    private function getLinksWhereFilterCurrentCategory($category_id){
//
//        $std = new \stdClass();
//        $std->color = null;
//
//        $template_id = 5;
//        foreach(Link::where('template_id',$template_id)->get() as $key=>$link){
//
//            $json = \GuzzleHttp\json_decode($link->params);
//            foreach($json->filter as $k=>$f){
//                if($category_id==$f->id){
//                    $std->color = $json->color;
//                }
//            }
//
//        }
//
//        return $std->color;
//
//    }


    public function getFilterResultsData($filters, $year, $month, $is_all){

        $res = [];



        $query = DB::table('agendas');
        if(!$is_all) {
            $query->join('categorygables', 'agendas.id', '=', 'categorygables.categorygables_id');
            $query->where('categorygables.categorygables_type','App\Entities\Agenda');
        }

        if(!$is_all) {

            $query->where(function($q) use($filters) {

                foreach ($filters as $key => $filter) {

                    if ($key == 0) {
                        $q->where('categorygables.category_id', $filter['id']);
                    } else {
                        $q->orWhere('categorygables.category_id', $filter['id']);
                    }

                }


            });

        }

        $begin_year =  Carbon::createFromDate($year, $month, 1)->toDateString();
        $end_year = Carbon::createFromDate($year, $month, 31)->toDateString();



        foreach($query->where('status',1)->whereDate('begin', '>=', $begin_year)->whereDate('begin', '<=', $end_year)->orderBy('begin','asc')->orderBy('begin_time','asc')->get() as $key=>$el){
            $el->categories = $this->find($el->id)->categories()->get();
            array_push($res, $el);
        }

        return $res;

    }


    public function getFilterResultsDataYearAndCategories($filters, $current_month, $year, $current_day, $is_current_year, $is_all, $all_filters, $order){

        $array = [];

        $res = [];


        $query = DB::table('agendas');
        $query->join('categorygables', 'agendas.id', '=', 'categorygables.categorygables_id');
        $query->where('categorygables.categorygables_type','App\Entities\Agenda');


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


        if($is_current_year && $order=='asc') {

            $begin_year = Carbon::createFromDate($year, $current_month, $current_day)->toDateString();
            $end_year = Carbon::createFromDate($year, 12, 31)->toDateString();


        }else{

            $begin_year = Carbon::createFromDate($year, 1, 1)->toDateString();
            $end_year = Carbon::createFromDate($year, 12, 31)->toDateString();
        }

        if($order=='asc'){
            $order_type='begin';
        }else{
            $order_type='end';

        }



        foreach($query->where('status',1)->whereDate('end', '>=', $begin_year)->whereDate('begin', '<=', $end_year)->orderBy($order_type,$order)->orderBy('begin_time','asc')->get()->unique('id') as $key=>$el){
//            $el->categories = $this->find($el->id)->categories()->get();
            $el->intro = strip_tags($el->intro);
            $el->content = strip_tags($el->content);
            $el->categories = Cache::get('agenda:categories:'.$el->id);
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

            $date_begin = date_parse($el->begin);
            $date_end = date_parse($el->end);

            foreach($data as $mn_nr=>$item){


                if(!is_null($date_end)){

                    if($date_begin==$date_end){


                        if($date_begin['month']==$mn_nr) {

                            array_push($data[$mn_nr]->res, $el);

                        }

                    }else{

                        if($date_begin['month']<=$mn_nr && $mn_nr<=$date_end['month']){

                            array_push($data[$mn_nr]->res,$el);

                        }

                    }

                }elseif(is_null($date_end)){

                    if($date_begin['month']==$mn_nr) {

                        array_push($data[$mn_nr]->res, $el);

                    }

                }

//                if($date_begin['month']==$mn_nr){
//
//                    array_push($data[$mn_nr]->res,$el);
//
//                }elseif($mn_nr<=$date_end['month']){
//
//                    array_push($data[$mn_nr]->res,$el);
//
//                }

            }



        }


        return array_values($data);
    }


    public function getFilterAdvancedResult($filters, $current_month, $current_year, $is_all, $all_filters){

        $array = [];

        $res = [];


        $query = DB::table('agendas');
        $query->join('categorygables', 'agendas.id', '=', 'categorygables.categorygables_id');
        $query->where('categorygables.categorygables_type','App\Entities\Agenda');


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

        $begin_month_year = $this->getFirstMonthYear($current_month, $current_year);
        $end_month_year = $this->getLastMonthYear($current_month, $current_year);




        foreach(

                $query->where(function($pq) use ($begin_month_year,$end_month_year){

                    $pq->where(function($q) use ($begin_month_year,$end_month_year){
                        $q->whereDate('begin', '>=', $begin_month_year);
                        $q->whereDate('begin', '<=', $end_month_year);
                    });
                    $pq->orWhere(function($q) use ($begin_month_year,$end_month_year){
                            $q->whereDate('begin', '<=', $begin_month_year);
                            $q->whereDate('end', '>=', $begin_month_year);
                        });

                })
                    ->where('status',1)

                    ->orderBy('begin','asc')
                    ->orderBy('begin_time','asc')
                    ->get()
                    ->unique('id')

                as $key=>$el){
//            $el->categories = $this->find($el->id)->categories()->get();
            $el->intro = strip_tags($el->intro);
            $el->content = strip_tags($el->content);
            $el->categories = Cache::get('agenda:categories:'.$el->id);
            $el->params = \GuzzleHttp\json_decode($el->params,true);
            array_push($res, $el);
        }

        return $res;

    }


    public function getFirstMonthYear($m,$y){

        $c = Carbon::createFromDate($y,$m,1);

        return $c->toDateString();

    }


    public function getLastMonthYear($m,$y){

        $last_day = 31;

        $days = [
                1=>'more',
                2=>null,
                3=>'more',
                4=>'less',
                5=>'more',
                6=>'less',
                7=>'more',
                8=>'more',
                9=>'less',
                10=>'more',
                11=>'less',
                12=>'more'
                ];

        if(!is_null($days[$m])){

            if($days[$m]=='more'){
                $last_day = 31;
            }

            if($days[$m]=='less'){
                $last_day = 30;
            }

        }else{

            if((($y % 4 == 0) && ($y % 100 != 0)) || ($y % 400 == 0)){
                $last_day = 29;
            }else{
                $last_day = 28;
            }

        }


        $c = Carbon::createFromDate($y,$m,$last_day);

        return $c->toDateString();

    }





}
