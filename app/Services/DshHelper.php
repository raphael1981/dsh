<?php

namespace App\Services;


class DshHelper{

    public static function getMonthNumberFromString($string){

        $ex = explode('-', $string);
        return $ex[1];

    }

    public static function getDayNumberFromString($string){

        $ex = explode('-', $string);
        return $ex[2];

    }

    public static function getYearNumberFromString($string){

        $ex = explode('-', $string);
        return $ex[0];

    }


    public static function getMonthFromDateTime($string){

        $ex1 = explode(' ', $string);
        $ex = explode('-', $ex1[0]);

        return $ex[1];

    }

    public static function getDayFromDateTime($string){

        $ex1 = explode(' ', $string);
        $ex = explode('-', $ex1[0]);

        return $ex[2];

    }

    public static function getYearFromDateTime($string){

        $ex1 = explode(' ', $string);
        $ex = explode('-', $ex1[0]);

        return $ex[0];

    }


    public static function getMonthNumberFromStringTimestamp($string){

        $trm_date = explode(' ',$string);

        $ex = explode('-', $trm_date[0]);
        return $ex[1];

    }

    public static function getDayNumberFromStringTimestamp($string){

        $trm_date = explode(' ',$string);

        $ex = explode('-', $trm_date[0]);
        return $ex[2];

    }

    public static function getYearNumberFromStringTimestamp($string){

        $trm_date = explode(' ',$string);

        $ex = explode('-', $trm_date[0]);
        return $ex[0];

    }


    public static function trimDescByWords($text, $words=5){

        $text = strip_tags($text);

        $txt = '';

        $exp = explode(' ',$text);

        $iter_count = 0;

        if(count($exp)>=$words){
            $iter_count=$words;
        }else{
            $iter_count=count($exp);
        }

        for($i=0;$i<$iter_count;$i++){


            if($i==0){

                $txt .= $exp[$i];

            }else{

                $txt .= ' '.$exp[$i];

            }

        }

        return $txt;

    }




    public static function makeFrontUrl($string){

        return env('APP_URL').'/'.$string;

    }

    public static function refactorDateForLangFromTimestamp($data, $lang){

        $std = new \stdClass();

        if($lang=='pl'){
            $expl = explode(' ',$data);
            $date = explode('-',$expl[0]);
            $std->date = $date[2].'.'.$date[1].'.'.$date[0];
            $time = explode(':',$expl[1]);
            $std->time = $time[0].':'.$time[1];
        }else{
            $expl = explode(' ',$data);
            $date = explode('-',$expl[0]);
            $std->date = $date[0].'.'.$date[1].'.'.$date[2];
            $time = explode(':',$expl[1]);
            $std->time = $time[0].':'.$time[1];
        }

        return $std;

    }

    public static function refactorDateForLangFromDay($data, $lang){

        $std = new \stdClass();

        if($lang=='pl'){
            $date = explode('-',$data);
            $std->date = $date[2].'.'.$date[1].'.'.$date[0];
        }else{
            $date = explode('-',$data);
            $std->date = $date[2].'.'.$date[1].'.'.$date[0];
        }

        return $std;
    }


    public static function makeTimeFromString($data){

        $ex = explode(':', $data);

        return $ex[0].':'.$ex[1];

    }


    public static function makeRegexSuffixFromCollection($coll){

        $rgs = '(';

        foreach($coll as $k=>$sf){
            if(end($coll)!=$sf){
                $rgs .= $sf->suffix.'|';
            }else{
                $rgs .= $sf->suffix;
            }
        }

        $rgs .= ')';

        return $rgs;

    }

    public static function parseSingleUrlContent($string,$entity){

        $std = new \stdClass();

        $ex = explode('-',$string);
        $last = explode(',',end($ex));
        $std->id = $ex[0];
        $std->suffix = end($last);

        if(is_null($entity::find($std->id))){
            return null;
        }

        $std->single = $entity::find($std->id);
        $std->viewprofile = \GuzzleHttp\json_decode($std->single->params);
        $std->categories = $std->single->categories()->get();
        $std->attachments = [];

        foreach($std->single->medias()->get() as $k=>$attach){

            $attach->params = \GuzzleHttp\json_decode($attach->params);
            array_push($std->attachments,$attach);

        }

        $std->galleries = [];
        foreach($std->single->galleries()->get() as $k=>$gallery){
            $el = new \stdClass();
            $el->gallery = $gallery;
            $el->pictures = [];

            $pics = $gallery->pictures();

            if($pics->count()>8){
                $el->is_more_open = true;
                $el->plus = $pics->count()-8;
            }else{
                $el->is_more_open = false;
                $el->plus = null;
            }

            foreach($pics->get() as $i=>$p){

                $p->real_url = self::createUrlFromDiskParams($p->image_name,$p->disk,$p->image_path);
                $p->size = getimagesize(storage_path().'/app/'.$p->real_url);
                $p->size_to_gallery = $p->size[0].'x'.$p->size[1];
                $p->translations = \GuzzleHttp\json_decode($p->translations);
                array_push($el->pictures,$p);

            }


            array_push($std->galleries, $el);
        }

        return $std;

    }

