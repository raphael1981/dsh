<?php

namespace App\Http\Controllers\Admin\Super;

use App\Entities\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Repositories;

class SlidesController extends Controller
{

    private $slide;
    private $lang_id;

    public function __construct(Repositories\SlideRepositoryEloquent $slide)
    {
        $this->middleware('auth');
        $this->slide = $slide;
    }

    public function index(){
        $language = [
            'name'=>LaravelLocalization::getCurrentLocaleName(),
            'tag'=>LaravelLocalization::getCurrentLocale(),
            'id'=>Language::where('tag',LaravelLocalization::getCurrentLocale())->first()->id
        ];


        $content = view('admin.slides.content');

        return view('admin.slidesmaster',
            [
                'content'=>$content,
                'controller'=>'admin/slides/slides.controller.js',
                'lang'=>$language,
                'languages'=>LaravelLocalization::getSupportedLocales()
            ]
        );
    }


    public function uploadImage(Request $request){
        $std = new \stdClass();
        $stringpath = '';
        if($request->get('disk')==substr($request->get('upload_dir'), 0, -1)){
            $stringpath = ':';
        }else{
            $pext = explode('/',$request->get('upload_dir'));
            for($i=1;$i<count($pext);$i++){
                $stringpath .= ':'.$pext[$i];
            }
        }


        $std->uncomplete_request = '/image/'.$request->get('filename').'/'.$request->get('disk').'/'.$stringpath.'/';
        $std->disk = $request->get('disk');
        $std->name = $request->get('filename');
        $std->path = $stringpath;



        $blob = $request->get('file');
        $blobRE = '/^data:((\w+)\/(\w+));base64,(.*)$/';


        if (preg_match($blobRE, $blob, $m))
        {
            Storage::disk($request->get('disk'))->put($request->get('filename'), base64_decode($m[4]));
           return response('{"success":true,"data":'.json_encode($std).'}',
                              200,
                              ['Content-type'=>'application/json']);

        }

    }

    public function addSlide(Request $request){
        $this->slide->create([
            'language_id'=>$request->get('lang'),
            'image'=>$request->get('image'),
            'title'=>$request->get('title'),
            'description'=>$request->get('description'),
            'url'=>$request->get('url'),
            'color'=>$request->get('color'),
            'ord'=>1,
            'status'=>1
        ]);
        return response($request->all());
    }

    public function setOrder(Request $request){
        $this->slide->setNewOrder($request->get('slides'),$this->lang_id);
        return $request->get('slides');
    }

    public function getAllSlides(Request $request){
        $this->lang_id = $request->header('lang');
        return json_encode($this->slide->orderBy('ord')
                                ->findWhere(['language_id'=>$this->lang_id]));
    }

    public function setSlideActive(Request $request){
        $this->slide->setActive($request->get('slid'));
        return $request->get('slid');
    }

    public function removeSlide(Request $request){
        $slide = json_decode($request->get('slide'),true);
        $this->slide->remove($slide['id']);
        Storage::delete("pictures/".$slide['image']);
        return $slide['id'];
    }


    public function getSlideById($id, Request $request){
        $slid = $this->slide->getById($id);
        //$slid->file = $this->createFile("pictures/".$slid['image']);
        return json_encode($slid);
    }

    public function createFile(Request $request){
        //return $request->get('filename');
        //$file = base64_encode(Storage::get("pictures/".$request->get('filename')));
        $file = Storage::get("pictures/".$request->get('filename'));
        return response($file, 200)->header('Content-Type', 'image/jpeg');
    }

    public function changePhoto(Request $request){
        $this->slide->updateImage($request->get('slideId'),$request->get('cropImageName'));
        Storage::delete("pictures/".$request->get('oldImageName'));
        return response(json_encode($request->all()));
    }


    public function setSlideColor(Request $request){
        $this->slide->setColor($request->get('id'),$request->get('color'));
        return response(json_encode($request->all()));
    }

    public function changeText(Request $request){
        $this->slide->changeText($request->get('id'),
                                 $request->get('fieldname'),
                                 $request->get('data'));
        return response(json_encode($request->all()));
    }
}
