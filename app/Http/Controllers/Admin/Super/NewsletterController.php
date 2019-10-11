<?php

namespace App\Http\Controllers\Admin\Super;

use App\Entities\Language;
use App\Entities\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Repositories;
use Maatwebsite\Excel\Facades\Excel;

class NewsletterController extends Controller
{
    private $member;

    public function __construct(Repositories\MemberRepositoryEloquent $members)
    {
        $this->middleware('auth');
        $this->member = $members;
    }


    public function index(){

        $language = [
            'name'=>LaravelLocalization::getCurrentLocaleName(),
            'tag'=>LaravelLocalization::getCurrentLocale(),
            'id'=>Language::where('tag',LaravelLocalization::getCurrentLocale())->first()->id
        ];
        ;

        $content = view('admin.newsletter.content');

        return view('admin.newslettermaster',
            [
                'content'=>$content,
                'controller'=>'admin/newsletter/newsletter.controller.js',
                'lang'=>$language,
                'languages'=>LaravelLocalization::getSupportedLocales()
            ]
        );

    }


    public function getMembers(Request $request){
        return $this->member->searchByCriteria($request->all());
    }


    public function updateMemberField(Request $request){
        $this->member->update([$request->get('field')=>$request->get('value')],$request->get('id'));
    }

    public function deleteMember($id){
        $this->member->delete($id);
    }

    public function exportMembers(Request $request){


        $array = [];

        if($request->get('unregister')=='false' && $request->get('unconfirm')=='false' && $request->get('confirm')=='false') {

            Excel::create(date('d-m-Y_H-i-s').'_subskrybenci', function($excel) {

                $excel->sheet('Sheetname', function($sheet){

                    $sheet->fromArray([]);

                });

            })->export('xls');

        }else{

            $memb = Member::where(function ($q) use ($request) {

                if ($request->get('unregister') == 'true') {
                    $q->orWhere('newsletter', -1);
                }

                if ($request->get('unconfirm') == 'true') {
                    $q->orWhere('newsletter', 0);
                }

                if ($request->get('confirm') == 'true') {
                    $q->orWhere('newsletter', 1);
                }

            })->get();

        }




        foreach($memb as $m){
            $collection = collect($m);
            $filtered = $collection->only(['email']);
            array_push($array,$filtered->all());
        }


        Excel::create(date('d-m-Y_H-i-s').'_subskrybenci', function($excel) use ($array) {

            $excel->sheet('Sheetname', function($sheet) use ($array) {

                $sheet->fromArray($array);

            });

        })->export('xls');

    }

}
