<?php

namespace App\Http\Controllers\Admin\Super;

use App\Entities\Category;
use App\Entities\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Repositories;
use Illuminate\Support\Facades\File;

class CategoriesController extends Controller
{

    private $category;

    public function __construct(Repositories\CategoryRepositoryEloquent $category)
    {
        $this->middleware('auth');
        $this->category = $category;
    }


    public function index(){

        $language = [
            'name'=>LaravelLocalization::getCurrentLocaleName(),
            'tag'=>LaravelLocalization::getCurrentLocale(),
            'id'=>Language::where('tag',LaravelLocalization::getCurrentLocale())->first()->id
        ];
        ;

        $content = view('admin.categories.content');

        return view('admin.categoriesmaster',
            [
                'content'=>$content,
                'controller'=>'admin/categories/categories.controller.js',
                'lang'=>$language,
                'languages'=>LaravelLocalization::getSupportedLocales()
            ]
        );

    }

    public function getCategories(Request $request){

        return $this->category->searchByCriteria($request->all());

    }

    public function getCategoryParams(){

        $std = new \stdClass();

        $files = File::allFiles(public_path().'/'.config('services')['category_svg_folder'].'/');

        $array = [];

        foreach ($files as $file)
        {

            array_push(
                $array,
                str_replace(public_path(),'',$file)
            );

        }

        $std->svg = $array;

        return \GuzzleHttp\json_encode($std);

    }


    public function checkIsCategory(Request $request){

        $count = $this->category->findWhere(['name'=>$request->get('name')])->count();

        if($count>0){
            return response('{"success":true}', 200, ['Content-Type'=>'application/json']);
        }

        return response('{"success":false}', 200, ['Content-Type'=>'application/json']);

    }

    public function checkIsCategoryExceptCurrent(Request $request){


        if($request->get('name')==$request->get('constcat')){
            return response('{"success":false}', 200, ['Content-Type'=>'application/json']);
        }

        $count = $this->category->findWhere(['name'=>$request->get('name')])->count();

        if($count>0){
            return response('{"success":true}', 200, ['Content-Type'=>'application/json']);
        }

        return response('{"success":false}', 200, ['Content-Type'=>'application/json']);


    }


    public function createNewCategory(Request $request){

//        $std = new \stdClass();
//        $std->icon = $request->get('icon');

        $c = $this->category->create([
            'language_id'=>$request->get('lang'),
            'name'=>$request->get('name'),
            'alias'=>str_slug($request->get('name'),'-'),
            'params'=>'{}'
        ]);

        $data = \GuzzleHttp\json_encode($c);

        return response('{"success":true, "data":'.$data.'}', 200, ['Content-Type'=>'application/json']);

    }


    public function updateCategory(Request $request){

//        $std = new \stdClass();
//        $std->icon = $request->get('icon');

        $c = $this->category->update([
            'language_id'=>$request->get('lang'),
            'name'=>$request->get('name'),
            'alias'=>str_slug($request->get('name'),'-'),
            'params'=>'{}'
        ],$request->get('id'));

        $data = \GuzzleHttp\json_encode($c);

        return response('{"success":true, "data":'.$data.'}', 200, ['Content-Type'=>'application/json']);

    }


    public function getCategoryFullData($id){

        return Category::find($id);

    }


}
