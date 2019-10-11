<?php

namespace App\Http\Controllers\Admin\Super;

use App\Entities\Category;
use App\Entities\Content;
use App\Entities\Gallery;
use App\Entities\Language;
use App\Entities\Logotype;
use App\Entities\Media;
use App\Repositories\ContentRepositoryEloquent;
use App\Repositories\LinkRepositoryEloquent;
use App\Repositories\MediaRepositoryEloquent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ContentsController extends Controller
{
    private $content;
    private $link;
    private $media;

    public function __construct(ContentRepositoryEloquent $content, LinkRepositoryEloquent $link, MediaRepositoryEloquent $media)
    {
        $this->middleware('auth');
        $this->content = $content;
        $this->link = $link;
        $this->media = $media;
    }

    public function index()
    {


        $language = [
            'name'=>LaravelLocalization::getCurrentLocaleName(),
            'tag'=>LaravelLocalization::getCurrentLocale(),
            'id'=>Language::where('tag',LaravelLocalization::getCurrentLocale())->first()->id
        ];
        ;

        $content = view('admin.contents.content');

        return view('admin.contentsmaster',
            [
                'content'=>$content,
                'controller'=>'admin/contents/contents.controller.js',
                'lang'=>$language,
                'languages'=>LaravelLocalization::getSupportedLocales()
            ]
        );

    }


    public function getContents(Request $request){

        return $this->content->searchByCriteria($request->all());

    }

    public function changeData(Request $request){

        $this->content->update([$request->get('field')=>$request->get('value')],$request->get('id'));

        return response('{"success":true}', 200, ['Content-Type'=>'application/json']);

    }


    public function getFullData($id){

        return $this->content->getFullContentData($id);

    }


    public function createNewContent(Request $request){


        $view_profile = $request->get('art')['view_profile'];
        $intro_image = $request->get('art')['intro_image'];

        $std_params = new \stdClass();

        if(!is_null($intro_image)){
            $std_params->format_intro_image = $intro_image['format'];
        }

        if(!is_null($view_profile)){

            $suffix = $view_profile['suffix'];
            $std_params->name = $view_profile['name'];
            $std_params->icon = $view_profile['icon'];
            $std_params->color = $view_profile['color'];
            $json_params = \GuzzleHttp\json_encode($std_params);

        }else{
            $suffix = null;
            $json_params = null;
        }

        $img_path = null;

        if(!is_null($request->get('art')['intro_image'])){

            $rex = explode('/',$request->get('art')['intro_image']['request_uncomplete']);
            $img_path = $rex[count($rex)-2];

        }


        $attach_ids = $this->addAttachOrFind($request->get('art')['attachments']);


        $c = $this->content->create([

            'language_id'=>$request->get('lang'),
            'title'=>$request->get('art')['title'],
            'alias'=>str_slug($request->get('art')['title'],'-'),
            'image'=>(!is_null($request->get('art')['intro_image']))?$request->get('art')['intro_image']['file']:null,
            'image_path'=>(!is_null($request->get('art')['intro_image']))?$img_path:null,
            'disk'=>(!is_null($request->get('art')['intro_image']))?$request->get('art')['intro_image']['disk']:null,
            'intro'=>(is_null($request->get('art')['introtext'])?'':$request->get('art')['introtext']),
            'content'=>(is_null($request->get('art')['fulltext'])?'':$request->get('art')['fulltext']),
            'author'=>$request->get('art')['author'],
            'type'=>$request->get('type'),
            'url'=>$request->get('art')['external_url'],
            'suffix'=>$suffix,
            'params'=>(!is_null($json_params))?$json_params:'{}',
            'meta_description'=>'',
            'meta_keywords'=>'',
            'published_at'=>($request->get('art')['published_at']=='Invalid date' || is_null($request->get('art')['published_at']))?null:$request->get('art')['published_at']

        ]);


        $collect_categories = [];

        if(!is_null($request->get('art')['categories'])){

            foreach($request->get('art')['categories'] as $ct){

                Content::find($c->id)->categories()->save(Category::find($ct));
                array_push($collect_categories, Category::find($ct));

            }

        }

        Cache::forever('content:categories:'.$c->id, $collect_categories);


        $collect_galleries = [];

        if(!is_null($request->get('art')['galleries'])){

            foreach($request->get('art')['galleries'] as $g){
                Content::find($c->id)->galleries()->save(Gallery::find($g));
                array_push($collect_galleries, Gallery::find($g));
            }

        }

        Cache::forever('content:galleries:'.$c->id, $collect_galleries);


        $collect_medias = [];

        foreach($attach_ids as $k=>$id){

            Content::find($c->id)->medias()->save(Media::find($id));
            array_push($collect_medias, Media::find($id));

        }

        Cache::forever('content:medias:'.$c->id, $collect_medias);


        $this->link->updateTreeArrayContentLinks($request->get('lang'), $c->id, $request->get('tree'));

        Cache::forever('content:links:'.$request->get('aid'), Content::find($c->id)->links()->get());


        foreach($request->get('art')['logotypes'] as $l){
            Content::find($c->id)->logotypes()->save(Logotype::find($l['id']));
        }

        return $c;

    }


    private function addAttachOrFind($attachments){

        $ids = [];

        foreach($attachments as $key=>$attach){

            if(Media::where(['media_relative_path'=>$attach['file'], 'title'=>$attach['basename']])->count()>0){

                $baseattach = Media::where(['media_relative_path'=>$attach['file'], 'title'=>$attach['basename']])->first();

                array_push($ids, $baseattach->id);

            }else{

                $std = new \stdClass();
                $std->icon = $attach['icon'];
                $json = \GuzzleHttp\json_encode($std);

                $m = $this->media->create([
                    'title'=>$attach['basename'],
                    'filename'=>$attach['info']['name'],
                    'full_filename'=>$attach['name'],
                    'disk'=>$attach['disk'],
                    'media_relative_path'=>$attach['file'],
                    'mimetype'=>$attach['info']['mimetype'],
                    'suffix'=>$attach['info']['suffix'],
                    'params'=>$json
                ]);

                array_push($ids, $m->id);

            }

        }

        return $ids;

    }



    public function updateContentData(Request $request){

//        return $request->all();

        $view_profile = $request->get('art')['view_profile'];
        $intro_image = $request->get('art')['intro_image'];
        
        $std_params = new \stdClass();

        if(!is_null($intro_image)){
            $std_params->format_intro_image = $intro_image['format'];
        }
        if(!is_null($view_profile)){

            $suffix = $view_profile['suffix'];
            $std_params->name = $view_profile['name'];
            $std_params->icon = $view_profile['icon'];
            $std_params->color = $view_profile['color'];
            $json_params = \GuzzleHttp\json_encode($std_params);

        }else{
            $suffix = null;
            $json_params = null;
        }

        $attach_ids = $this->addAttachOrFind($request->get('art')['attachments']);

        $img_path = null;

        if(!is_null($request->get('art')['intro_image'])){

            $rex = explode('/',$request->get('art')['intro_image']['request_uncomplete']);
            $img_path = $rex[count($rex)-2];

        }


        $c = $this->content->update([

            'language_id'=>$request->get('lang'),
            'title'=>$request->get('art')['title'],
            'alias'=>str_slug($request->get('art')['title'],'-'),
            'image'=>(!is_null($request->get('art')['intro_image']))?$request->get('art')['intro_image']['file']:null,
            'image_path'=>(!is_null($request->get('art')['intro_image']))?$img_path:null,
            'disk'=>(!is_null($request->get('art')['intro_image']))?$request->get('art')['intro_image']['disk']:null,
            'intro'=>(is_null($request->get('art')['introtext'])?'':$request->get('art')['introtext']),
            'content'=>(is_null($request->get('art')['fulltext'])?'':$request->get('art')['fulltext']),
            'author'=>$request->get('art')['author'],
            'type'=>$request->get('type'),
            'url'=>$request->get('art')['external_url'],
            'suffix'=>$suffix,
            'params'=>(!is_null($json_params))?$json_params:'{}',
            'meta_description'=>'',
            'meta_keywords'=>'',
            'published_at'=>($request->get('art')['published_at']=='Invalid date' || is_null($request->get('art')['published_at']))?null:$request->get('art')['published_at']

        ], $request->get('cid'));

        Content::find($c->id)->medias()->detach();

        $collect_medias = [];

        foreach($attach_ids as $k=>$id){

            Content::find($c->id)->medias()->save(Media::find($id));
            array_push($collect_medias, Media::find($id));

        }

        Cache::forever('content:medias:'.$c->id, $collect_medias);


        Content::find($c->id)->categories()->detach();

        $collect_categories = [];

        if(!is_null($request->get('art')['categories'])){

            foreach($request->get('art')['categories'] as $ct){

                if(gettype($ct)=='integer'){

                    Content::find($c->id)->categories()->save(Category::find($ct));
                    array_push($collect_categories, Category::find($ct));

                }else{

                    Content::find($c->id)->categories()->save(Category::find($ct['id']));
                    array_push($collect_categories, Category::find($ct['id']));

                }

            }

        }

        Cache::forever('content:categories:'.$c->id, $collect_categories);



        $collect_galleries = [];

        Content::find($c->id)->galleries()->detach();

        if(!is_null($request->get('art')['galleries'])){

            foreach($request->get('art')['galleries'] as $g){

                if(gettype($g)=='integer'){

                    Content::find($c->id)->galleries()->save(Gallery::find($g));
                    array_push($collect_galleries, Gallery::find($g));

                }else{

                    Content::find($c->id)->galleries()->save(Gallery::find($g['id']));
                    array_push($collect_galleries, Gallery::find($g['id']));

                }


            }

        }

        Cache::forever('content:galleries:'.$c->id, $collect_galleries);

//        Content::find($c->id)->links()->detach();

        $this->link->updateTreeArrayContentLinks($request->get('lang'), $c->id, $request->get('tree'));

        Cache::forever('content:links:'.$request->get('aid'), Content::find($c->id)->links()->get());


        Content::find($c->id)->logotypes()->detach();

        foreach($request->get('art')['logotypes'] as $l){
            Content::find($c->id)->logotypes()->save(Logotype::find($l['id']));
        }

        return $c;

    }

}
