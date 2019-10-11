<?php
/**
 * Created by PhpStorm.
 * User: rradecki
 * Date: 2017-04-27
 * Time: 14:58
 */

namespace App\Http\Controllers;

use App\Entities\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

    class OldBaseController extends Controller
    {

        public function index(Request $request){

            $language = [
                'name'=>LaravelLocalization::getCurrentLocaleName(),
                'tag'=>LaravelLocalization::getCurrentLocale(),
                'id'=>Language::where('tag',LaravelLocalization::getCurrentLocale())->first()->id
            ];
            ;

            $content = view('admin.tests.oldbase');

            return view('admin.homemaster',
                [
                    'content'=>$content,
                    'controller'=>'admin/tests/oldbase.controller.js',
                    'lang'=>$language,
                    'languages'=>LaravelLocalization::getSupportedLocales()
                ]
            );

        }


        public function getExhibitions(Request $request)
        {

    $query = "SELECT `wp_posts`.*,
                        `wp_term_relationships`.*,
                        `wp_terms`.*
                 FROM `wp_terms` 
                 JOIN `wp_term_relationships` ON `wp_terms`.`term_id`=`wp_term_relationships`.`term_taxonomy_id`
                 JOIN `wp_posts` ON `wp_term_relationships`.`object_id`=`wp_posts`.`ID`
                 WHERE `wp_terms`.`name`='Wystawy' AND `wp_posts`.`post_status`='publish'";


        $resp =  DB::connection('mysql2')->select($query);
        return response($resp);
        }


/*        public function getItems($type)
        {
            $op = $type == 'event' ? '<>' : '=';

            $query = "SELECT DISTINCT `wp_posts`.*,
                        `wp_postmeta`.*
                      FROM `wp_posts`
                 LEFT JOIN `wp_postmeta` ON `wp_posts`.`ID`=`wp_postmeta`.`post_id`
                 LEFT JOIN `wp_term_relationships` ON `wp_term_relationships`.`object_id`=`wp_posts`.`ID`
                 LEFT JOIN `wp_terms` ON `wp_term_relationships`.`term_taxonomy_id`=`wp_terms`.`term_id`
                 WHERE (`wp_postmeta`.`meta_key`='scope_date_start'
                 OR `wp_postmeta`.`meta_key`='scope_date_end')
                 AND `wp_terms`.`name`".$op."'Wystawy'
                 AND `wp_posts`.`post_status`='publish'";


            $resp =  DB::connection('mysql2')->select($query);
            return response($resp);
        }*/


/*        public function getItems($type)
        {
            $op = $type == 'event' ? '<>' : '=';

            $query = "SELECT DISTINCT `wp_posts`.*,
                        `wp_postmeta`.*
                 FROM `wp_posts`
                 LEFT JOIN `wp_postmeta` ON `wp_posts`.`ID`=`wp_postmeta`.`post_id`
                 LEFT JOIN `wp_term_relationships` ON `wp_term_relationships`.`object_id`=`wp_posts`.`ID`
                 LEFT JOIN `wp_terms` ON `wp_term_relationships`.`term_taxonomy_id`=`wp_terms`.`term_id`
                 WHERE (`wp_postmeta`.`meta_key`='scope_date_start'
                 OR `wp_postmeta`.`meta_key`='scope_date_end')
                 AND `wp_terms`.`name`".$op."'Wystawy'
                 AND `wp_posts`.`post_status`='publish'";


            $resp =  DB::connection('mysql2')->select($query);
            return response($resp);
        }*/


        public function getItems($type)
        {
            $op = $type == 'event' ? '<>' : '=';
            $typ = $type == 'event' ? 'Wydarzenia' : 'Wystawy';

            $query = "SELECT DISTINCT post.ID AS post_id,
                                      post.post_content AS post_content,
                                      post.post_title AS post_title,
                                      post.post_excerpt AS post_excerpt,
                                      post.post_parent AS post_parent,
                                      st.meta_value AS start,
                                      en.meta_value AS ended,
                                      thumb.meta_value AS thmb,
                                      thumbnail.guid AS picture,
                                      attach.ID AS attach_id,
                                      attach.post_parent AS attach_parent,
                                      attach.post_type AS attach_type,
                                      attach.post_mime_type AS attach_mime,
                                      attach.post_name AS attach_name,
                                      attach.guid AS attach_guid                                                      
                 FROM wp_posts AS post
                 LEFT JOIN wp_posts AS attach ON post.ID=attach.post_parent
                 LEFT JOIN wp_postmeta AS st ON post.ID=st.post_id AND st.meta_key='scope_date_start'
                 LEFT JOIN wp_postmeta AS en ON post.ID=en.post_id AND en.meta_key='scope_date_end'
                 LEFT JOIN wp_postmeta AS thumb ON post.ID=thumb.post_id 
                 AND thumb.meta_key='_thumbnail_id'
                 LEFT JOIN wp_posts AS thumbnail ON thumbnail.ID=thumb.meta_value
                 LEFT JOIN wp_term_relationships AS termrel ON termrel.object_id=post.ID
                 LEFT JOIN wp_terms AS term ON termrel.term_taxonomy_id=term.term_id
                 WHERE st.meta_value<>'0000-00-00'
                 AND term.name".$op."'Wystawy'
                 AND post.post_status='publish' ORDER BY st.meta_value DESC";


            $resp =  DB::connection('mysql2')->select($query);
            $collection = $this->refactorItemsGroup($resp);
            $collection = array_values($collection);
//            dd($collection);
//            dd($resp);
//            return response($resp);
            return response($collection);
        }


        public function getEvents($date)
        {
            $query = "SELECT DISTINCT post.ID AS post_id,
                                      post.post_content AS post_content,
                                      post.post_title AS post_title,
                                      post.post_excerpt AS post_excerpt,
                                      post.post_parent AS post_parent,
                                      st.meta_value AS start,
                                      en.meta_value AS ended,
                                      thumb.meta_value AS thmb,
                                      thumbnail.guid AS picture,
                                      attach.ID AS attach_id,
                                      attach.post_parent AS attach_parent,
                                      attach.post_type AS attach_type,
                                      attach.post_mime_type AS attach_mime,
                                      attach.post_name AS attach_name,
                                      attach.guid AS attach_guid                                                      
                 FROM wp_posts AS post
                 LEFT JOIN wp_posts AS attach ON post.ID=attach.post_parent
                 LEFT JOIN wp_postmeta AS st ON post.ID=st.post_id AND st.meta_key='scope_date_start'
                 LEFT JOIN wp_postmeta AS en ON post.ID=en.post_id AND en.meta_key='scope_date_end'
                 LEFT JOIN wp_postmeta AS thumb ON post.ID=thumb.post_id 
                 AND thumb.meta_key='_thumbnail_id'
                 LEFT JOIN wp_posts AS thumbnail ON thumbnail.ID=thumb.meta_value
                 LEFT JOIN wp_term_relationships AS termrel ON termrel.object_id=post.ID
                 LEFT JOIN wp_terms AS term ON termrel.term_taxonomy_id=term.term_id
                 /*WHERE st.meta_value>=now()*/
                 WHERE st.meta_value>='".$date."'
                 AND term.name<>'Wystawy'
                 AND post.post_status='publish' ORDER BY st.meta_value DESC";


            $resp =  DB::connection('mysql2')->select($query);
            $collection = $this->refactorItemsGroup($resp);
            $collection = array_values($collection);
//            dd($collection);
//            dd($resp);
//            return response($resp);
            return response($collection);
        }


        private function refactorItemsGroup($items){

            $collection = [];

            foreach($items as $key=>$item){

                if(!array_key_exists($item->post_id,$collection)){
                    $collection[$item->post_id] = new \stdClass();
                    $collection[$item->post_id]->title = $item->post_title;
                    $collection[$item->post_id]->content = $item->post_content;
                    $collection[$item->post_id]->begin = $item->start;
                    $collection[$item->post_id]->end = $item->ended;
                    $collection[$item->post_id]->picture = $item->picture;
                    $collection[$item->post_id]->picture_full_content = $item->attach_guid;
                    $collection[$item->post_id]->gallery = [];
                    $collection[$item->post_id]->countgroup = 1;
                }else{

                    $attach = new \stdClass();
                    $attach->mime = $item->attach_mime;
                    $attach->name = $item->attach_name;
                    $attach->file = $item->attach_guid;

                    array_push(
                        $collection[$item->post_id]->gallery,
                        $attach
                        );
                    $collection[$item->post_id]->countgroup++;

                }


            }

            return $collection;

        }


        public function getEditions(Request $request)
        {


            $query = "SELECT DISTINCT post.ID as post_id,
                                      post.post_content as post_content,
                                      post.post_title as post_title,
                                      post.post_excerpt as post_excerpt,
                                      attach.ID as attach_id,
                                      attach.post_parent as attach_parent, 
                                      attach.post_type as attach_type,
                                      attach.post_mime_type as attach_mime,
                                      attach.post_name as attach_name,
                                      attach.guid as attach_guid 
                      FROM wp_posts AS post 
                      LEFT JOIN wp_posts AS attach ON post.ID=attach.post_parent                       
                      WHERE post.post_type='bookstore_item'";


            $resp =  DB::connection('mysql2')->select($query);
            $collection = $this->refactorContents($resp);
//            dd($resp);
            $collection = array_values($collection);
//            dd($collection);
            return response($collection);
            return response($resp);
        }


        private function refactorContents($items){

            $collection = [];

            foreach($items as $key=>$item){

                if(!array_key_exists($item->post_id,$collection)){

                    $collection[$item->post_id] = new \stdClass();
                    $collection[$item->post_id]->title = $item->post_title;
                    $collection[$item->post_id]->intro = $item->post_excerpt;
                    $collection[$item->post_id]->content = $item->post_content;
                    $collection[$item->post_id]->picture = $item->attach_guid;
                    $collection[$item->post_id]->gallery = [];
                    $collection[$item->post_id]->countgroup = 1;


                }else{

                    $attach = new \stdClass();
                    $attach->mime = $item->attach_mime;
                    $attach->name = $item->attach_name;
                    $attach->file = $item->attach_guid;

                    array_push(
                        $collection[$item->post_id]->gallery,
                        $attach
                    );
                    $collection[$item->post_id]->countgroup++;

                }

            }

            return $collection;

        }

}