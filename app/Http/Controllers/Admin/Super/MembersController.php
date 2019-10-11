<?php

namespace App\Http\Controllers\Admin\Super;

use App\Entities\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Repositories;

class MembersController extends Controller
{

    private $member;

    public function __construct(Repositories\MemberRepositoryEloquent $members)
    {
        $this->middleware('auth');
        $this->member = $members;
    }


    public function index()
    {


        $language = [
            'name'=>LaravelLocalization::getCurrentLocaleName(),
            'tag'=>LaravelLocalization::getCurrentLocale(),
            'id'=>Language::where('tag',LaravelLocalization::getCurrentLocale())->first()->id
        ];
        ;

        $content = view('admin.members.content');

        return view('admin.mediamaster',
            [
                'content'=>$content,
                'controller'=>'admin/members/members.controller.js',
                'lang'=>$language,
                'languages'=>LaravelLocalization::getSupportedLocales()
            ]
        );

    }

    public function getMembers(Request $request){
        return $this->member->searchByCriteria($request->all());
    }

    public function changeData(Request $request){

        $this->member->update([$request->get('field')=>$request->get('value')],$request->get('id'));

        return response('{"success":true}', 200, ['Content-Type'=>'application/json']);

    }

}
