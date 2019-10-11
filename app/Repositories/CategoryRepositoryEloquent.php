<?php

namespace App\Repositories;

use App\Entities\Language;
use App\Entities\Link;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\CategoryRepository;
use App\Entities\Category;
use App\Validators\CategoryValidator;

/**
 * Class CategoryRepositoryEloquent
 * @package namespace App\Repositories;
 */
class CategoryRepositoryEloquent extends BaseRepository implements CategoryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Category::class;
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

        $query = Category::where(function($q) use ($data){

            foreach($data['searchcolumns'] as $name=>$boolean){
                if($boolean){
                    $q->orWhere($name, 'LIKE', '%' . $data['frase'] . '%');
                }
            }

        });



        $query
            ->where('language_id', $lang->id);

        $total = $query->count();


        $elements = $query
            ->skip($data['start'])
            ->take($data['limit'])
            ->get();


        $std = new \stdClass();
        $std->elements = $elements;
        $std->count = $total;

        return \GuzzleHttp\json_encode($std);

    }


    public function getCategoriesAndLinksRelationships(){

        $array = [];

        foreach(\App\Entities\Category::all() as $k=>$c){

            $std = new \stdClass();
            $std->name = $c->name;
            $std->id = $c->id;
            $std->relations = $this->createHtmlLinksRelView($this->findCategoryLinks($c->id));

            array_push($array, $std);

        }

        return $array;
    }


    private function createHtmlLinksRelView($collection){
        return $collection;
    }


    private function findCategoryLinks($id){

        $links_exist = [];

        $links = Link::where('language_id', 2)->
                    where(function($q){

                        $q->where('template_id',5);
                        $q->orWhere('template_id',7);

                    })
                    ->get();


        foreach($links as $k=>$l){


            foreach(\GuzzleHttp\json_decode($l->params)->filter as $fid){

                if($id==$fid->id){
                    array_push($links_exist, $l);
                }

            }

        }

        return $links_exist;

    }

}
