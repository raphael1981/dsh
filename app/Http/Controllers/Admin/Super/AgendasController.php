<?php

namespace App\Http\Controllers\Admin\Super;

use App\Entities\Agenda;
use App\Entities\Category;
use App\Entities\Gallery;
use App\Entities\Language;
use App\Entities\Logotype;
use App\Entities\Media;
use App\Entities\Place;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Repositories;

class AgendasController extends Controller
{

    private $agenda;
    private $link;
    private $media;

    public function __construct(Repositories\AgendaRepositoryEloquent $agenda, Repositories\LinkRepositoryEloquent $link, Repositories\MediaRepositoryEloquent $media)
    {
        $this->middleware('auth');
        $this->agenda = $agenda;
        $this->link = $link;
        $this->media = $media;

    }


    public function index(){

        $language = [
            'name'=>LaravelLocalization::getCurrentLocaleName(),
            'tag'=>LaravelLocalization::getCurrentLocale(),
            'id'=>Language::where('tag',LaravelLocalization::getCurrentLocale())->first()->id
        ];


        $content = view('admin.agendas.content');

        return view('admin.agendasmaster',
            [
                'content'=>$content,
                'controller'=>'admin/agendas/agendas.controller.js',
                'lang'=>$language,
                'languages'=>LaravelLocalization::getSupportedLocales()
            ]
        );

    }


    public function getAgendas(Request $request){

        return $this->agenda->searchByCriteria($request->all());

    }

    public function fastPlaceCreate(Request $request){


        $place = Place::create([
            'name'=>$request->get('name'),
            'alias'=>str_slug($request->get('name'), '-'),
            'description'=>(is_null($request->get('description')))?'':$request->get('description'),
            'params'=>'{}'
        ]);

        return $place;
    }


