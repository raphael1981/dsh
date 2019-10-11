<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\MemberRepository;
use App\Entities\Member;
use App\Validators\MemberValidator;

/**
 * Class MemberRepositoryEloquent
 * @package namespace App\Repositories;
 */
class MemberRepositoryEloquent extends BaseRepository implements MemberRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Member::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function searchByCriteria($data){


        $query = Member::where(function($q) use ($data){

            foreach($data['searchcolumns'] as $name=>$boolean){
                if($boolean){
                    $q->orWhere($name, 'LIKE', '%' . $data['frase'] . '%');
                }
            }

        });


//        foreach($data['filter'] as $name=>$filter) {
//
//            if(!is_null($filter['value'])){
//                $query
//                    ->where($name, $filter['value']);
//            }
//
//        }

        $query->orderBy('created_at', 'DESC');


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
