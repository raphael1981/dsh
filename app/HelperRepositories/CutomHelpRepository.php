<?php

namespace HelperRepositories;

use App\Entities\Agenda;
use App\Entities\Content;
use App\Entities\Language;
use Illuminate\Support\Facades\DB;

class CustomHelpRepository{

    public function getElementsContentsAgendasByFrase($frase){

        $contents = Content::where('title', 'LIKE', '%'.$frase.'%')->get();
        $agendas = Agenda::where('title', 'LIKE', '%'.$frase.'%')->get();

        $std = new \stdClass();
        $std->contents = [];
        $std->agendas = [];

        foreach($contents as $ct){
            $to_add = $ct;
            $to_add->language = Language::find($ct->language_id);
            $to_add->entity = 'App\\Entities\\Content';
            $to_add->entity_type = 'contents';
            array_push($std->contents, $to_add);
        }

        foreach($agendas as $ag){
            $to_add = $ag;
            $to_add->language = Language::find($ag->language_id);
            $to_add->entity = 'App\\Entities\\Agenda';
            $to_add->entity_type = 'agendas';
            array_push($std->agendas, $to_add);
        }

        return \GuzzleHttp\json_encode($std);

    }

    public function createModelToLinkParams($id, $entity){

        switch($entity){

            case 'App\Entities\Agenda':

                $data = $entity::find($id);
                $data->language = Language::find($data->language_id);
                $data->entity = $entity;
                $data->entity_type = 'agendas';

                break;

            case 'App\Entities\Content':

                $data = $entity::find($id);
                $data->language = Language::find($data->language_id);
                $data->entity = $entity;
                $data->entity_type = 'contents';

                break;

        }


        return $data;

    }

}