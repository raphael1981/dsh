<?php

namespace ResourceRecursion\MediaRecursion;



use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\LaravelLocalization;

class MediaRecursion{

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


    public static function getResourceFolderTreeLevel($disk){

        $list=['folders'=>[]];
        $folders=[''];
        $list['folders'] = self::listFileGetLevel($disk, $folders, []);
        return $list;

    }



    private static function listFileGetLevel($disk,  $folders, $list, $path='', $level=0, $object_path=''){

        $level++;

        foreach($folders as $k=>$folder) {

            $files = Storage::disk($disk)->files($folder);
            $dirs = Storage::disk($disk)->directories($folder);


            if($folders==''){
                $path = '/';
            }else{
                $path = '/'.$folder;
                if($k==0) {
                    $object_path = $object_path . ':' . $k;
                }else{
                    $ex = explode(':',$object_path);
                    $path_f = '';
                    for($i=0;$i<(count($ex)-1);$i++){
                        if($i==0){
                            $path_f .= $ex[$i];
                        }else{
                            $path_f .= ':'.$ex[$i];
                        }
                    }
                    $object_path = $path_f.':'.$k;

                }
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
                'level'=>$level,
                'files' => self::refactorFilesArrayLevel($files,$object_path),
                'folders'=>[]
            ];



            $list[$k]['folders'] = self::listFileGetLevel($disk, $dirs, $list[$k]['folders'], $path,$level,$object_path);

        }


        return $list;

    }

    private static function refactorFilesArrayLevel($files,$object_path){

        $array = [];

        foreach($files as $key=>$file){

            $array[$key] = new \stdClass();
            $array[$key]->name = $file;
            $array[$key]->object_path = $object_path.':files:'.$key;

        }

        return $array;

    }


    private static function refactorFilesArray($files){

        $array = [];

        foreach($files as $key=>$file){

            $array[$key] = new \stdClass();
            $array[$key]->name = $file;

        }

        return $array;

    }


    public static function getGetFolderMedia($disk, $data){

        $array = [];

        foreach(Storage::disk($disk)->files($data['path']) as $key=>$file){

            $std = new \stdClass();
            $std->file = '/'.$file;
            $std->name = self::getFileName($file);
            $std->basename = '';
            $std->disk = $disk;
            $std->path = storage_path('app/'.$disk.'/'.$std->file);
            $std->info = self::getFileInfo($file);
            $std->icon = self::getIcon($std->info->mimetype);


            array_push($array, $std);

        }

        return $array;

    }


    private static function getFileName($pname){

        $only_name = '';

        $ex = explode('/',$pname);

        return end($ex);

    }


    private static function getIcon($mime){

        $icon_name = null;

        foreach(config('services')['mimeicons'] as $key=>$icon){

            foreach($icon['mimelist'] as $k=>$mt){

                if($mt==$mime){
                    $icon_name=config('services')['mimeicons'][$key]['icon'];
                }

            }

        }

        if(is_null($icon_name)){
            $icon_name = config('services')['mimeicons'][0]['icon'];
        }

        return $icon_name;

    }


    private static function getFileInfo($file){

        $std = new \stdClass();

        $fname_array = explode('.', $file);
        $suffix = str_replace('.','',end($fname_array));
        array_pop($fname_array);

        $name = '';

        foreach($fname_array as $k=>$f){
            $name .= $f;
        }


        $std->suffix = $suffix;
        $std->name = $name;
        $std->mimetype = mime_content_type(storage_path().'/app/media/'.$file);

        return $std;


    }


}