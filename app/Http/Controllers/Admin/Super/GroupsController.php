<?php

namespace App\Http\Controllers\Admin\Super;

use App\Entities\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Repositories;

class GroupsController extends Controller
{

    private $group;

    public function __construct(Repositories\GroupRepositoryEloquent $group)
    {
        $this->middleware('auth');
        $this->group = $group;
    }


    public function index()
    {


        $language = [
            'name'=>LaravelLocalization::getCurrentLocaleName(),
            'tag'=>LaravelLocalization::getCurrentLocale(),
            'id'=>Language::where('tag',LaravelLocalization::getCurrentLocale())->first()->id
        ];
        ;

        $content = view('admin.groups.content');

        return view('admin.groupsmaster',
            [
                'content'=>$content,
                'controller'=>'admin/groups/groups.controller.js',
                'lang'=>$language,
                'languages'=>LaravelLocalization::getSupportedLocales()
            ]
        );

    }


    public function getGroups(Request $request){

        return $this->group->searchByCriteria($request->all());

    }


}
