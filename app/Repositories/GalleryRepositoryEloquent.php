<?php

namespace App\Repositories;

use App\Entities\Picture;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\GalleryRepository;
use App\Entities\Gallery;
use App\Validators\GalleryValidator;

/**
 * Class GalleryRepositoryEloquent
 * @package namespace App\Repositories;
 */
class GalleryRepositoryEloquent extends BaseRepository implements GalleryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Gallery::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }



    public function searchByCriteria($data){


        $query = Gallery::where(function($q) use ($data){

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


        $total = $query->count();


        $elements = $query
            ->orderBy('updated_at','desc')
            ->skip($data['start'])
            ->take($data['limit'])
            ->get();


        $std = new \stdClass();
        $std->elements = $elements;
        $std->count = $total;

        return \GuzzleHttp\json_encode($std);

    }



    public function getGalleryWithPicturesByIdFastView($id){

        $std = new \stdClass();

        $g = $this->find($id);

        $std->gallery = $g;

        $std->pictures = [];

        $pictures = $g->pictures()->orderBy('ord','desc')->get();

        foreach($pictures as $p){


            array_push(
                $std->pictures,
                [
                    'disk'=>$p->disk,
                    'name'=>$p->image_name,
                    'path'=>$p->image_path,
                    'request'=>'/image/'.$p->image_name.'/'.$p->disk.'/'.$p->image_path.'/120'
                ]
            );

        }

//        foreach(\GuzzleHttp\json_decode($g->collection) as $pid){
//
//            $p = Picture::find($pid);
//
//            array_push(
//                $std->pictures,
//                [
//                    'disk'=>$p->disk,
//                    'name'=>$p->image_name,
//                    'path'=>$p->image_path,
//                    'request'=>'/image/'.$p->image_name.'/'.$p->disk.'/'.$p->image_path.'/120'
//                ]
//                );
//
//        }


        return \GuzzleHttp\json_encode($std);


    }

    public function getGalleryFullData($id){

        $pics = [];

        $gallery = $this->find($id);

        foreach($gallery->pictures()->orderBy('ord','asc')->get() as $key=>$pic){

            if($pic->image_path==''){
                $pic->image_path = ':';
            }

            $std = new \stdClass();
            $std->id = $pic->id;
            $std->disk = $pic->disk;
            $std->file = $pic->image_name;
            $std->filename = $pic->image_name;
            $std->path = $pic->image_path;
            $std->request = '/image/'.$pic->image_name.'/'.$pic->disk.'/'.$pic->image_path.'/200';
            $std->request_uncomplete = '/image/'.$pic->image_name.'/'.$pic->disk.'/'.$pic->image_path.'/';
            $std->size = '';
            $std->desc = \GuzzleHttp\json_decode($pic->translations);
            $std->base = true;

            array_push($pics, $std);

        }

        $fulldata = new \stdClass();

        $fulldata->gallery = $gallery;
        $fulldata->pictures = $pics;

        return $fulldata;

    }

}
