<?php

namespace App\Http\Controllers\Front;

use App\Entities\Language;
use App\Entities\Link;
use App\Services\DshHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Services\LinkParseHelper;
use Illuminate\Support\Facades\Storage;

class CmsController extends Controller
{

    private $linkparse;

    public function __construct(LinkParseHelper $linkparse)
    {
        $this->linkparse = $linkparse;
    }


    public function indexPrimaryLink($primary){


        $language = [
            'name'=>LaravelLocalization::getCurrentLocaleName(),
            'tag'=>LaravelLocalization::getCurrentLocale(),
            'id'=>Language::where('tag',LaravelLocalization::getCurrentLocale())->first()->id
        ];


        if(Link::where('link',$primary)->count()>0) {

            $linkdata = $this->linkparse->parseLink(Link::where('link', $primary)->first()->id, $language['id']);


            $first_link = Link::where(['language_id' => $language['id']])->first();

            $foot_menu = $this->linkparse->getTreeFirstChildTreeRecursion($first_link->id);

            $content = view('front.links.' . $linkdata->folder . '.content', ['data' => $linkdata, 'language' => $language]);
            

            return view('front.master' . $linkdata->master,
                [
                    'content' => $content,
                    'controller' => $linkdata->controller,
                    'lang' => $language,
                    'languages' => LaravelLocalization::getSupportedLocales(),
                    'colorclass' => $linkdata->color->classname,
                    'footmenu' => $foot_menu,
                    'all' => $linkdata
                ]
            );

        }else{
            abort(404, '404');
        }


    }


    public function indexDeeperLink($primary,$last){

        $language = [
            'name'=>LaravelLocalization::getCurrentLocaleName(),
            'tag'=>LaravelLocalization::getCurrentLocale(),
            'id'=>Language::where('tag',LaravelLocalization::getCurrentLocale())->first()->id
        ];


        if(Link::where('link',$primary)->count()>0) {




            $linkdata = $this->linkparse->parseLink(Link::where('link', $primary . '/' . $last)->first()->id, $language['id']);


            $first_link = Link::where(['language_id' => $language['id']])->first();


            $foot_menu = $this->linkparse->getTreeFirstChildTreeRecursion($first_link->id);


            $content = view('front.links.' . $linkdata->folder . '.content', ['data' => $linkdata, 'language' => $language]);

            return view('front.master' . $linkdata->master,
                [
                    'content' => $content,
                    'controller' => $linkdata->controller,
                    'lang' => $language,
                    'languages' => LaravelLocalization::getSupportedLocales(),
                    'colorclass' => $linkdata->color->classname,
                    'footmenu' => $foot_menu,
                    'all' => $linkdata
                ]
            );

        }else{
            abort(404, '404');
        }

    }


    public function indexSingleAgenda_copy($singleslugagenda){


        $language = [
            'name'=>LaravelLocalization::getCurrentLocaleName(),
            'tag'=>LaravelLocalization::getCurrentLocale(),
            'id'=>Language::where('tag',LaravelLocalization::getCurrentLocale())->first()->id
        ];

        $obj = DshHelper::parseSingleUrlAgenda($singleslugagenda,'App\Entities\Agenda');

        if(!is_null($obj)) {

            $first_link = Link::where(['language_id' => $language['id']])->first();

            $foot_menu = $this->linkparse->getTreeFirstChildTreeRecursion($first_link->id);
            preg_match('#(<img.*?>)#', $obj->single->content, $imgs);
            if(count($imgs)>0) {

                              preg_match('/alt="([^"]+)"/', $imgs[0], $alt);
                              //$alt = array_pop($alt);
                    if(count($alt)>0) {
                        $alt[0] = str_replace('alt="', '', $alt[0]);
                        $alt[0] = str_replace('"', '', $alt[0]);
                        $obj->single->content =
                            str_replace($imgs[0], '<div style="font-size:0.7em; font-weight:500; font-style:italic; line-height:1.3em; text-align:center">' . $imgs[0] . '<br /><br /><span>' . $alt[0] . '</span></div>', $obj->single->content);
                    }
            }

            $obj->single->content = str_replace('src="http:','src="https:',$obj->single->content);
            $content = view('front.agenda.single', ['data' => $obj, 'language' => $language]);

			 //Storage::disk('public')->put('test.txt', json_encode($obj));
			
            return view('front.mastersingle',
                [
                    'content' => $content,
                    'controller' => 'front/single/single.controller.js',
                    'lang' => $language,
                    'languages' => LaravelLocalization::getSupportedLocales(),
                    'colorclass' => $obj->viewprofile->color->classname,
                    'footmenu' => $foot_menu,
                    'all'=>$obj
                ]
            );

        }else{
            abort(404, '404');
        }


    }


    public function indexSingleAgenda($singleslugagenda){


        $language = [
            'name'=>LaravelLocalization::getCurrentLocaleName(),
            'tag'=>LaravelLocalization::getCurrentLocale(),
            'id'=>Language::where('tag',LaravelLocalization::getCurrentLocale())->first()->id
        ];

        $obj = DshHelper::parseSingleUrlAgenda($singleslugagenda,'App\Entities\Agenda');

        if(!is_null($obj)) {
            $first_link = Link::where(['language_id' => $language['id']])->first();
            $foot_menu = $this->linkparse->getTreeFirstChildTreeRecursion($first_link->id);
            preg_match_all('/<img.*?alt=["\'](.*?)["\']+(.*?)>/i', $obj->single->content, $imgs);

            for($i = 0; $i<count($imgs); $i++){
                if(count($imgs)>2 && isset($imgs[0][$i]) && $imgs[1][$i] != '') {
                    $obj->single->content = str_replace($imgs[0][$i], '<div style="font-size:0.7em; font-weight:500; font-style:italic; line-height:1.3em; text-align:center">' . $imgs[0][$i] . '<br /><br /><span>' . $imgs[1][$i] . '</span></div>', $obj->single->content);
                }
            }


            Storage::disk('public')->put('costam.json', \GuzzleHttp\json_encode($imgs));
            $content = view('front.agenda.single', ['data' => $obj, 'language' => $language]);


            return view('front.mastersingle',
                [
                    'content' => $content,
                    'controller' => 'front/single/single.controller.js',
                    'lang' => $language,
                    'languages' => LaravelLocalization::getSupportedLocales(),
                    'colorclass' => $obj->viewprofile->color->classname,
                    'footmenu' => $foot_menu,
                    'all'=>$obj
                ]
            );

        }else{
            abort(404, '404');
        }
    }


