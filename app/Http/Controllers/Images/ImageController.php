<?php

namespace App\Http\Controllers\Images;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterImage;
use CSD\Image\Image as ImageCSD;

class ImageController extends Controller
{
    public function imageRender($filename, $disk, $stringpath=':', $basesize=null, $format=null){


        $patharray = explode(':', $stringpath);

        $path = '';

        if($patharray[0]=='' && $patharray[1]==''){

            $path = '';

        }else{

            for($i=1;$i<count($patharray);$i++){
                $path .= $patharray[$i].'/';
            }

        }


        $ctype = Storage::disk($disk)->mimeType($path.$filename);


        if(!is_null($basesize)) {

            if(!is_null($format) && $format == 'panoram') {
                $img = InterImage::make(storage_path() . '/app/' . $disk . '/' . $path . $filename)
                    ->resize(null, $basesize, function ($constraint) {
                        $constraint->aspectRatio();
                    });
            }else {
                $img = InterImage::make(storage_path() . '/app/' . $disk . '/' . $path . $filename)
                    ->resize($basesize, null ,function ($constraint) {
                        $constraint->aspectRatio();
                    });
            }

            return $img->response($ctype)
                ->header('Content-Type',  $ctype);



        }else{


            $img = InterImage::make(storage_path().'/app/'.$disk.'/'.$path.$filename);

            return $img->response($ctype)
                ->header('Content-Type',  $ctype);

        }


    }

    public function picFromRoot($filename, $disk,  $basesize=null){
        $ctype = Storage::disk($disk)->mimeType($filename);

        if(!is_null($basesize)) {
            $img = InterImage::make(storage_path().'/app/'.$disk.'/'.$filename)
                ->resize($basesize, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            return $img->response($ctype)
                ->header('Content-Type',  $ctype);
        }
        else{
            $img = InterImage::make(storage_path().'/app/'.$disk.'/'.$filename);
            return $img->response($ctype)
                ->header('Content-Type',  $ctype);

        }

    }

}
