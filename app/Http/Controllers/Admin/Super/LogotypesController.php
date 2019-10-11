<?php

namespace App\Http\Controllers\Admin\Super;

use App\Entities\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LogotypesController extends Controller
{
    private $logotypes;

    public function __construct(Repositories\LogotypeRepositoryEloquent $logotypes)
    {
        $this->logotypes = $logotypes;
        $this->middleware('auth');
    }


    public function index()
    {


        $language = [
            'name'=>LaravelLocalization::getCurrentLocaleName(),
            'tag'=>LaravelLocalization::getCurrentLocale(),
            'id'=>Language::where('tag',LaravelLocalization::getCurrentLocale())->first()->id
        ];
        ;

        $content = view('admin.logotypes.content');

        return view('admin.logotypesmaster',
            [
                'content'=>$content,
                'controller'=>'admin/logotypes/logotypes.controller.js',
                'lang'=>$language,
                'languages'=>LaravelLocalization::getSupportedLocales()
            ]
        );

    }

    public function getLogotypes(Request $request){
        return $this->logotypes->searchByCriteria($request->all());
    }

    public function getLogotypesOne($id){
        $logotypes = $this->logotypes->find($id);
        $logotypes->logotypes = json_decode($logotypes->logotypes);
        return $logotypes;
    }

    public function updateData(Request $request){
        $this->logotypes->update([
            'name'=>$request->get('data')['name'],
            'rotor_title'=>$request->get('data')['rotor_title'],
            'logotypes'=>json_encode($request->get('logotypes'))
        ], $request->get('id'));

        return $request->all();
    }


    public function createLogotype(Request $request){

        $l = $this->logotypes->create([
            'language_id'=>$request->get('lang_id'),
            'name'=>$request->get('data')['name'],
            'rotor_title'=>$request->get('data')['rotor_title'],
            'logotypes'=>json_encode($request->get('logotypes'))
        ]);

        return $l;
    }


}
