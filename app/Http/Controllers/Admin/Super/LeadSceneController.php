<?php

namespace App\Http\Controllers\Admin\Super;

use App\Entities\Language;
use App\Entities\Leadscene;
use App\Entities\Link;
use App\Entities\Slide;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Services\LinkParseHelper;

class LeadSceneController extends Controller
{
    private $leadscene;
    private $linkparse;

    public function __construct(Repositories\LeadsceneRepositoryEloquent $leadscene, LinkParseHelper $linkparse)
    {
        $this->middleware('auth');
        $this->leadscene = $leadscene;
        $this->linkparse = $linkparse;

    }

    public function index(){


        $language = [
            'name'=>LaravelLocalization::getCurrentLocaleName(),
            'tag'=>LaravelLocalization::getCurrentLocale(),
            'id'=>Language::where('tag',LaravelLocalization::getCurrentLocale())->first()->id
        ];

        $content = view('admin.homepage.content');

        return view('admin.homepagemaster',
            [
                'content'=>$content,
                'controller'=>'admin/homepage/homepage.controller.js',
                'lang'=>$language,
                'languages'=>LaravelLocalization::getSupportedLocales()
            ]
        );

    }


    public function getLeadScenes(Request $request){

        return $this->leadscene->searchByCriteria($request->all());

    }


    public function uploadImageCustomView(Request $request){

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
            return response('{"success":true,"data":'.json_encode($std).'}', 200, ['Content-type'=>'application/json']);
        }

    }


    public function uploadImageBannerView(Request $request){


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
            return response('{"success":true,"data":'.json_encode($std).'}', 200, ['Content-type'=>'application/json']);
        }

    }



    public function createFrontPageStructure(Request $request){

//        Leadscene::query()->update(['active' => 0]);

        Storage::disk('public')->put('data-'.date('Y-m-d-h-i-s').'.json', \GuzzleHttp\json_encode($request->get('structure')));
        $lang_tag = Language::find($request->get('lang'))->tag;
        $full_structure = $this->leadscene->prepareFastStructure(\GuzzleHttp\json_decode(\GuzzleHttp\json_encode($request->get('structure'))), $lang_tag);

        $this->leadscene->create([
            'language_id'=>$request->get('lang'),
            'name'=>$request->get('name'),
            'serialize'=>\GuzzleHttp\json_encode($full_structure),
            'fast_serialize'=>\GuzzleHttp\json_encode($request->get('structure')),
            'active'=>0
        ]);

        return $request->all();

    }


    public function updateCurrentFrontPageStructure(Request $request){

        Storage::disk('public')->put('data-'.date('Y-m-d-h-i-s').'.json', \GuzzleHttp\json_encode($request->get('structure')));
        $lang_tag = Language::find($request->get('lang'))->tag;
        $full_structure = $this->leadscene->prepareFastStructure(\GuzzleHttp\json_decode(\GuzzleHttp\json_encode($request->get('structure'))), $lang_tag);


        $this->leadscene->update([
            'language_id'=>$request->get('lang'),
            'name'=>$request->get('name'),
            'serialize'=>\GuzzleHttp\json_encode($full_structure),
            'fast_serialize'=>\GuzzleHttp\json_encode($request->get('structure')),
            'active'=>1
        ], $request->get('lead_id'));

        return $full_structure;

    }


    public function updateLeadSceneName(Request $request){

        $this->leadscene->update(['name'=>$request->get('name')], $request->get('id'));
        return response('{"success":true}', 200, ['Content-Type'=>'application/json']);

    }


    public function updateLeadSceneDateAndOther(Request $request){
        
//        return $request->all();

        Leadscene::query()->where('language_id',$request->get('lang'))->update([$request->get('field') => $request->get('other_value')]);

        $this->leadscene->update([$request->get('field')=>$request->get('value')],$request->get('id'));

        return response('{"success":true}', 200, ['Content-Type'=>'application/json']);

    }


    public function deleteLeadScene($id){

        $this->leadscene->delete($id);
        return response('{"success":true}', 200, ['Content-Type'=>'application/json']);

    }


    public function showPreviewLeadScene($id){


        $language = [
            'name'=>LaravelLocalization::getCurrentLocaleName(),
            'tag'=>LaravelLocalization::getCurrentLocale(),
            'id'=>Language::where('tag',LaravelLocalization::getCurrentLocale())->first()->id
        ];

        $leadscene = $this->leadscene->find($id);
        $slides = Slide::where('status',1)->orderBy('ord','asc');

        $slds = [];

        $count_slds = $slides->count();

        if($count_slds>0){

            foreach($slides->get() as $k=>$s){
                $s->color = \GuzzleHttp\json_decode($s->color);
                array_push($slds,$s);
            }

        }else{
            $slds = null;
        }



        if(!is_null($leadscene)){
            $structure = \GuzzleHttp\json_decode($leadscene->serialize);
        }else{
            $structure = null;
        }

        $footmenu = $this->linkparse->getLinkMenuRecById(Link::where('ref',null)->first());

        $content = view('front.homepage.content', ['structure'=>$structure]);


        return view('front.masterhome',
            [
                'content'=>$content,
                'controller'=>'front/homepage/homepage.controller.js',
                'lang'=>$language,
                'languages'=>LaravelLocalization::getSupportedLocales(),
                'colorclass'=>'outrageous-orange-color',
                'slides'=>$slds,
                'count_slides'=>$count_slds,
                'footmenu'=>$footmenu
            ]
        );


    }



}
