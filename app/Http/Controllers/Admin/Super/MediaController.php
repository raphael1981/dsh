<?php

namespace App\Http\Controllers\Admin\Super;

use App\Entities\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Repositories;


class MediaController extends Controller
{
    private $media;

    public function __construct(Repositories\MediaRepositoryEloquent $media)
    {
        $this->middleware('auth');
        $this->media = $media;
    }

    public function index()
    {


        $language = [
            'name'=>LaravelLocalization::getCurrentLocaleName(),
            'tag'=>LaravelLocalization::getCurrentLocale(),
            'id'=>Language::where('tag',LaravelLocalization::getCurrentLocale())->first()->id
        ];


//        dd(Storage::disk('media')->files('edukacja'));
//        dd(Storage::disk('media')->directories(''));



        $content = view('admin.media.content');

        return view('admin.mediamaster',
            [
                'content'=>$content,
                'controller'=>'admin/medias/medias.controller.js',
                'lang'=>$language,
                'languages'=>LaravelLocalization::getSupportedLocales()
            ]
        );

    }

    public function getMedias(Request $request){
        return $this->media->searchByCriteria($request->all());
    }

    public function trashFile(Request $request){
        $content = Storage::disk('media')->get($request->get('file'));
        $path_ex = explode('/',$request->get('file'));
        $name = end($path_ex);
        Storage::disk('trash')->put($name, $content);
        Storage::disk('media')->delete($request->get('file'));
        return end($path_ex);
    }

}