    public function indexSingleContent($singleslugcontent){


        $language = [
            'name'=>LaravelLocalization::getCurrentLocaleName(),
            'tag'=>LaravelLocalization::getCurrentLocale(),
            'id'=>Language::where('tag',LaravelLocalization::getCurrentLocale())->first()->id
        ];

        $obj = DshHelper::parseSingleUrlcontent($singleslugcontent,'App\Entities\content');

        if(!is_null($obj)) {
            $first_link = Link::where(['language_id' => $language['id']])->first();
            $foot_menu = $this->linkparse->getTreeFirstChildTreeRecursion($first_link->id);
            preg_match_all('/<img.*?alt=["\'](.*?)["\']+(.*?)>/i', $obj->single->content, $imgs);

            for($i = 0; $i<count($imgs); $i++){
                if(count($imgs)>2 && isset($imgs[0][$i]) && $imgs[1][$i] != '') {
                    $obj->single->content = str_replace($imgs[0][$i], '<div style="font-size:0.7em; font-weight:500; font-style:italic; line-height:1.3em; text-align:center">' . $imgs[0][$i] . '<br /><br /><span>' . $imgs[1][$i] . '</span></div>', $obj->single->content);
                }
            }

            $content = view('front.content.single', ['data' => $obj, 'language' => $language]);


            return view('front.mastersingle',
                [
                    'content' => $content,
                    'controller' => 'front/single/single.controller.js',
                    'lang' => $language,
                    'languages' => LaravelLocalization::getSupportedLocales(),
                    'colorclass' => $obj->viewprofile->color->classname,
                    'footmenu' => $foot_menu,
                    'all'=>$obj
                ]
            );

        }else{
            abort(404, '404');
        }
    }


    public function indexSingleContent_copy($singleslugcontent){

        $language = [
            'name'=>LaravelLocalization::getCurrentLocaleName(),
            'tag'=>LaravelLocalization::getCurrentLocale(),
            'id'=>Language::where('tag',LaravelLocalization::getCurrentLocale())->first()->id
        ];

        $obj = DshHelper::parseSingleUrlContent($singleslugcontent,'App\Entities\Content');

        if(!is_null($obj)) {

            $first_link = Link::where(['language_id' => $language['id']])->first();

            $foot_menu = $this->linkparse->getTreeFirstChildTreeRecursion($first_link->id);
            
            preg_match('#(<img.*?>)#', $obj->single->content, $imgs);
            if(count($imgs)>0) {

                preg_match('/alt="([^"]+)"/', $imgs[0], $alt);
                //$alt = array_pop($alt);
                if(count($alt)>0) {
                    $alt[0] = str_replace('alt="', '', $alt[0]);
                    $alt[0] = str_replace('"', '', $alt[0]);
                    $obj->single->content =
                        str_replace($imgs[0], '<div style="font-size:0.7em; font-weight:500; font-style:italic; line-height:1.3em; text-align:center">' . $imgs[0] . '<br /><br /><span>' . $alt[0] . '</span></div>', $obj->single->content);
                }
            }

            $obj->single->content = str_replace('src="http:','src="https:',$obj->single->content);
            $content = view('front.content.single', ['data' => $obj, 'language' => $language]);

            return view('front.mastersingle',
                [
                    'content' => $content,
                    'controller' => 'front/single/single.controller.js',
                    'lang' => $language,
                    'languages' => LaravelLocalization::getSupportedLocales(),
                    'colorclass' => $obj->viewprofile->color->classname,
                    'footmenu' => $foot_menu,
                    'all'=>$obj
                ]
            );

        }else{
            abort(404, '404');
        }

    }


    public function indexSinglePublication($singleslugpublication){

        $language = [
            'name'=>LaravelLocalization::getCurrentLocaleName(),
            'tag'=>LaravelLocalization::getCurrentLocale(),
            'id'=>Language::where('tag',LaravelLocalization::getCurrentLocale())->first()->id
        ];


        $obj = DshHelper::parseSingleUrlPublication($singleslugpublication,'App\Entities\Publication');

        if(!is_null($obj)) {

            $first_link = Link::where(['language_id' => $language['id']])->first();

            $foot_menu = $this->linkparse->getTreeFirstChildTreeRecursion($first_link->id);


            $content = view('front.publication.single', ['data' => $obj, 'language' => $language]);



            return view('front.masterpublication',
                [
                    'content' => $content,
                    'controller' => 'front/single/single.controller.js',
                    'lang' => $language,
                    'languages' => LaravelLocalization::getSupportedLocales(),
                    'colorclass' => $obj->viewprofile->color->classname,
                    'footmenu' => $foot_menu,
                    'all'=>$obj
                ]
            );

        }else{
            abort(404, '404');
        }

    }

}
