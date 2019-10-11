<?php

namespace App\Http\Controllers\Admin\Super;

use App\Entities\Language;
use App\Entities\Media;
use App\Entities\Publication;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories;
use Illuminate\Support\Facades\Cache;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PublicationsController extends Controller
{

    private $publication;
    private $media;

    public function __construct(Repositories\PublicationRepositoryEloquent $publication, Repositories\MediaRepositoryEloquent $media)
    {
        $this->middleware('auth');
        $this->publication = $publication;
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

        $content = view('admin.publications.content');

        return view('admin.publicationsmaster',
            [
                'content'=>$content,
                'controller'=>'admin/publications/publications.controller.js',
                'lang'=>$language,
                'languages'=>LaravelLocalization::getSupportedLocales()
            ]
        );

    }


    public function getPublications(Request $request){

        return $this->publication->searchByCriteria($request->all());

    }


    public function createNewPublication(Request $request){

        $view_profile = $request->get('data')['view_profile'];

        if(!is_null($view_profile)){

            $suffix = $view_profile['suffix'];
            $std_params = new \stdClass();
            $std_params->name = $view_profile['name'];
            $std_params->icon = $view_profile['icon'];
            $std_params->color = $view_profile['color'];
            $json_params = \GuzzleHttp\json_encode($std_params);

        }else{
            $suffix = null;
            $json_params = null;
        }


        $attach_ids = $this->addAttachOrFind($request->get('data')['attachments']);

        $p = $this->publication->create([

            'language_id'=>$request->get('lang'),
            'title'=>$request->get('data')['title'],
            'alias'=>str_slug($request->get('data')['title'],'-'),
            'intro'=>(is_null($request->get('data')['intro'])?'':$request->get('data')['intro']),
            'content'=>(is_null($request->get('data')['content'])?'':$request->get('data')['content']),
            'suffix'=>$suffix,
            'params'=>(!is_null($json_params))?$json_params:'{}'

        ]);

        $collect_medias = [];

        foreach($attach_ids as $k=>$id){

            Publication::find($p->id)->medias()->save(Media::find($id));
            array_push($collect_medias, Media::find($id));

        }

        Cache::forever('publication:medias:'.$p->id, $collect_medias);

        return $p;

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


    public function updatePublicationData(Request $request){

        $view_profile = $request->get('data')['view_profile'];

        if(!is_null($view_profile)){

            $suffix = $view_profile['suffix'];
            $std_params = new \stdClass();
            $std_params->name = $view_profile['name'];
            $std_params->icon = $view_profile['icon'];
            $std_params->color = $view_profile['color'];
            $json_params = \GuzzleHttp\json_encode($std_params);

        }else{
            $suffix = null;
            $json_params = null;
        }


        $attach_ids = $this->addAttachOrFind($request->get('data')['attachments']);

        $p = $this->publication->update([

            'language_id'=>$request->get('lang'),
            'title'=>$request->get('data')['title'],
            'alias'=>str_slug($request->get('data')['title'],'-'),
            'intro'=>(is_null($request->get('data')['intro'])?'':$request->get('data')['intro']),
            'content'=>(is_null($request->get('data')['content'])?'':$request->get('data')['content']),
            'suffix'=>$suffix,
            'params'=>(!is_null($json_params))?$json_params:'{}'

        ],$request->get('data')['id']);

        Publication::find($p->id)->medias()->detach();

        $collect_medias = [];

        foreach($attach_ids as $k=>$id){

            Publication::find($p->id)->medias()->save(Media::find($id));
            array_push($collect_medias, Media::find($id));

        }

        Cache::forever('publication:medias:'.$p->id, $collect_medias);

        return $p;

    }

    public function changePublicationDataField(Request $request){

        $this->publication->update([$request->get('field')=>$request->get('value')],$request->get('id'));

        return response('{"success":true}', 200, ['Content-Type'=>'application/json']);
    }

    public function getFullData($id){
        return $this->publication->getFullPublicationData($id);
    }


}
