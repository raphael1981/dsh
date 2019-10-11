<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PictureRepository;
use App\Entities\Picture;
use App\Validators\PictureValidator;

/**
 * Class PictureRepositoryEloquent
 * @package namespace App\Repositories;
 */
class PictureRepositoryEloquent extends BaseRepository implements PictureRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Picture::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    public function searchByCriteriaFastFullData($data){


        $query = Picture::where(function($q) use ($data){

            foreach($data['searchcolumns'] as $name=>$boolean){
                if($boolean){
                    $q->orWhere($name, 'LIKE', '%' . $data['frase'] . '%');
                }
            }

        });


        $elements = $query->get();



        return $this->refactorPicturesToResponse($elements);

    }


    private function refactorPicturesToResponse($elements){

        $collection = [];

        foreach($elements as $key=>$pic){

            $image = new \stdClass();
            $image->id = $pic->id;
            $image->disk = $pic->disk;
            $image->file = $pic->image_name;
            $image->filename = $pic->image_name;
            $image->path = self::makeRootPathFromImagePath($pic->image_path, $pic->disk).$pic->image_path;
            $image->request = '/image/'.$pic->image_name.'/'.$pic->disk.'/'.$pic->image_path.'/200';
            $image->request_uncomplete = '/image/'.$pic->image_name.'/'.$pic->disk.'/'.$pic->image_path.'/';
            $image->size = '';
            $image->desc = $pic->translations;
            $image->base = true;

            array_push($collection, $image);

        }



        return $collection;

    }


    public static function makeRootPathFromImagePath($im_path, $disk){

        $root_path = '';

        if($im_path==':'){
            $root_path = storage_path().'/app/'.$disk;
        }else{

            $piece_path = '';
            $ex = explode(':', $im_path);
            for($i=1;$i<count($ex);$i++){
                $piece_path .= '/'.$ex;
            }

            $root_path = storage_path().'/app/'.$disk.$piece_path.'/';

        }

        return ':';

    }

}
