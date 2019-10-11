<?php

namespace ResourceRecursion\PictureRecursion;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image as InterImage;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PictureRecursion{


    public static function getResourceFolderTree($disk){

        $list=['folders'=>[]];
        $folders=[''];
        $list['folders'] = self::listFileGet($disk, $folders, []);
        return $list;

    }



    private static function listFileGet($disk,  $folders, $list, $path=''){

        foreach($folders as $k=>$folder) {

            $files = Storage::disk($disk)->files($folder);
            $dirs = Storage::disk($disk)->directories($folder);

           if($folders==''){
               $path = '/';
           }else{
               $path = '/'.$folder;
           }

            $pex = [];
            $lk=0;

            if($folder!=''){
                $pex = explode('/',$folder);
                $lk = count($pex)-1;
            }

            $list[$k] = [
                'name'=>($folder=='')?$disk:$pex[$lk],
                'path'=>$path,
                'files' => self::refactorFilesArray($files),
//                'files'=>[],
                'folders'=>[]
            ];

            $list[$k]['folders'] = self::listFileGet($disk, $dirs, $list[$k]['folders'], $path);

        }


        return $list;

    }

    private static function refactorFilesArray($files){

        $array = [];

        foreach($files as $key=>$file){

            $array[$key] = new \stdClass();
            $array[$key]->name = $file;

        }

        return $array;

    }



    public static function getGetFolderImages($disk, $data){

        $langs = LaravelLocalization::getSupportedLocales();

        $lstd = new \stdClass();

        foreach($langs as $key=>$value){
            $lstd->{$key} = '';
        }

        $ex_path = explode('/',$data['path']);

        $array = [];

        $urlpath = null;

        if($ex_path[0]=='' && $ex_path[1]==''){
            $urlpath = ':';
        }else{

            $urlpath = '';
            for($i=1;$i<count($ex_path);$i++){
                $urlpath .= ':'.$ex_path[$i];
            }

        }

        foreach(Storage::disk($disk)->files($data['path']) as $key=>$file){
            $std = new \stdClass();
            $std->file = $file;
            if($data['path']=="/"){
                $std->filename = str_replace(substr($data['path'],1), '', $file);
            }else{
                $std->filename = substr( str_replace(substr($data['path'],1), '', $file), 1 );
            }

            $std->disk = $disk;
            $std->path = storage_path('app/'.$disk.'/'.$std->file);
            $std->size = getimagesize($std->path);
            $std->request = '/image/'.$std->filename.'/'.$disk.'/'.$urlpath.'/200';
            $std->request_uncomplete = '/image/'.$std->filename.'/'.$disk.'/'.$urlpath.'/';
            $std->desc = $lstd;
            $std->base = false;
            array_push($array, $std);
        }

        return \GuzzleHttp\json_encode($array);

    }


    public static function getImageInfo($disk, $data){

        $array = [];

        $bc = $data['breadcrumbs'];
        $imgpath = $bc[count($bc)-1];



        $array['all'] = $data;
        $array['name'] = $data['name'];


        return $array;

    }


}