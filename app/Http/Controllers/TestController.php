<?php

namespace App\Http\Controllers;

use App\Entities\Agenda;
use App\Entities\Category;
use App\Entities\Content;
use App\Entities\Gallery;
use App\Entities\Language;
use App\Entities\Link;
use App\Entities\Picture;
use App\Entities\ViewProfile;
use App\Repositories\LeadsceneRepositoryEloquent;
use App\Repositories\LinkRepositoryEloquent;
use HelperRepositories\CustomHelpRepository;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Mmanos\Search\Facade as Search;
use Collective\Remote\RemoteFacade as SSH;

class TestController extends Controller
{


    public function index1($prefix, CustomHelpRepository $custom){




        $colors = config('services')['template_colors'];
        $temp_config= config('template_config')['template_config_data'];


        $array = [
            [
                'title'=>'O nas',
                'template_id'=>1,
                'ltype'=>'internal',
                'description'=>null,
                'description_links'=>null,
                'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[2]).',"first_leaf_id":2,"is_show_desc":false}',
                'children'=>[
                    [
                        'title'=>'misja',
                        'template_id'=>2,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[2]).',"config":'.\GuzzleHttp\json_encode($temp_config['is_unstandard'][0]).',"is_show_desc":false}'
                    ],
                    [
                        'title'=>'zespół',
                        'template_id'=>2,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[2]).',"config":'.\GuzzleHttp\json_encode($temp_config['is_unstandard'][1]).',"is_show_desc":false}'
                    ],
                    [
                        'title'=>'kawiarnia Karowa 20',
                        'template_id'=>2,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[2]).',"config":'.\GuzzleHttp\json_encode($temp_config['is_unstandard'][2]).',"is_show_desc":false}'
                    ],
                    [
                        'title'=>'historia budynku',
                        'template_id'=>2,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[2]).',"config":'.\GuzzleHttp\json_encode($temp_config['is_unstandard'][3]).',"is_show_desc":false}'
                    ],
                    [
                        'title'=>'księgarania XXw',
                        'template_id'=>2,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[2]).',"config":'.\GuzzleHttp\json_encode($temp_config['is_unstandard'][4]).',"is_show_desc":false}'
                    ],
                    [
                        'title'=>'ogród Sprawiedliwych',
                        'template_id'=>2,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[2]).',"config":'.\GuzzleHttp\json_encode($temp_config['is_unstandard'][5]).',"is_show_desc":false}'
                    ],
                    [
                        'title'=>'dla mediów',
                        'template_id'=>2,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[2]).',"config":'.\GuzzleHttp\json_encode($temp_config['is_unstandard'][5]).',"is_show_desc":false}'
                    ],
                    [
                        'title'=>'ogłoszenia',
                        'template_id'=>2,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[2]).',"config":'.\GuzzleHttp\json_encode($temp_config['is_unstandard'][6]).',"is_show_desc":false}'
                    ],
                    [
                        'title'=>'kontakt',
                        'template_id'=>2,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[2]).',"config":'.\GuzzleHttp\json_encode($temp_config['is_unstandard'][7]).',"is_show_desc":false}'
                    ]

                ]
            ],
            [
                'title'=>'wystawy',
                'template_id'=>5,
                'ltype'=>'internal',
                'description'=>null,
                'description_links'=>null,
                'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[1]).', "filter":'.\GuzzleHttp\json_encode($this->getExibCategoryList()).',"is_show_desc":false,"elastic_view":{"type":"tiles","name":"Tylko kafeli"},"order":{"type":"desc", "name":"Malejąco"},"group":{"type":false,"name":"Tak"}}'
            ],
            [
                'title'=>'wydarzenia',
                'template_id'=>5,
                'ltype'=>'internal',
                'description'=>null,
                'description_links'=>null,
                'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[2]).', "filter":'.\GuzzleHttp\json_encode($this->getEventsCategoryList()).',"is_show_desc":false, "elastic_view":{"type":"all","name":"Układ zmienny"},"order":{"type":"desc", "name":"Rosnąco"},"group":{"type":true,"name":"Nie"}}'
            ],
            [
                'title'=>'publikacje',
                'template_id'=>6,
                'ltype'=>'internal',
                'description'=>'Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, ',
                'description_links'=>'[{"name":"sklep online","link":"http://'.config('services')['domains']['customers'].'/o-nas/kawiarnia-karowa-20", "target":"_self"},{"name":"Księgarnia XX Wieku","link":"http://dsh.dsh.usermd.net/bookstore", "target":"_blank"}]',
                'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[3]).',"config":'.\GuzzleHttp\json_encode($temp_config['is_filteredleaf'][0]).',"is_show_desc":true}',
                'children'=>[
                    [
                        'title'=>'relacje',
                        'template_id'=>4,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[3]).',"config":'.\GuzzleHttp\json_encode($temp_config['is_many'][0]).',"is_show_desc":false}'
                    ],
                    [
                        'title'=>'albumy',
                        'template_id'=>4,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[3]).',"config":'.\GuzzleHttp\json_encode($temp_config['is_many'][0]).',"is_show_desc":false}'
                    ],
                    [
                        'title'=>'Varsaviana',
                        'template_id'=>4,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[3]).',"config":'.\GuzzleHttp\json_encode($temp_config['is_many'][0]).',"is_show_desc":false}'
                    ],
                    [
                        'title'=>'dla dzieci',
                        'template_id'=>4,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[3]).',"config":'.\GuzzleHttp\json_encode($temp_config['is_many'][0]).',"is_show_desc":false}'
                    ],
                    [
                        'title'=>'filmy',
                        'template_id'=>4,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[3]).',"config":'.\GuzzleHttp\json_encode($temp_config['is_many'][0]).',"is_show_desc":false}'
                    ],
                    [
                        'title'=>'mapy',
                        'template_id'=>4,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[3]).',"config":'.\GuzzleHttp\json_encode($temp_config['is_many'][0]).',"is_show_desc":false}'
                    ]
                ]
            ],

            [
                'title'=>'Archiwum Historii Mówionej',
                'template_id'=>4,
                'ltype'=>'internal',
                'description'=>null,
                'description_links'=>null,
                'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[3]).',"config":'.\GuzzleHttp\json_encode($temp_config['is_many'][0]).',"is_show_desc":false}'
            ],
            [
                'title'=>'Edukacja',
                'template_id'=>6,
                'ltype'=>'internal',
                'description'=>null,
                'description_links'=>null,
                'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[4]).',"is_show_desc":false}',
                'children'=>[
                    [
                        'title'=>'Dla uczniów',
                        'template_id'=>3,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[4]).', "model":'.\GuzzleHttp\json_encode($this->createFastModelSingle(1, 'App\Entities\Agenda')).',"is_show_desc":false}',
                        'children'=>[
                            [
                                'title'=>'Szkoła podstawowa',
                                'template_id'=>3,
                                'ltype'=>'internal',
                                'description'=>null,
                                'description_links'=>null,
                                'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[4]).', "model":'.\GuzzleHttp\json_encode($this->createFastModelSingle(2, 'App\Entities\Content')).',"is_show_desc":false}',
                            ],
                            [
                                'title'=>'Gimnazja i licea',
                                'template_id'=>3,
                                'ltype'=>'internal',
                                'description'=>null,
                                'description_links'=>null,
                                'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[4]).', "model":'.\GuzzleHttp\json_encode($this->createFastModelSingle(3, 'App\Entities\Agenda')).',"is_show_desc":false}',
                            ],
                            [
                                'title'=>'Studenci',
                                'template_id'=>3,
                                'ltype'=>'internal',
                                'description'=>null,
                                'description_links'=>null,
                                'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[4]).', "model":'.\GuzzleHttp\json_encode($this->createFastModelSingle(3, 'App\Entities\Content')).',"is_show_desc":false}',
                            ]
                        ]
                    ],
                    [
                        'title'=>'Dla nauczycieli',
                        'template_id'=>3,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[4]).', "model":'.\GuzzleHttp\json_encode($this->createFastModelSingle(4, 'App\Entities\Agenda')).',"is_show_desc":false}',
                        'children'=>[
                            [
                                'title'=>'Pakiety edukacyjne',
                                'template_id'=>3,
                                'ltype'=>'internal',
                                'description'=>null,
                                'description_links'=>null,
                                'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[4]).', "model":'.\GuzzleHttp\json_encode($this->createFastModelSingle(2, 'App\Entities\Agenda')).',"is_show_desc":false}',
                            ]
                        ]
                    ],
                    [
                        'title'=>'Dla niepełnosprawnych',
                        'template_id'=>3,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[4]).', "model":'.\GuzzleHttp\json_encode($this->createFastModelSingle(1, 'App\Entities\Content')).',"is_show_desc":false}',
                    ],
                    [
                        'title'=>'Dla rodzin',
                        'template_id'=>3,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[4]).', "model":'.\GuzzleHttp\json_encode($this->createFastModelSingle(1, 'App\Entities\Agenda')).',"is_show_desc":false}',
                    ],
                    [
                        'title'=>'Grupy międzynarodowe',
                        'template_id'=>3,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[4]).', "model":'.\GuzzleHttp\json_encode($this->createFastModelSingle(3, 'App\Entities\Content')).',"is_show_desc":false}',
                    ],
                    [
                        'title'=>'Edukacja w Domu Spotkań z Historią',
                        'template_id'=>3,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[4]).', "model":'.\GuzzleHttp\json_encode($this->createFastModelSingle(1, 'App\Entities\Agenda')).',"is_show_desc":false}',
                    ]
                ]
            ],
            [
                'title'=>'multimedia',
                'template_id'=>6,
                'ltype'=>'internal',
                'description'=>null,
                'description_links'=>null,
                'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[5]).',"is_show_desc":false}',
                'children'=>[
                    [
                        'title'=>'zdjęcia',
                        'template_id'=>4,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[5]).',"is_show_desc":false}',
                    ],
                    [
                        'title'=>'filmy',
                        'template_id'=>4,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[5]).',"is_show_desc":false}',
                    ],
                    [
                        'title'=>'aplikacje mobilne',
                        'template_id'=>4,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[5]).',"is_show_desc":false}',
                    ],
                    [
                        'title'=>'serwisy internetowe',
                        'template_id'=>4,
                        'ltype'=>'internal',
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[5]).',"is_show_desc":false}',
                    ]
                ]
            ],
            [
                'title'=>'projekty specjalne',
                'template_id'=>4,
                'ltype'=>'internal',
                'description'=>null,
                'description_links'=>null,
                'params'=>'{"color":'.\GuzzleHttp\json_encode($colors[6]).',"is_show_desc":false}',
            ]
        ];


        return response(\GuzzleHttp\json_encode($array), 200, ['Content-Type'=>'application/json']);

    }

    public function index2($prefix, LinkRepositoryEloquent $link){

//        $data = file_get_contents(url('/test'));
//        $array = \GuzzleHttp\json_decode($data, true);
//
//        $link->createTreeFromArray($array,1);

//        dd($link->getTreeArray());

//        dd(storage_path('app/pictures'));

//        $files = \Illuminate\Support\Facades\Storage::disk('media')->files();

//        dd($files);

//        dd(Link::find(1)->contents()->where('id', 2)->count());

//        dd(Gallery::find(1)->pictures()->orderBy('ord','asc')->get());

//        dd(LaravelLocalization::getSupportedLocales());

//        echo Storage::disk('public')->get('leadone.json');

//        dd(\App\Entities\ViewProfile::where('type','content')->get()->toArray());

        $data = file_get_contents(url('/test/1'));
        $array = \GuzzleHttp\json_decode($data, true);
        dd($array);

    }


    public function index3($prefix){

        $array = [
            [
                'title'=>'About us',
                'template_id'=>null,
                'ltype'=>null,
                'description'=>null,
                'description_links'=>null,
                'params'=>'{"is_show_desc":false}',
                'children'=>[
                    [
                        'title'=>'Our Mission',
                        'template_id'=>null,
                        'ltype'=>null,
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"is_show_desc":false}',

                    ],
                    [
                        'title'=>'History of the place',
                        'template_id'=>null,
                        'ltype'=>null,
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"is_show_desc":false}',
                    ],
                    [
                        'title'=>'Links',
                        'template_id'=>null,
                        'ltype'=>null,
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"is_show_desc":false}',
                    ],
                    [
                        'title'=>'Contact',
                        'template_id'=>null,
                        'ltype'=>null,
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"is_show_desc":false}',
                    ]

                ]
            ],
            [
                'title'=>'Exibitions',
                'template_id'=>null,
                'params'=>'{}',
                'ltype'=>null,
                'description'=>null,
                'description_links'=>null,
                'children'=>[
                    [
                        'title'=>'Current exhibitions',
                        'template_id'=>null,
                        'ltype'=>null,
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"is_show_desc":false}',
                    ],
                    [
                        'title'=>'Exhibition archive',
                        'template_id'=>null,
                        'ltype'=>null,
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"is_show_desc":false}',
                    ]
                ]
            ],
            [
                'title'=>'Bookstore',
                'template_id'=>null,
                'ltype'=>null,
                'description'=>null,
                'description_links'=>null,
                'params'=>'{"is_show_desc":false}',
                'children'=>[
                    [
                        'title'=>'BOOKSHOP OF THE 20th CENTURY and INTERNET SALES',
                        'template_id'=>null,
                        'ltype'=>null,
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"is_show_desc":false}',
                    ],
                    [
                        'title'=>'Publications',
                        'template_id'=>null,
                        'ltype'=>null,
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"is_show_desc":false}',
                    ]
                ]
            ],
            [
                'title'=>'Education',
                'template_id'=>null,
                'ltype'=>null,
                'description'=>null,
                'description_links'=>null,
                'params'=>'{"is_show_desc":false}',
                'children'=>[
                    [
                        'title'=>'Children, Families and Life Long Learning',
                        'template_id'=>null,
                        'ltype'=>null,
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"is_show_desc":false}',
                    ],
                    [
                        'title'=>'Middle and High Schools',
                        'template_id'=>null,
                        'ltype'=>null,
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"is_show_desc":false}',
                    ],
                    [
                        'title'=>'Primary Schools',
                        'template_id'=>null,
                        'ltype'=>null,
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"is_show_desc":false}',
                    ],
                    [
                        'title'=>'Special Educational Needs',
                        'template_id'=>null,
                        'ltype'=>null,
                        'description'=>null,
                        'description_links'=>null,
                        'params'=>'{"is_show_desc":false}',
                    ]
                ]
            ],

        ];


        return response(\GuzzleHttp\json_encode($array), 200, ['Content-Type'=>'application/json']);

    }


    private function createFastModelSingle($id, $model){
        $std = new \stdClass();
        $std->id = $id;
        $std->entity = $model;
        return $std;
    }

    private function createFastCategoriesList(){

        $cats = Category::all();
        $lastKey = count($cats)-1;

        $b = (boolean) random_int(0, 1);

        $array = [];

        foreach($cats as $k=>$c){

            $b = (boolean) random_int(0, 1);

            if($b){

                $std = new \stdClass();
                $std->id = $c->id;
                $std->entity = 'App\Entities\Category';

                array_push($array, $std);
            }

        }

        return $array;

    }


    private function getExibCategoryList(){

        $array = [];
        $cats = [1,2,3,4];

        for($i=0;$i<count($cats);$i++) {

            $std = new \stdClass();
            $std->id = $cats[$i];
            $std->entity = 'App\Entities\Category';
            array_push($array, $std);

        }

        return $array;

    }

    private function getEventsCategoryList(){

        $array = [];
        $cats = [5,6,7,8,9,10,11,12,13,14,15];

        for($i=0;$i<count($cats);$i++) {

            $std = new \stdClass();
            $std->id = $cats[$i];
            $std->entity = 'App\Entities\Category';
            array_push($array, $std);

        }

        return $array;

    }


    public function index4($prefix,LeadsceneRepositoryEloquent $leadscene){


        $images = glob(storage_path().'/app/public/*{.json}', GLOB_BRACE);

        return view('testjsonmenu',['menu'=>$images]);

    }


    public function index5($prefix, $json, LeadsceneRepositoryEloquent $leadscene){

        $gjson = file_get_contents(storage_path().'/app/public/'.$json);
//        dd(\GuzzleHttp\json_decode($gjson));
        $data = $leadscene->prepareFastStructure(\GuzzleHttp\json_decode($gjson), 'pl');


        return view('test', ['v'=>$data]);

    }

    public function index6($prefix, CustomHelpRepository $custom){
//        return view('test2');

//        dd($custom->createModelToLinkParams(1, 'App\Entities\Agenda'));
//        $files = \Illuminate\Support\Facades\Storage::disk('media')->files();
//        dd(config('services')['mimeicons'][1]['mimelist']);

//        $res = DB::table('agendas')
//            ->join('categorygables', 'agendas.id', '=', 'categorygables.categorygables_id')
//            ->where('categorygables.categorygables_type','App\Entities\Agenda')
//            ->where(function($q){
//                $q->where('categorygables.category_id',1)
//                    ->orWhere('categorygables.category_id',2)
//                    ->orWhere('categorygables.category_id',3)
//                    ->orWhere('categorygables.category_id',4)
//                    ->orWhere('categorygables.category_id',5)
//                    ->orWhere('categorygables.category_id',6)
//                    ->orWhere('categorygables.category_id',7)
//                    ->orWhere('categorygables.category_id',8)
//                    ->orWhere('categorygables.category_id',9)
//                    ->orWhere('categorygables.category_id',10)
//                    ->orWhere('categorygables.category_id',11)
//                    ->orWhere('categorygables.category_id',12);
//            })
//            ->where('begin','>','1.01.2017')
//            ->get();
//
//        dd($res);
//
//        return view('welcome');

//        dd(Lang::get('translations'));

        dd(Link::find(13)->description_links);

    }


    public function index7($prefix)
    {

        dd(Agenda::all());

        return;

        $http_host = 'http://'.config('services')['domains']['admin'];

        $exibitions = file_get_contents($http_host.'/get/olditems/exhib');
        $exibitions = \GuzzleHttp\json_decode($exibitions);

        $exibit_category_id = 1;

        foreach($exibitions as $key=>$item){

            $pic = $this->prepareImage($item);
            $gallery = $this->prepeareGallery($item);

//            $ex_ag = \App\Entities\Agenda::create([
//                'language_id'=>1,
//                'title'=>$item->title,
//                'alias'=>str_slug($item->title,'-'),
//                'image',
//                'image_path',
//                'disk',
//                'intro',
//                'content',
//                'params'=>'{}',
//                'begin'=>$item->begin,
//                'end'=>$item->end
//            ]);
        }

    }

    private function preapareContent(){

    }

    private function prepeareGallery($item){

        $pic_collect = [];
        $translations = new \stdClass();
        $translations->en = '';
        $translations->pl = '';
        $translations = \GuzzleHttp\json_encode($translations);

        foreach($item->gallery as $key=>$pic){

            $path_ex = explode('/',$pic->file);
            $name = end($path_ex);
            $image_content = file_get_contents($pic->file);
            file_put_contents(storage_path().'/app/pictures/archive/'.$name,$image_content);

            $p = Picture::create([
                'image_name'=>$name,
                'image_path'=>':archive',
                'disk'=>'pictures',
                'translations'=>$translations
            ]);

            array_push($pic_collect,$p->id);

        }


        $gal = Gallery::create([
            'title'=>$item->title,
            'alias'=>str_slug($item->title,'-'),
            'regex_tag'=>'',
            'params'=>'{}',
            'collection'=>\GuzzleHttp\json_encode($pic_collect)
        ]);

        $gal->update(['regex_tag'=>'<{gallery:'.$gal->id.'}>']);

        $ord = 1;

        foreach($pic_collect as $i=>$pic){

            DB::table('gallerygables')->insert(
              [
                  'gallery_id'=>$gal->id,
                  'gallerygables_id'=>$pic,
                  'gallerygables_type'=>'App\Entities\Picture',
                  'ord'=>$ord
              ]
            );

            $ord++;

        }


    }

    private function prepareImage($item){

        $path_ex = explode('/',$item->picture);
        $name = end($path_ex);
        $image_content = file_get_contents($item->picture);
        file_put_contents(storage_path().'/app/pictures/archive/'.$name,$image_content);

        $std = new \stdClass();
        $std->image = $name;
        $std->image_path = ':archive';
        $std->disk = 'disk';

        return $std;

    }

    public function index8($prefix)
    {
        $regex = '/\[gallery ids="[0-9,]*"(| [a-z]+="[a-z]+")\]/';

        $lsd = 'djsghfjhsfg sffs [gallery ids="10014,10015,10016,10017,10018,10019,10020,10025"] sdsds [gallery ids="10014,10015,10016,10017,10018,10019,10020,10021" orderby="band"]';

        if(preg_match_all($regex,$lsd,$matches)){
            echo 'sss';
            dd($matches);
        }

    }


    public function index9($prefix){


        $vars = '';

        foreach(config('services')['template_colors'] as $key=>$value){

            $vars .= '@'.str_slug($value['classname'],'_').': #'.$value['rgb'].';';

            $vars .= "\r\n";

        }

        echo '<pre>'.$vars.'</pre>';

        $colors = '';

        foreach(config('services')['template_colors'] as $key=>$value){

            $colors .= '
            body.'.$value['classname'].'{

                .foot-beam-full-color{
                    background-color: @'.str_slug($value['classname'],'_').';
                }

                .fliter-color-beam{
                    background-color: @'.str_slug($value['classname'],'_').';
                }

                //'.$value['rgb'].'
                .dsh-container-newsletter{
                    background-color: @'.str_slug($value['classname'],'_').';
                }

                .search-beam-form{
                    background-color: @'.str_slug($value['classname'],'_').';
                }

                .dsh-container.content-single-cont{
                    background-color:#'.$value['bgrgb'].';

                    .data-of-single{
                        color: @'.str_slug($value['classname'],'_').';
                        div.line{
                        background-color: @'.str_slug($value['classname'],'_').';
                        }
                    }

                    .attachments-list{

                        .attach-name{
                            a{
                                color:@'.str_slug($value['classname'],'_').';
                            }
                        }

                    }

                }

                .gird-row.row-archive{

                    .col-archive-years-line{
                        .filter-labels li.element a.active{
                            background-color: @'.str_slug($value['classname'],'_').';
                        }
                    }

                }

                .dsh-container-unstandard.unstandard-view{

                    .filter-labels{

                        li.element{
                            a.active{
                                background-color:@'.str_slug($value['classname'],'_').';
                                color:#FFF;
                            }
                        }

                    }

                    .uns-second-row.bg-color-template{

                        background-color:@'.str_slug($value['classname'],'_').';

                        .uns-section-content.col.persons{

                            background-color:#'.$value['bgrgb'].';

                        }

                    }

                    .uns-second-row.black-bg-row {

                        .uns-section-content.col.persons{

                            background-color:@'.str_slug($value['classname'],'_').';

                        }

                    }

                    .head-of-media-element{
                        background-color:@'.str_slug($value['classname'],'_').';
                    }


                }

                .filter-labels{

                        li.element{
                            a.active{
                                background-color:#'.$value['activergb'].';
                                color:#FFF;
                            }
                        }

                    }

                .color-by-template{
                    .uns-section-content.col{
                        background-color:@'.str_slug($value['classname'],'_').';
                    }
                }

                a.link-external-tag{
                    color:@'.str_slug($value['classname'],'_').';
                }


                .dsh-container.filter-section-cont{
                    background-color: @'.str_slug($value['classname'],'_').';
                }

                .dsh-container.filter-section-cont.none-background-cnt{
                    background-color: transparent;
                }

                .head-mobile-filter{
                    background-color: @'.str_slug($value['classname'],'_').';
                }

                .dsh-container.dsh-container-link-desc{
                    .gird-row{
                        margin-bottom:0;
                        .gird-col{
                            background:transparent;
                            min-height:0;

                            ul.link-desc-menu{

                                li{

                                    .point{

                                        .color-point{
                                            background-color: @'.str_slug($value['classname'],'_').';
                                        }

                                    }

                                    a.link-dsc{
                                        color:@'.str_slug($value['classname'],'_').';
                                    }

                                }

                            }

                        }
                    }
                }

                .dsh-container.content-single-cont{

                    .content-of-single{

                        a{
                            color: @'.str_slug($value['classname'],'_').';
                        }

                        a:hover{
                            color: @'.str_slug($value['classname'],'_').';
                        }

                    }

                    .head-color-480-to-end{
                        background-color: @'.str_slug($value['classname'],'_').';
                    }

                }



                .dsh-container.content-home{
                    background-color:#'.$value['bgrgb'].';
                }

                .dsh-container.contact-cont{
                     background-color:#'.$value['bgrgb'].';
                }

                .dsh-container.content-filter-results{
                      background-color:#'.$value['bgrgb'].';
                }

                .dsh-container.double-many-view{
                    background-color:#'.$value['bgrgb'].';
                }

                .dsh-container.unstandard-view{
                    background-color:#'.$value['bgrgb'].';
                }

                .dsh-container.footer-cont{
                     background-color:#'.$value['footbg'].';
                }

                .dsh-container.one-view{
                    background-color:#'.$value['bgrgb'].';
                }

                .dsh-container.double-view{
                    background-color:#'.$value['bgrgb'].';
                }

                .dsh-container.trio-view{
                    background-color:#'.$value['bgrgb'].';
                }

            }';

            $colors .= "\r\n";
            $colors .= "\r\n";
            $colors .= "\r\n";

        }

        echo '<pre>'.$colors.'</pre>';

        $banners = '';

        foreach(config('services')['template_colors'] as $key=>$value){

            $banners .= '
            .banner-'.$value['classname'].'{

                background-color:@'.str_slug($value['classname'],'_').';

            }';


            $banners .= '
            .banner-big-'.$value['classname'].'{

                background-color:@'.str_slug($value['classname'],'_').';

            }';

            $banners .= "\r\n";
            $banners .= "\r\n";
            $banners .= "\r\n";

        }

        echo '<pre>'.$banners.'</pre>';



    }

    public function index10($prefix, LinkRepositoryEloquent $ln){
//        $arr = [];
//        foreach($ln->getLinkLeafs(13) as $k=>$l){
//            array_push($arr,$l->id);
//        }
//
//        $to_index = [];
//
//        foreach($ln->getAllItemsArrayLinks($arr) as $el){
//            array_push($to_index, \GuzzleHttp\json_decode(\GuzzleHttp\json_encode($el),true));
//        }
//
//        dd($to_index);

//        Search::insert('13', $to_index);

//        dd(Search::select('link_id',14)->get());
    }

    public function index11($prefix){

//        foreach(Content::all() as $k=>$c){
//
//            $c->galleries()->get();
//
//        }


//        SSH::run([
//            'cd /usr/home/raphael/domains/dsh.spaceforweb.pl/public_html',
//            'php artisan cache:polimorfic',
//        ]);

//        $query = DB::table('agendas');
//        $data = $query->whereDate('end', '>=', '2016-01-01')->whereDate('begin', '<=', '2016-12-31')->get()->unique('id')->toArray();
////
//        foreach($data as $key=>$year){
//
//            $cats = collect([Agenda::find($year->id)->categories()->get()]);
//            $cats = \GuzzleHttp\json_decode(\GuzzleHttp\json_encode($cats));
//            $element = \GuzzleHttp\json_decode(\GuzzleHttp\json_encode($year),true);
//            $arr = array_merge($element,$cats);
//            dd($arr);
//
////            Search::index('2016')->insert('2016-'.$year->id,$arr);
//
//
//        }


//        dd( Search::index('2016')->where('status', 0)->get());

//        dd($res[0]);


    }

    public function index12($prefix){

//        foreach(Link::all() as $key=>$link){
//
//            $l = Link::find($link->id);
//            $params = \GuzzleHttp\json_decode($l->params);
//            $std = new \stdClass();
//            $std->name = "Nie pokazuj menu gałęzi";
//            $std->value = false;
//            $params->show_branch_menu = $std;
//
//            Link::find($link->id)->update(
//                [
//                    'params'=>\GuzzleHttp\json_encode($params)
//                ]
//            );
//
//        }

//        foreach(Link::all() as $key=>$link){
//
//            $l = Link::find($link->id);
//            $params = \GuzzleHttp\json_decode($l->params);
//            $std = new \stdClass();
//            $std->name = 'Tak';
//            $std->value = true;
//            $params->is_year_in_filter = $std;
//
//            Link::find($link->id)->update(
//                [
//                    'params'=>\GuzzleHttp\json_encode($params)
//                ]
//            );
//
//        }

//        dd(config('view_profiles')['view_profile_config_publication']);


//        foreach(config('view_profiles')['view_profile_config_publication'] as $prf){
//
//            $std = new \stdClass();
//            $std->color = $prf['color'];
//            $std->icon = $prf['icon'];
//
//            ViewProfile::create([
//                'language_id'=>$prf['lang'],
//                'profile_name'=>$prf['p_name'],
//                'suffix'=>$prf['suffix'],
//                'type'=>$prf['type'],
//                'params'=>\GuzzleHttp\json_encode($std)
//            ]);
//
//        }



    }

    public function index13($prefix){

        $ar = [];

        foreach(Gallery::all() as $key=>$gall){

            array_push($ar, $gall->pictures()->count());

        }

        dd($ar);

    }

    public function index14($prefix){

//        $ar = [];
//
//        foreach(Agenda::all() as $key=>$agenda){
//
//            if(array_key_exists($agenda->alias,$ar)){
//                array_push($ar[$agenda->alias], $agenda);
//            }else{
//                $ar[$agenda->alias]=[];
//                array_push($ar[$agenda->alias], $agenda);
//            }
//
//        }
//
//        dd($ar);


//        \App\User::create([
//            'name'=>'Adam',
//            'email'=>'a.plaskota@dsh.waw.pl',
//            'password'=>bcrypt('alamakota123'),
//            'permission'=>'employee'
//        ]);
//
//        \App\User::create([
//            'name'=>'Milena',
//            'email'=>'m.ryckowska@dsh.waw.pl',
//            'password'=>bcrypt('alamakota123'),
//            'permission'=>'employee'
//        ]);

    }


    public function index15($prefix, Request $request){


//        $query = DB::table('agendas');
//        $data = $query->whereDate('end', '>=', '2016-01-01')->whereDate('begin', '<=', '2016-12-31')->get()->unique('id')->toArray();
//
//        dd($data);

//        foreach($data as $s){
//
//            $ar = \GuzzleHttp\json_decode(json_encode($s),true);
//            Search::index('test')->insert($ar['id'],$ar);
//
//        }


        dd(Search::index('sindex')->search(['title','intro','content'], htmlspecialchars($request->get('word')).'*')->get());


//        SSH::run([
//            'cd '.base_path().'/',
//            'php artisan index:search index',
//        ]);

    }


}
