<?php

namespace App\Http\Controllers\Admin\Super;

use App\Entities\Language;
use App\Entities\Link;
use HelperRepositories\CustomHelpRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Repositories;

class LinksController extends Controller
{

    private $link;

    public function __construct(Repositories\LinkRepositoryEloquent $link,
                                Repositories\ContentRepositoryEloquent $content)
    {
        $this->middleware('auth');
        $this->link = $link;
    }


    public function index()
    {


        $language = [
            'name'=>LaravelLocalization::getCurrentLocaleName(),
            'tag'=>LaravelLocalization::getCurrentLocale(),
            'id'=>Language::where('tag',LaravelLocalization::getCurrentLocale())->first()->id
        ];
        ;

        $content = view('admin.links.content');

        return view('admin.linksmaster',
            [
                'content'=>$content,
                'controller'=>'admin/links/links.controller.js',
                'lang'=>$language,
                'languages'=>LaravelLocalization::getSupportedLocales()
            ]
        );

    }


    public function updateTree(Request $request){

        return $this->link->updateTree($request->get('tree'), $request->get('lang_id'));

    }

    public function getLinkById($id){
        $resp = new \stdClass;
        $resp->link = $this->link->find($id);
        $resp->items = $this->link->getItems($id);
        return json_encode($resp);

    }

    public function getLinkIconAndLinkData($id){

        $std = new \stdClass();

        $l = Link::find($id);
        $p = \GuzzleHttp\json_decode($l->params);

        if(isset($p->icon)){

            $std->success = true;
            $std->icon = $p->icon;
            $std->link = $l;

            return response(\GuzzleHttp\json_encode($std), 200, ['Content-Type'=>'application/json']);
        }

        $std->success = true;
        $std->icon = null;
        $std->link = $l;

        return response(\GuzzleHttp\json_encode($std), 200, ['Content-Type'=>'application/json']);

    }


    public function getLinkColorndLinkData($id){

        $std = new \stdClass();

        $l = Link::find($id);
        $p = \GuzzleHttp\json_decode($l->params);

        if(isset($p->color)){

            $std->success = true;
            $std->color = $p->color;
            $std->link = $l;

            return response(\GuzzleHttp\json_encode($std), 200, ['Content-Type'=>'application/json']);
        }

        $std->success = true;
        $std->color = null;
        $std->link = $l;

        return response(\GuzzleHttp\json_encode($std), 200, ['Content-Type'=>'application/json']);

    }


    public function getItemsByLinkId($id){
        $resp = $this->link->getAllItems($id);
        return json_encode($resp);
    }

    public function checkIsNameOnLevel(Request $request){

        $boolean = true;

        $check_array = [];

        $links = $this->link->findWhere(['ref'=>$request->get('ref')]);

        foreach($links as $key=>$link){
            if($link->id!=$request->get('id')){
                array_push($check_array,$link);
            }
        }


        foreach($check_array as $key=>$link){
            if($request->get('name')==$link->title){
                $boolean=false;
            }
        }

        return response('{"success":'.$boolean.'}', 200, ['Content-Type'=>'application/json']);

    }


    public function setNewItemsOrder(Request $request){
        $this->link->setNewOrder($request->get('items'), $request->get('lid'));
        return $request->all();
    }

    public function changeActive(Request $request){
        $this->link->changeActive($request->get('id'),$request->get('status'));
        return $request->all();
    }

    public function setNewStatus(Request $request){
        $this->link->setNewStatus($request->get('link_id'),
                                  $request->get('item')['id'],
                                  $request->get('item')['row_type'],
                                  $request->get('item')['status']);
        return response('gooood!');
    }

    public function removeFromLink(Request $request){
        $query = $this->link->removeFromLink($request->get('linkId'),
                                    $request->get('itemId'),
                                    $request->get('type'));
        return response($query);
    }


    public function fastFindSingleData(Request $request, CustomHelpRepository $custom){

        return $custom->getElementsContentsAgendasByFrase($request->get('frase'));

    }

    public function getDataConfigSingle($id, Request $request, CustomHelpRepository $custom){

        $data = \GuzzleHttp\json_decode(Link::find($id)->params);

        $fulldata = $custom->createModelToLinkParams($data->model->id, $data->model->entity);

        return $fulldata;
    }


    public function getDataConfigFiltered($id){

        $std = new \stdClass();

        $l = Link::find($id);

        $params = \GuzzleHttp\json_decode($l->params);

        $std->params = $params;

        $array = [];

//        foreach($params->filter as $k=>$f){
//            $en = $f->entity;
//            array_push($array, $en::find($f->id));
//        }

        if(isset($params->filter )) {
            foreach ($params->filter as $k => $f) {
                array_push($array, $f->id);
            }
        }

        $std->cats = $array;

        return \GuzzleHttp\json_encode($std);
    }


    public function getDataConfigMany($id){

        $l = Link::find($id);

        return $l->params;

    }


    public function getDataConfigUnstandard($id){

        $l = Link::find($id);

        return $l->params;

    }


    public function getDataConfigFilterLeaf($id){

        $l = Link::find($id);

        return $l->params;

    }


    public function saveTemplateData(Request $request){

        $ttype = $request->get('ttype');

        if($this->link->templateUpdateLinkData($ttype, $request->get('tid'), $request->get('id'), $request->get('edit_params'), $request->get('desc'))){
            return $request->all();
        }

        return response('{"success":false}', 200, ['Content-Type'=>'application/json']);
    }

    public function setChangeIconLink(Request $request){

        $l = Link::find($request->get('id'));
        $p = \GuzzleHttp\json_decode($l->params);
        $p->icon = $request->get('icon');
        $l->params = \GuzzleHttp\json_encode($p);
        $l->save();

        return $request->all();

    }

    public function setChangeColorLink(Request $request){

        $l = Link::find($request->get('id'));
        $p = \GuzzleHttp\json_decode($l->params);
        $p->color = $request->get('color');
        $l->params = \GuzzleHttp\json_encode($p);
        $l->save();

        return $request->all();

    }





}
