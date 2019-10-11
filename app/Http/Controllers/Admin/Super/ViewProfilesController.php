<?php

namespace App\Http\Controllers\Admin\Super;

use App\Entities\Agenda;
use App\Entities\Content;
use App\Entities\Language;
use App\Entities\ViewProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ViewProfilesController extends Controller
{
    private $viewprofile;
    private $content;
    private $agenda;

    public function __construct(Repositories\ViewProfileRepositoryEloquent $viewprofile, Repositories\ContentRepositoryEloquent $content, Repositories\AgendaRepositoryEloquent $agenda)
    {
        $this->middleware('auth');
        $this->viewprofile = $viewprofile;
        $this->content = $content;
        $this->agenda = $agenda;
    }

    public function index()
    {


        $language = [
            'name'=>LaravelLocalization::getCurrentLocaleName(),
            'tag'=>LaravelLocalization::getCurrentLocale(),
            'id'=>Language::where('tag',LaravelLocalization::getCurrentLocale())->first()->id
        ];


        $content = view('admin.viewprofile.content');

        return view('admin.viewprofilemaster',
            [
                'content'=>$content,
                'controller'=>'admin/viewprofile/viewprofile.controller.js',
                'lang'=>$language,
                'languages'=>LaravelLocalization::getSupportedLocales()
            ]
        );

    }


    public function changeColor(Request $request){


        $std_params = \GuzzleHttp\json_decode($this->viewprofile->find($request->get('id'))->params);
        $std_params->color = $request->get('color');
        $this->viewprofile->update([
            'params'=>\GuzzleHttp\json_encode($std_params)
        ],$request->get('id'));


        if($request->get('entity')['id']=='content') {

            foreach (Content::where('suffix', $request->get('suffix'))->get() as $k => $ct) {

                $std_base_ct = \GuzzleHttp\json_decode($ct->params);
                $std_base_ct->color = $request->get('color');
                $this->content->update([
                    'params' => \GuzzleHttp\json_encode($std_base_ct)
                ], $ct->id);

            }

        }


        if($request->get('entity')['id']=='agenda') {


            foreach (Agenda::where('suffix', $request->get('suffix'))->get() as $k => $ag) {

                $std_base_ag = \GuzzleHttp\json_decode($ag->params);
                $std_base_ag->color = $request->get('color');
                $this->agenda->update([
                    'params' => \GuzzleHttp\json_encode($std_base_ag)
                ], $ag->id);

            }

        }

        return $request->all();
    }

    public function changeIcon(Request $request){



        $std_params = \GuzzleHttp\json_decode($this->viewprofile->find($request->get('id'))->params);
        $std_params->icon = $request->get('icon');
        $this->viewprofile->update([
            'params'=>\GuzzleHttp\json_encode($std_params)
        ],$request->get('id'));


        if($request->get('entity')['id']=='content') {

            foreach (Content::where('suffix', $request->get('suffix'))->get() as $k => $ct) {

                $std_base_ct = \GuzzleHttp\json_decode($ct->params);
                $std_base_ct->icon = $request->get('icon');
                $this->content->update([
                    'params' => \GuzzleHttp\json_encode($std_base_ct)
                ], $ct->id);

            }

        }


        if($request->get('entity')['id']=='agenda') {


            foreach (Agenda::where('suffix', $request->get('suffix'))->get() as $k => $ag) {

                $std_base_ag = \GuzzleHttp\json_decode($ag->params);
                $std_base_ag->icon = $request->get('icon');
                $this->agenda->update([
                    'params' => \GuzzleHttp\json_encode($std_base_ag)
                ], $ag->id);

            }

        }


        return $request->all();
    }


    public function changeName(Request $request){

        $this->viewprofile->update([
            'profile_name'=>$request->get('name')
        ],$request->get('id'));


        if($request->get('entity')['id']=='content') {

            foreach (Content::where('suffix', $request->get('suffix'))->get() as $k => $ct) {

                $std_base_ct = \GuzzleHttp\json_decode($ct->params);
                $std_base_ct->name = $request->get('name');
                $this->content->update([
                    'params' => \GuzzleHttp\json_encode($std_base_ct)
                ], $ct->id);

            }

        }


        if($request->get('entity')['id']=='agenda') {


            foreach (Agenda::where('suffix', $request->get('suffix'))->get() as $k => $ag) {

                $std_base_ag = \GuzzleHttp\json_decode($ag->params);
                $std_base_ag->name = $request->get('name');
                $this->agenda->update([
                    'params' => \GuzzleHttp\json_encode($std_base_ag)
                ], $ag->id);

            }

        }


        return $request->all();

    }


    public function checkSuffix(Request $request){

        if(ViewProfile::where('suffix',$request->get('suffix'))->where('suffix', '!=', $request->get('old_suffix'))->count()>0){
            return response('{"success":false}', 200, ['Content-Type'=>'application/json']);
        }else{
            return response('{"success":true}', 200, ['Content-Type'=>'application/json']);
        }


    }


    public function changeSuffix(Request $request){

        $old_suffix = $this->viewprofile->find($request->get('id'))->suffix;

        $this->viewprofile->update([
            'suffix'=>$request->get('suffix')
        ],$request->get('id'));


        if($request->get('entity')['id']=='content') {

            foreach (Content::where('suffix', $old_suffix)->get() as $k => $ct) {

                $this->content->update([
                    'suffix' => $request->get('suffix')
                ], $ct->id);

            }

        }


        if($request->get('entity')['id']=='agenda') {


            foreach (Agenda::where('suffix', $old_suffix)->get() as $k => $ag) {

                $this->agenda->update([
                    'suffix' => $request->get('suffix')
                ], $ag->id);

            }

        }


        return $request->all();

    }


}
