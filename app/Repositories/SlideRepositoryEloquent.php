<?php

namespace App\Repositories;

use Guzzle\Http\Message\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\SlideRepository;
use App\Entities\Slide;
use Illuminate\Support\Facades\DB;
use App\Validators\SlideValidator;

/**
 * Class SlideRepositoryEloquent
 * @package namespace App\Repositories;
 */
class SlideRepositoryEloquent extends BaseRepository implements SlideRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Slide::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    public function setNewOrder($data){
        $iter = 1;
        foreach($data as $key=>$item){
            $query = "UPDATE slides SET ord='".$iter."' where id='".$item['id']."'";
            DB::update($query);
            $iter++;
        }

    }

    public function setActive($data){
        $query = "UPDATE slides SET status='".$data['status']."' where id='".$data['id']."'";
        DB::update($query);
    }

    public function remove($data){
        $query = "DELETE FROM slides WHERE id='".$data."'";
        DB::update($query);
    }

    public function getById($id){
        $slid = Slide::find($id);
        return $slid;
    }

    public function updateImage($id,$imageName){
        $query = "UPDATE slides SET image='".$imageName."' where id='".$id."'";
        DB::update($query);
    }

    public function setColor($id,$color){
        $query = "UPDATE slides SET color='".$color."' where id='".$id."'";
        DB::update($query);
    }

    public function changeText($id,$field,$data){
        $query = "UPDATE slides SET ".$field."='".$data."' where id='".$id."'";
        DB::update($query);
    }
}