    public static function parseSingleUrlAgenda($string,$entity){


        $std = new \stdClass();

        $ex = explode('-',$string);
        $last = explode(',',end($ex));
        $std->id = $ex[0];
        $std->suffix = end($last);

        if(is_null($entity::find($std->id))){
            return null;
        }

        $std->single = $entity::find($std->id);
        $std->categories = $std->single->categories()->get();
        $std->viewprofile = \GuzzleHttp\json_decode($std->single->params);
        $std->attachments = [];

        foreach($std->single->medias()->get() as $k=>$attach){

            $attach->params = \GuzzleHttp\json_decode($attach->params);
            array_push($std->attachments,$attach);

        }
        $std->galleries = [];

        foreach($std->single->galleries()->get() as $k=>$gallery){
            $el = new \stdClass();
            $el->gallery = $gallery;
            $el->pictures = [];

            $pics = $gallery->pictures();


            if($pics->count()>8){
                $el->is_more_open = true;
                $el->plus = $pics->count()-8;
            }else{
                $el->is_more_open = false;
                $el->plus = null;
            }

            foreach($pics->get() as $i=>$p){

                $p->real_url = self::createUrlFromDiskParams($p->image_name,$p->disk,$p->image_path);
                $p->size = getimagesize(storage_path().'/app/'.$p->real_url);
                $p->size_to_gallery = $p->size[0].'x'.$p->size[1];
                $p->translations = \GuzzleHttp\json_decode($p->translations);
                array_push($el->pictures,$p);

            }

            array_push($std->galleries, $el);
        }

        return $std;



    }



    public static function parseSingleUrlPublication($string,$entity){

        $std = new \stdClass();

        $ex = explode('-',$string);
        $last = explode(',',end($ex));
        $std->id = $ex[0];
        $std->suffix = end($last);

        if(is_null($entity::find($std->id))){
            return null;
        }

        $std->single = $entity::find($std->id);
        $std->viewprofile = \GuzzleHttp\json_decode($std->single->params);
        $std->attachments = [];

        foreach($std->single->medias()->get() as $k=>$attach){

            $attach->params = \GuzzleHttp\json_decode($attach->params);
            array_push($std->attachments,$attach);

        }

        return $std;

    }


    public static function createUrlFromDiskParams($name,$disk,$path){

        $npath = '';

        if($path==':'){

            $npath .= '/'.$disk;

        }else{

            $npath .= '/'.$disk;

            $ex = explode(':',$path);
            for($i=1;$i<count($ex);$i++){

                $npath .= '/'.$ex[$i];

            }

        }

        return $npath.'/'.$name;

    }


    public static function cutTitleBySigns($title, $signs){

        $string = '';

        $len = strlen($title);
        if($len>$signs){
            $remlen = $len-$signs;
            $exfull = explode(' ',$title);
            $exshort = explode(' ', substr($title, 0,-($remlen-1)));

            $words = count($exshort);
            if(strlen($exfull[$words-1])==strlen($exshort[$words-1])){
                foreach($exshort as $key=>$word){
                    if($key==0){
                        $string .= $word;
                    }else{
                        $string .= ' '.$word;
                    }
                }
                $string.='...';
            }else{
                for($i=0;$i<(count($exshort)-1);$i++){
                    if($i==0){
                        $string .= $exshort[$i];
                    }else{
                        $string .= ' '.$exshort[$i];}
                }
                $string .='...';
            }
        }else{
            $string = $title;
        }
        return $string;

    }
    public static function addToFile($filename,$content){
        if(file_exists('../storage/app/public/'.$filename)){
            file_put_contents('../storage/app/public/'.$filename, $content,FILE_APPEND);
        }else{
            file_put_contents('../storage/app/public/'.$filename, "\n".$content);
        }
    }

}