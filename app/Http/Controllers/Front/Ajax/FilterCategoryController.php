<?php

namespace App\Http\Controllers\Front\Ajax;

use App\Entities\Link;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Repositories;

class FilterCategoryController extends Controller
{

    private $agenda;
    private $content;

    public function __construct(Repositories\AgendaRepositoryEloquent $agenda, Repositories\ContentRepositoryEloquent $content)
    {
        $this->agenda = $agenda;
        $this->content = $content;
    }


    public function getLinkFilters($prefix, $linkid){

        $params = \GuzzleHttp\json_decode(Link::find($linkid)->params);
        $filters = [];

        foreach($params->filter as $k=>$f){

            $ent = $f->entity;
            $fil = $ent::find($f->id);
            array_push($filters,$fil);

        }

        return $filters;

    }

    public function getFilterResults($prefix, Request $request){

//        return $request->get('year');
//        return $this->agenda->getFilterResultsData($request->get('filters'), $request->get('year'), $request->get('month'), $request->get('is_all'));
        return $this->agenda->getFilterResultsDataYearAndCategories($request->get('filters'),
                                                                    $request->get('current_month'),
                                                                    $request->get('year'),
                                                                    $request->get('current_day'),
                                                                    $request->get('is_current_year'),
                                                                    $request->get('is_all'),
                                                                    $request->get('full_filter_collection'),
                                                                    $request->get('order'));

    }


    public function getFilterContentResults($prefix, Request $request){

//        return $request->all();
//        return $this->agenda->getFilterResultsData($request->get('filters'), $request->get('year'), $request->get('month'), $request->get('is_all'));
        return $this->content->getFilterResultsDataYearAndCategories($request->get('filters'),
            $request->get('current_month'),
            $request->get('year'),
            $request->get('current_day'),
            $request->get('is_current_year'),
            $request->get('is_all'),
            $request->get('full_filter_collection'),
            $request->get('order'));

    }


    public function getFilterContentResultsNoYear($prefix, Request $request){

//        return $request->all();
//        return $this->agenda->getFilterResultsData($request->get('filters'), $request->get('year'), $request->get('month'), $request->get('is_all'));
        return $this->content->getFilterResultsDataNoYearAndCategories($request->get('filters'),
            $request->get('current_month'),
            $request->get('year'),
            $request->get('current_day'),
            $request->get('is_current_year'),
            $request->get('is_all'),
            $request->get('full_filter_collection'),
            $request->get('order'));

    }

    public function getFilterContentResultsStatic($prefix, Request $request){


        return $this->content->getFilterResultsDataStaticAndCategories(
            $request->get('full_filter_collection'),
            $request->get('order'));

    }


    public function getFilterAdvancedResults(Request $request){

        return $this->agenda->getFilterAdvancedResult($request->get('filters'), $request->get('current_month'), $request->get('year'), $request->get('is_all'), $request->get('all_filters'));

    }


    public function getFilterContentArchiveResults(Request $request){

        return $this->content->getFilterResultsDataYearAndCategoriesArchive($request->get('filters'), $request->get('current_month'), $request->get('year'),$request->get('is_all'),$request->get('full_filter_collection'));
    }


}
