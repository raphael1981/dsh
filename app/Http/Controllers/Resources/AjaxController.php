<?php

namespace App\Http\Controllers\Resources;

use App\Entities\Language;
use App\Entities\Media;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterImage;
use CSD\Image\Image as ImageCSD;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class AjaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function uploadImages(Request $request){

        $langs = LaravelLocalization::getSupportedLocales();

        $lstd = new \stdClass();

        foreach($langs as $key=>$value){
            $lstd->{$key} = '';
        }

        $stringpath = '';

        if($request->get('disk')==substr($request->get('upload_dir'), 0, -1)){

            $stringpath = ':';

        }else{


            $pext = explode('/',$request->get('upload_dir'));

            for($i=1;$i<count($pext);$i++){
                $stringpath .= ':'.$pext[$i];
            }

        }


        $std = new \stdClass();

        $image = $request->file('file');

        $temp = explode('.',$image->getClientOriginalName());
        $ext = $temp[count($temp)-1];
        $newfilename = (microtime(true)*10000).$temp[0].'.'.strtolower($ext);
        $image->move(storage_path().'/app/'.$request->get('upload_dir'), $newfilename);


        $std->disk = $request->get('disk');
        $std->file = $newfilename;
        $std->filename = $newfilename;
        $std->path = storage_path().'/app/'.$request->get('upload_dir').$newfilename;
        $std->request = '/image/'.$newfilename.'/'.$request->get('disk').'/'.$stringpath.'/200';
        $std->request_uncomplete = '/image/'.$newfilename.'/'.$request->get('disk').'/'.$stringpath.'/';
        $std->size = getimagesize(storage_path().'/app/'.$request->get('upload_dir').$newfilename);
        $std->desc = $lstd;
        $std->base = false;

        return json_encode($std);

    }

    public function uploadImagesCropped(Request $request){

        $langs = LaravelLocalization::getSupportedLocales();

        $lstd = new \stdClass();

        foreach($langs as $key=>$value){
            $lstd->{$key} = '';
        }

        $stringpath = '';

        if($request->get('disk')==substr($request->get('upload_dir'), 0, -1)){

            $stringpath = ':';

        }else{


            $pext = explode('/',$request->get('upload_dir'));

            for($i=1;$i<count($pext);$i++){
                $stringpath .= ':'.$pext[$i];
            }

        }


        $std = new \stdClass();

        $image = $request->file('file');

        $blob = $request->get('file');
        $blobRE = '/^data:((\w+)\/(\w+));base64,(.*)$/';

        $newfilename = str_slug($request->get('filename'),'-');

        if (preg_match($blobRE, $blob, $m))
        {
            Storage::disk($request->get('disk'))->put($newfilename, base64_decode($m[4]));
        }

//        $temp = explode('.',$image->getClientOriginalName());
//        $ext = $temp[count($temp)-1];
//        $newfilename = (microtime(true)*10000).$temp[0].'.'.strtolower($ext);
//        $image->move(storage_path().'/app/'.$request->get('upload_dir'), $newfilename);


        $std->disk = $request->get('disk');
        $std->file = $newfilename;
        $std->filename = $newfilename;
        $std->path = storage_path().'/app/'.$request->get('upload_dir').$newfilename;
        $std->request = '/image/'.$newfilename.'/'.$request->get('disk').'/'.$stringpath.'/200';
        $std->request_uncomplete = '/image/'.$newfilename.'/'.$request->get('disk').'/'.$stringpath.'/';
        $std->size = getimagesize(storage_path().'/app/'.$request->get('upload_dir').$newfilename);
        $std->desc = $lstd;
        $std->base = false;

        return json_encode($std);

    }


    public function uploadFiles(Request $request){
        $file = $request->file('file');
        $temp = explode('.',$file->getClientOriginalName());

        $name = '';

        $suffix = end($temp);

        array_pop($temp);

        foreach($temp as $s){
            $name .= $s;
        }

        $name .= microtime(true)*10000;

        $file->move(storage_path().'/app/'.$request->get('disk'), $name.'.'.$suffix);

        $std = new \stdClass();
        $std->disk = $request->get('disk');
        $std->file = $name;
        $std->fullfile = $name.'.'.$suffix;
        $std->suffix = $suffix;
        $std->path = '/'.$std->fullfile;
        $std->mimetype = mime_content_type(storage_path().'/app/'.$request->get('disk').'/'.$std->fullfile);

        $id = Media::create([
                'title'=>$name,
                'filename'=>$name,
                'full_filename'=>$name.'.'.$std->suffix,
                'disk'=>$std->disk,
                'media_relative_path'=>$std->path,
                'mimetype'=>$std->mimetype,
                'suffix'=>$std->suffix,
                'params'=>'{}'
            ]);

        $std->id = $id;

        return \GuzzleHttp\json_encode($std);
    }


    public function makeDir(Request $request){

        $path = storage_path('app/'.$request->get('addpath')).$request->get('folder');

        mkdir($path);

        return $request->all();

    }

    public function uploadMediaFiles(Request $request){

        $file = $request->file('file');

//        $temp = explode('.',$file->getClientOriginalName());

        $file->move(storage_path().'/app/'.$request->get('disk').$request->get('upload_path'), $file->getClientOriginalName());

        return $request->all();

    }


    public function uploadProgramPdf(Request $request){


        $lang = Language::find($request->get('lang_id'));

        $file = $request->file('file');

        $ex = explode('.',$file->getClientOriginalName());

        $extension =  end($ex);

        if(!Storage::exists(storage_path().'/app/'. $request->get('disk').'/programy')){
            Storage::makeDirectory(storage_path().'/app/'. $request->get('disk').'/programy');
        }

        if(!Storage::exists(storage_path().'/app/'. $request->get('disk').'/programy/'.$request->get('year'))){
            Storage::makeDirectory(storage_path().'/app/'. $request->get('disk').'/programy/'.$request->get('year'));
        }

        if(!Storage::exists(storage_path().'/app/'. $request->get('disk').'/programy/'.$request->get('year').'/'.$request->get('month'))){
            Storage::makeDirectory(storage_path().'/app/'. $request->get('disk').'/programy/'.$request->get('year').'/'.$request->get('month'));
        }

        $file->move(storage_path().'/app/'. $request->get('disk').'/programy/'.$request->get('year').'/'.$request->get('month'), 'program-'.$lang->tag.'.'.$extension);


        return $file;



    }


    public function uploadLogotype(Request $request){


        $file = $request->file('file');
        $temp = explode('.',$file->getClientOriginalName());

        $name = '';

        $suffix = end($temp);

        foreach($temp as $k=>$s){

            if($k<(count($temp)-1)){
                $name .= $s;
            }

        }

        $name = str_slug($name,'-').str_slug(microtime(),'-');

        $file->move(storage_path().'/app/'.$request->get('disk'), $name.'.'.$suffix);

        $std = new \stdClass();
        $std->disk = $request->get('disk');
        $std->file = $name;
        $std->fullfile = $name.'.'.$suffix;
        $std->suffix = $suffix;
        $std->mimetype = mime_content_type(storage_path().'/app/'.$request->get('disk').'/'.$std->fullfile);
        $std->real_path = '/'.$request->get('disk').'/'.$std->fullfile;

        return json_encode($std);
    }


}
