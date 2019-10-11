<?php

namespace App\Repositories;

use App\Entities\Agenda;
use App\Entities\Content;
use App\Entities\Language;
use App\Entities\Link;
use Illuminate\Support\Facades\App;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\LeadsceneRepository;
use App\Entities\Leadscene;
use App\Validators\LeadsceneValidator;
use App\Services\DshHelper;

/**
 * Class LeadsceneRepositoryEloquent
 * @package namespace App\Repositories;
 */
class LeadsceneRepositoryEloquent extends BaseRepository implements LeadsceneRepository
{

    private static $word_count = ['first', 'second', 'third'];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Leadscene::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    public function searchByCriteria($data){



        $lang = Language::where('id', $data['lang'])->first();

        $query = Leadscene::where(function($q) use ($data){

            foreach($data['searchcolumns'] as $name=>$boolean){
                if($boolean){
                    $q->orWhere($name, 'LIKE', '%' . $data['frase'] . '%');
                }
            }

        });


        foreach($data['filter'] as $name=>$filter) {

            if(!is_null($filter['value'])){
                $query
                    ->where($name, $filter['value']);
            }

        }

        $query
            ->where('language_id', $lang->id);

        $total = $query->count();


        $elements = $query
            ->orderBy('updated_at','desc')
            ->skip($data['start'])
            ->take($data['limit'])
            ->get();


        $std = new \stdClass();
        $std->elements = $elements;
        $std->count = $total;

        return \GuzzleHttp\json_encode($std);

    }


    public function prepareFastStructure($structure, $lang_tag){



        $farr = [];
        App::setLocale($lang_tag);

        $iter = 1;

//        dd($structure);

        foreach($structure as $k=>$el){

            switch($el[0]->colclass){

                case 'col-md-4':

                    $std = new \stdClass();

                    $other_attr = null;

                    $std->htmlview = $this->createViewElementsThree($el[0]->value, $lang_tag);
                    $std->rowclass = null;
                    $std->other_attr = $other_attr;

                    array_push(
                        $farr,
                        $std
                    );


                    break;

                case 'col-md-12':

                    $std = new \stdClass();

                    $other_attr = 'home="content-column"';

                    $std->htmlview = $this->createViewElementsOne($el[0]->value, $lang_tag);
                    $std->rowclass = 'white-gird-row';
                    $std->other_attr = $other_attr;

                    array_push(
                        $farr,
                        $std
                    );

                    break;

            }
        }

        return $farr;

    }


    private function createViewElementsThree($elements,$lang_tag){

        $html = '';
        $element_class = 'gird-col gird-out-off gird-single';

        foreach($elements as $key=>$elem){

            $margin_cls = '';
            if($key==0){
                $margin_cls = 'no-margin';
            }


            switch($elem->current_type){

//                case 'agenda':
//
//                    $view = view('partials.three.agenda',
//                        [
//                            'data'=>$elem->data,
//                            'colclass'=>$element_class.' '.$margin_cls.' agenda '.self::$word_count[$key]
//                        ]);
//                    $html .= $view->render();
//
//                    break;
//
//                case 'content':
//
//                    $view = view('partials.three.content',
//                        [
//                            'data'=>$elem->data,
//                            'colclass'=>$element_class.' '.$margin_cls.' content '.self::$word_count[$key]
//                        ]);
//                    $html .= $view->render();
//
//                    break;

                case 'only_youtube':

                    $view = view('partials.three.ytplayer',
                        [
                            'data'=>$elem->data,
                            'colclass'=>$element_class.' '.$margin_cls.' only_youtube '.self::$word_count[$key],
                            'lang_tag'=>$lang_tag
                        ]);
                    $html .= $view->render();

                    break;

                case 'youtube_banner':

                    $view = view('partials.three.ytbanner',
                        [
                            'data'=>$elem->data,
                            'colclass'=>$element_class.' '.$margin_cls.' youtube_banner '.self::$word_count[$key],
                            'lang_tag'=>$lang_tag
                        ]);
                    $html .= $view->render();

                    break;

                case 'external_baner':

                    $view = view('partials.three.banner',
                        [
                            'data'=>$elem->data,
                            'colclass'=>$element_class.' '.$margin_cls.' external_baner '.self::$word_count[$key],
                            'lang_tag'=>$lang_tag
                        ]);
                    $html .= $view->render();

                    break;

                case 'agenda_custom_image':

                    //DshHelper::addToFile('cowtrawiepiszczy.txt',json_encode($elem->data->entity_data));
                    $elem->data->entity_data = Agenda::find($elem->data->entity_data->id);
                    $now_agenda_data_params = \GuzzleHttp\json_decode(Agenda::find($elem->data->entity_data->id)->params);
                    $elem->data->entity_data->params = $now_agenda_data_params;
                    $elem->data->border_color = $now_agenda_data_params->color;
                    $elem->data->entity_data->categories = Agenda::find($elem->data->entity_data->id)->categories()->get();
                    $elem->data->first_tag = $this->getCurrentLinkSection($elem->data->entity_data->id, 'App\Entities\Agenda');

                    $view = view('partials.three.customagenda',
                        [
                            'data'=>$elem->data,
                            'colclass'=>$element_class.' '.$margin_cls.' agenda_custom_image '.self::$word_count[$key],
                            'lang_tag'=>$lang_tag
                        ]);
                    $html .= $view->render();

                    break;

                case 'content_custom_image':

                    $elem->data->entity_data = Content::find($elem->data->entity_data->id);
                    $now_content_data_params = \GuzzleHttp\json_decode(Content::find($elem->data->entity_data->id)->params);
                    $elem->data->entity_data->params = $now_content_data_params;
                    $elem->data->border_color = $now_content_data_params->color;
                    $elem->data->entity_data->categories = Content::find($elem->data->entity_data->id)->categories()->get();
                    $elem->data->first_tag = $this->getCurrentLinkSection($elem->data->entity_data->id, 'App\Entities\Content');

                    $view = view('partials.three.customcontent',
                        [
                            'data'=>$elem->data,
                            'colclass'=>$element_class.' '.$margin_cls.' content_custom_image '.self::$word_count[$key],
                            'lang_tag'=>$lang_tag
                        ]);
                    $html .= $view->render();

                    break;

            }

        }

        return $html;

    }

