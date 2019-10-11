<?php

namespace App\Http\Controllers\Admin\Super;

use App\Entities\Gallery;
use App\Entities\Language;
use App\Entities\Picture;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Repositories;

class GalleriesController extends Controller
{

    private $gallery;
    private $picture;

    public function __construct(Repositories\GalleryRepositoryEloquent $gallery, Repositories\PictureRepositoryEloquent $picture)
    {
        $this->gallery = $gallery;
        $this->picture = $picture;
        $this->middleware('auth');
    }


    public function index(){

        $language = [
            'name'=>LaravelLocalization::getCurrentLocaleName(),
            'tag'=>LaravelLocalization::getCurrentLocale(),
            'id'=>Language::where('tag',LaravelLocalization::getCurrentLocale())->first()->id
        ];
        ;

        $content = view('admin.agendas.content');

        return view('admin.galleriesmaster',
            [
                'content'=>$content,
                'controller'=>'admin/galleries/galleries.controller.js',
                'lang'=>$language,
                'languages'=>LaravelLocalization::getSupportedLocales()
            ]
        );

    }


    public function getGalleries(Request $request){
        return $this->gallery->searchByCriteria($request->all());
    }


    public function createNewGallery(Request $request){

        $g = $this->gallery->create([
            'title'=>$request->get('title'),
            'alias'=>str_slug($request->get('title')),
            'regex_tag'=>'',
            'params'=>'{}'
        ]);

        $this->gallery->update([
           'regex_tag'=>'<{gallery:'.$g->id.'}>'
        ],$g->id);

        $i=1;

        foreach($request->get('gallery') as $k=>$p){

            if($p['base']){

                $this->picture->update([
                    'translations'=>\GuzzleHttp\json_encode($p['desc'])
                ], $p['id']);

                Gallery::find($g->id)->pictures()->save(Picture::find($p['id']));
                DB::table('gallerygables')
                    ->where('gallery_id', $g->id)
                    ->where('gallerygables_id', $p['id'])
                    ->where('gallerygables_type', 'LIKE', '%Picture%')
                    ->update(['ord'=>$i]);

            }else{

                $tstd = new \stdClass();
                foreach(LaravelLocalization::getSupportedLocales() as $tag=>$lang){
                    $tstd->{$tag} = '';
                }

                $expath = explode('/',$p['request_uncomplete']);
                $real_path = $expath[count($expath)-1];

                $np = Picture::create([
                    'image_name'=>$p['file'],
                    'image_path'=>$real_path,
                    'disk'=>$p['disk'],
                    'translations'=>\GuzzleHttp\json_encode($p['desc'])
                ]);



                Gallery::find($g->id)->pictures()->save(Picture::find($np->id));
                DB::table('gallerygables')
                    ->where('gallery_id', $g->id)
                    ->where('gallerygables_id', $np->id)
                    ->where('gallerygables_type', 'LIKE', '%Picture%')
                    ->update(['ord'=>$i]);

            }

            $i++;
        }

        return $g;

    }



    public function updateGallery($id, Request $request){



        $g = $this->gallery->update([
            'title'=>$request->get('title'),
            'alias'=>str_slug($request->get('title'))
        ],$id);

        Gallery::find($id)->pictures()->detach();

        $i=1;

        foreach($request->get('gallery') as $k=>$p){

            if($p['base']){

                $this->picture->update([
                    'translations'=>\GuzzleHttp\json_encode($p['desc'])
                ], $p['id']);

                Gallery::find($id)->pictures()->save(Picture::find($p['id']));
                DB::table('gallerygables')
                    ->where('gallery_id', $id)
                    ->where('gallerygables_id', $p['id'])
                    ->where('gallerygables_type', 'LIKE', '%Picture%')
                    ->update(['ord'=>$i]);

            }else{

                $tstd = new \stdClass();
                foreach(LaravelLocalization::getSupportedLocales() as $tag=>$lang){
                    $tstd->{$tag} = '';
                }

                $expath = explode('/',$p['request_uncomplete']);
                $real_path = $expath[count($expath)-1];

                $np = Picture::create([
                    'image_name'=>$p['file'],
                    'image_path'=>$real_path,
                    'disk'=>$p['disk'],
                    'translations'=>\GuzzleHttp\json_encode($p['desc'])
                ]);



                Gallery::find($id)->pictures()->save(Picture::find($np->id));
                DB::table('gallerygables')
                    ->where('gallery_id', $id)
                    ->where('gallerygables_id', $np->id)
                    ->where('gallerygables_type', 'LIKE', '%Picture%')
                    ->update(['ord'=>$i]);

            }

            $i++;
        }

        return $g;

    }


    public function getFullDataGallery($id){

        return \GuzzleHttp\json_encode($this->gallery->getGalleryFullData($id));

    }

}