    public function createNewAgenda(Request $request){

        $view_profile = $request->get('data')['view_profile'];
        $intro_image = $request->get('data')['intro_image'];

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

        $attach_ids = $this->addAttachOrFind($request->get('data')['attachments']);

        $img_path = null;

        if(!is_null($request->get('data')['intro_image'])){

            $rex = explode('/',$request->get('data')['intro_image']['request_uncomplete']);
            $img_path = $rex[count($rex)-2];

        }

        $data = $request->get('data');

        $a = $this->agenda->create([
            'language_id'=>$request->get('lang'),
            'place_id'=>(!is_null($data['place']))?$data['place']['id']:null,
            'title'=>$data['title'],
            'alias'=>str_slug($data['title'], '-'),
            'image'=>(!is_null($data['intro_image']))?$data['intro_image']['file']:null,
            'disk'=>(!is_null($data['intro_image']))?$data['intro_image']['disk']:null,
            'image_path'=>(!is_null($data['intro_image']))?$img_path:null,
            'intro'=>(!is_null($data['intro']))?$data['intro']:'',
            'content'=>(!is_null($data['content']))?$data['content']:'',
            'params'=>(!is_null($json_params))?$json_params:'{}',
            'begin'=>(!is_null($data['begin_convert']))?$data['begin_convert']:null,
            'end'=>(!is_null($data['end_convert']))?$data['end_convert']:$data['begin_convert'],
            'begin_time'=>(!is_null($data['begin_time']))?$data['begin_time_convert']:null,
            'end_time'=>(!is_null($data['end_time']))?$data['end_time_convert']:null,
            'suffix'=>$suffix,
            'meta_description'=>'',
            'meta_keywords'=>''

        ]);

        $collect_categories = [];

        if(!is_null($data['categories'])){

            foreach($data['categories'] as $ct){

                Agenda::find($a->id)->categories()->save(Category::find($ct));
                array_push($collect_categories, Category::find($ct));

            }

        }

        Cache::forever('agenda:categories:'.$a->id, $collect_categories);


        $collect_galleries = [];

        if(!is_null($data['galleries'])){

            foreach($data['galleries'] as $gl){

                Agenda::find($a->id)->galleries()->save(Gallery::find($gl));
                array_push($collect_galleries,Gallery::find($gl));

            }

        }

        Cache::forever('agenda:galleries:'.$a->id, $collect_galleries);

        $collect_medias = [];

        foreach($attach_ids as $k=>$id){

            Agenda::find($a->id)->medias()->save(Media::find($id));
            array_push($collect_medias, Media::find($id));

        }

        Cache::forever('agenda:medias:'.$a->id, $collect_medias);


        $this->link->updateTreeArrayAgendaLinks($request->get('lang'), $a->id, $request->get('tree'));

        Cache::forever('agenda:links:'.$request->get('aid'), Agenda::find($a->id)->links()->get());


        foreach($request->get('data')['logotypes'] as $l){
            Agenda::find($a->id)->logotypes()->save(Logotype::find($l['id']));
        }

        return $a;

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


    public function updateAgendaData(Request $request){

        $view_profile = $request->get('data')['view_profile'];
        $intro_image = $request->get('data')['intro_image'];

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
        

        $attach_ids = $this->addAttachOrFind($request->get('data')['attachments']);

        $img_path = null;

        if(!is_null($request->get('data')['intro_image'])){

            $rex = explode('/',$request->get('data')['intro_image']['request_uncomplete']);
            $img_path = $rex[count($rex)-2];

        }

        $data = $request->get('data');

        $a = $this->agenda->update([
            'language_id'=>$request->get('lang'),
            'place_id'=>(!is_null($data['place']))?$data['place']['id']:null,
            'title'=>$data['title'],
            'alias'=>str_slug($data['title'], '-'),
            'image'=>(!is_null($data['intro_image']))?$data['intro_image']['file']:null,
            'disk'=>(!is_null($data['intro_image']))?$data['intro_image']['disk']:null,
            'image_path'=>(!is_null($data['intro_image']))?$img_path:null,
            'intro'=>(!is_null($data['intro']))?$data['intro']:'',
            'content'=>(!is_null($data['content']))?$data['content']:'',
            'params'=>(!is_null($json_params))?$json_params:'{}',
            'begin'=>(!is_null($data['begin_convert']))?$data['begin_convert']:null,
            'end'=>(!is_null($data['end_convert']))?$data['end_convert']:null,
            'begin_time'=>(!is_null($data['begin_time']))?$data['begin_time_convert']:null,
            'end_time'=>(!is_null($data['end_time']))?$data['end_time_convert']:null,
            'suffix'=>$suffix,
            'meta_description'=>'',
            'meta_keywords'=>''

        ],$request->get('aid'));

        Agenda::find($a->id)->categories()->detach();

        $collect_categories = [];

        if(!is_null($data['categories'])){

            foreach($data['categories'] as $ct){

                Agenda::find($a->id)->categories()->save(Category::find($ct));
                array_push($collect_categories, Category::find($ct));

            }

        }

        Cache::forever('agenda:categories:'.$request->get('aid'), $collect_categories);


        $collect_galleries = [];

        Agenda::find($a->id)->galleries()->detach();

        if(!is_null($data['galleries'])){

            foreach($data['galleries'] as $gl){

                Agenda::find($a->id)->galleries()->save(Gallery::find($gl));
                array_push($collect_galleries,Gallery::find($gl));

            }

        }

        Cache::forever('agenda:galleries:'.$a->id, $collect_galleries);


        Agenda::find($a->id)->medias()->detach();

        $collect_medias = [];

        foreach($attach_ids as $k=>$id){

            Agenda::find($a->id)->medias()->save(Media::find($id));
            array_push($collect_medias, Media::find($id));

        }

        Cache::forever('agenda:medias:'.$request->get('aid'), $collect_medias);


        Agenda::find($a->id)->links()->detach();

        $this->link->updateTreeArrayAgendaLinks($request->get('lang'), $a->id, $request->get('tree'));

        Cache::forever('agenda:links:'.$request->get('aid'), Agenda::find($a->id)->links()->get());


        Agenda::find($a->id)->logotypes()->detach();

        foreach($request->get('data')['logotypes'] as $l){
            Agenda::find($a->id)->logotypes()->save(Logotype::find($l['id']));
        }


        return $a;

    }

    public function changeAgendaData(Request $request){

        $this->agenda->update([$request->get('field')=>$request->get('value')],$request->get('id'));

        return response('{"success":true}', 200, ['Content-Type'=>'application/json']);

    }

    public function getFullData($id){
        return $this->agenda->getFullAgendaData($id);
    }


    public function getRotorsByPhrase(Request $request){

        $rotors = Logotype::where(function($q) use ($request){
            $q->where('name','LIKE','%'.$request->get('phrase').'%');
            $q->orWhere('rotor_title','LIKE','%'.$request->get('phrase').'%');
        })->get();

        return $rotors;

    }

}
