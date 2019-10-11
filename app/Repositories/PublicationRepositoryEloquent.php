<?php

namespace App\Repositories;

use App\Entities\Language;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PublicationRepository;
use App\Entities\Publication;
use App\Validators\PublicationValidator;

/**
 * Class PublicationRepositoryEloquent
 * @package namespace App\Repositories;
 */
class PublicationRepositoryEloquent extends BaseRepository implements PublicationRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Publication::class;
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

        $query = Publication::where(function($q) use ($data){

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


    public function getFullPublicationData($id){

        $p = $this->find($id);

        if(!is_null($p->suffix) && !is_null($p->params)) {
            $prstd = new \stdClass();
            $prstd->suffix = $p->suffix;
            $json_params = \GuzzleHttp\json_decode($p->params);

            $prstd->name = $json_params->name;
            $prstd->icon = $json_params->icon;
            $prstd->color = $json_params->color;

            $p->view_profile = $prstd;
        }else{
            $p->view_profile = null;
        }

        $p->attachments = $p->medias()->get();

        return \GuzzleHttp\json_encode($p);

    }

}