    private function createViewElementsOne($elements,$lang_tag){

        $html = '';
        $element_class = 'gird-col';

        foreach($elements as $key=>$elem){

            switch($elem->current_type){

//                case 'agenda':
//
//                    $view = view('partials.one.agenda',
//                        [
//                            'data'=>$elem->data,
//                            'colclass'=>$element_class.' agenda '.self::$word_count[$key]
//                        ]);
//                    $html .= $view->render();
//
//                    break;
//
//                case 'content':
//
//                    $view = view('partials.one.content',
//                        [
//                            'data'=>$elem->data,
//                            'colclass'=>$element_class.' content '.self::$word_count[$key]
//                        ]);
//                    $html .= $view->render();
//
//                    break;

                case 'only_youtube':

                    $view = view('partials.one.ytplayer',
                        [
                            'data'=>$elem->data,
                            'colclass'=>$element_class.' only_youtube '.self::$word_count[$key],
                            'lang_tag'=>$lang_tag
                        ]);
                    $html .= $view->render();

                    break;

                case 'youtube_banner':

                    $view = view('partials.one.ytbanner',
                        [
                            'data'=>$elem->data,
                            'colclass'=>$element_class.' youtube_banner '.self::$word_count[$key],
                            'lang_tag'=>$lang_tag
                        ]);
                    $html .= $view->render();

                    break;

                case 'external_baner':

                    $view = view('partials.one.banner',
                        [
                            'data'=>$elem->data,
                            'colclass'=>$element_class.' external_baner '.self::$word_count[$key],
                            'lang_tag'=>$lang_tag
                        ]);
                    $html .= $view->render();

                    break;

                case 'agenda_custom_image':

//                    return Agenda::find($elem->data->entity_data->id);
                    $elem->data->entity_data = Agenda::find($elem->data->entity_data->id);
                    $now_agenda_data_params = \GuzzleHttp\json_decode(Agenda::find($elem->data->entity_data->id)->params);
                    $elem->data->entity_data->params = $now_agenda_data_params;
                    $elem->data->border_color = $now_agenda_data_params->color;
                    $elem->data->entity_data->categories = Agenda::find($elem->data->entity_data->id)->categories()->get();
                    $elem->data->first_tag = $this->getCurrentLinkSection($elem->data->entity_data->id, 'App\Entities\Agenda');


                    $view = view('partials.one.customagenda',
                        [
                            'data'=>$elem->data,
                            'colclass'=>$element_class.' agenda_custom_image '.self::$word_count[$key],
                            'lang_tag'=>$lang_tag
                        ]);
                    $html .= $view->render();

                    break;

                case 'content_custom_image':

                    $elem->data->entity_data = Content::find($elem->data->entity_data->id);
                    $now_content_data_params = \GuzzleHttp\json_decode(Content::find($elem->data->entity_data->id)->params);
                    $elem->data->entity_data->params = $now_content_data_params;
                    $elem->data->border_color = $now_content_data_params->color;
                    $elem->data->entity_data->categories = Content::find($elem->data->entity_data->id)->categories()->get();
                    $elem->data->first_tag = $this->getCurrentLinkSection($elem->data->entity_data->id, 'App\Entities\Content');

                    $view = view('partials.one.customcontent',
                        [
                            'data'=>$elem->data,
                            'colclass'=>$element_class.' content_custom_image '.self::$word_count[$key],
                            'lang_tag'=>$lang_tag
                        ]);
                    $html .= $view->render();

                    break;

            }

        }

        return $html;

    }

    private function getCurrentLinkSection($item_id, $entity){

        $arr = [];

        $links = Link::where(function($q){

            $q->where('template_id', 5);
            $q->orWhere('template_id', 7);

        })->get();

        $cats = $entity::find($item_id)->categories()->get();

        foreach($links as $k=>$l){

            $prms = \GuzzleHttp\json_decode($l->params);

            if(property_exists($prms, 'filter')){

                $deep = [];
                $deep['link'] = $l;
                $deep['exist_count'] = 0;

                foreach($prms->filter as $f){

                    foreach($cats as $c){
                        if($c->id==$f->id){
                            $deep['exist_count']++;
                        }
                    }

                }

                array_push($arr, $deep);

            }


        }

        $collection = collect($arr);
        $sorted = $collection->sortByDesc('exist_count');
        

        if($sorted->first()['exist_count']>0){
            return $sorted->first()['link'];
        }else{
            return null;
        }

    }

}
