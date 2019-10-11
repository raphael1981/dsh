<?php

namespace App\Http\Controllers\Front;

use App\Entities\Language;
use App\Entities\Link;
use App\Entities\Slide;
use App\Repositories\LeadsceneRepositoryEloquent;
use App\Services\LinkParseHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Repositories;

class HomePageController extends Controller
{

    private $leadscene;
    private $linkparse;

    public function __construct(LeadsceneRepositoryEloquent $leadscene, LinkParseHelper $linkparse){

        $this->leadscene = $leadscene;
        $this->linkparse = $linkparse;

    }


    public function index(){


        $language = [
            'name'=>LaravelLocalization::getCurrentLocaleName(),
            'tag'=>LaravelLocalization::getCurrentLocale(),
            'id'=>Language::where('tag',LaravelLocalization::getCurrentLocale())->first()->id
        ];

        $leadscene = $this->leadscene->findWhere(['language_id'=>$language['id'], 'active'=>1])->first();
        $slides = Slide::where('status',1)
            ->where('language_id',$language['id'])
            ->orderBy('ord','asc');

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

        if(LaravelLocalization::getCurrentLocale()=='en'){$id=1;}else{$id=2;}

        $footmenu = $this->linkparse->getLinkMenuRecById(Link::where('ref',null)->where('language_id',$id)->first());

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
