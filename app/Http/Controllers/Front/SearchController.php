<?php

namespace App\Http\Controllers\Front;

use App\Entities\Language;
use App\Entities\Link;
use App\Services\LinkParseHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Mmanos\Search\Facade as Search;

class SearchController extends Controller
{

    private $linkparse;

    public function __construct(LinkParseHelper $linkparse)
    {
        $this->linkparse = $linkparse;
    }


    public function index()
    {


        $language = [
            'name' => LaravelLocalization::getCurrentLocaleName(),
            'tag' => LaravelLocalization::getCurrentLocale(),
            'id' => Language::where('tag', LaravelLocalization::getCurrentLocale())->first()->id
        ];

        $first_link = Link::where(['language_id' => $language['id']])->first();

        $content = view('front.search.content', ['language' => $language]);

        $foot_menu = $this->linkparse->getTreeFirstChildTreeRecursion($first_link->id);

        return view('front.mastersearch',
            [
                'content' => $content,
                'controller' => 'front/search/search.controller.js',
                'lang' => $language,
                'languages' => LaravelLocalization::getSupportedLocales(),
                'colorclass' => 'outrageous-orange-color',
                'footmenu' => $foot_menu,
            ]
        );


    }

    public function getIndexData(Request $request){

        $std = new \stdClass();
        $results = Search::index('sindex')
            ->search(['title','intro','content'], htmlspecialchars($request->get('word')).'*')
            ->where('language_id',$request->get('lang_id'))
            ->get();
        $std->results = array_chunk($results,10);
        $std->count = count($std->results);

//        $this->putDataSearchToFile(htmlspecialchars($request->get('word')),$std->result);

        return json_encode($std);

    }


    private function putDataSearchToFile($phr,$result){

        $std = new \stdClass();
        $str_ag = '';
        $str_ct = '';

        foreach($result as $item){

            if($item['type']=='content'){
                $str_ct .= ','.$item['entid'];
            }
            if($item['type']=='agenda'){
                $str_ag .= ','.$item['entid'];
            }

        }

        $std->agenda = $str_ag;
        $std->content = $str_ct;

        file_get_contents(storage_path().'/app/'.$phr.time().'.json',json_encode($std));
    }

}
