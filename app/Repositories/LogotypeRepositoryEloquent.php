<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\LogotypeRepository;
use App\Entities\Logotype;
use App\Validators\LogotypeValidator;

/**
 * Class LogotypeRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class LogotypeRepositoryEloquent extends BaseRepository implements LogotypeRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Logotype::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    public function searchByCriteria($data){

        $query = Logotype::where(function($q) use ($data){

            foreach($data['searchcolumns'] as $name=>$boolean){
                if($boolean){
                    $q->orWhere($name, 'LIKE', '%' . $data['frase'] . '%');
                }
            }

        });


        $query->where('language_id',$data['lang'])->orderBy('created_at', 'DESC');


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
    
}
