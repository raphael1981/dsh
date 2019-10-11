<?php

use App\Entities\Link;
use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


$domains = config('services')['domains'];

Route::group(['https'], function() use($domains) {


});


//Route::middleware(['https.protocol'])->group(function () use($domains) {


    Route::group(['domain' => $domains['admin']], function () use ($domains) {


        /*
         * Prefix api wyznacza routes nie związane za adreami cms
         */
        Route::put('{prefix}/member/newsletter/subscribe',
            ['uses' => 'Front\NewsletterController@subscribeNewsleterMember', 'as' => 'subscribe-preview'])
            ->where('prefix','^api$');
        Route::get('{prefix}/newsletter/unsubscribe/{token}',
            ['uses' => 'Front\NewsletterController@unsubscribeNewsletterMember', 'as' => 'unsubscribe-preview'])
            ->where('prefix','^api$');
        Route::post('{prefix}/check/is/member/email/newsletter',
            ['uses' => 'Front\NewsletterController@checkIsEmailInBaseForNewsletter', 'as' => 'check_is_member-preview'])
            ->where('prefix','^api$');
        Route::get('{prefix}/newsletter/confirm/{token}',
            ['uses' => 'Front\NewsletterController@subscribeConfirm', 'as' => 'subscribe_confirm-preview'])
            ->where('prefix','^api$');



        Route::get('{prefix}', function($prefix){
            abort(404, 'Route nie może być widokiem');
        })->where('prefix','^api$');
        /*
         * Prefix api wyznacza routes nie związane za adreami cms
         */


        /*
        * Filter Category Ajax
        */

        Route::get('{prefix}/get/link/data/{linkid}',
            ['uses' => 'Front\Ajax\FilterCategoryController@getLinkFilters', 'as'=>'link-data-preview'])
            ->where('prefix','^api$');

        Route::post('{prefix}/get/filter/agenda/result',
            ['uses' => 'Front\Ajax\FilterCategoryController@getFilterResults', 'as'=>'agenda-filter-preview'])
            ->where('prefix','^api$');

        Route::post('{prefix}/get/filter/content/result',
            ['uses' => 'Front\Ajax\FilterCategoryController@getFilterContentResults', 'as'=>'content-filter-preview'])
            ->where('prefix','^api$');

        Route::post('{prefix}/get/filter/content/noyear/result',
            ['uses' => 'Front\Ajax\FilterCategoryController@getFilterContentResultsNoYear', 'as'=>'noyear-filter-preview'])
            ->where('prefix','^api$');

        /*
         * Filter Category Ajax
         */


        /*
        * Filter Many List
        */


        Route::get('{prefix}/get/link/data/many/{id}',
            ['uses' => 'Front\Ajax\ManyListController@getLinkData', 'as'=>'get-many-data-preview'])
            ->where('prefix','^api$');


        /*
         * Filter Many List
         */


        /*
        * Filter Leaf
        */

        Route::get('{prefix}/link/links/deeper/{id}',
            ['uses' => 'Front\Ajax\FilterLeafListController@getLinkLeafs', 'as'=>'get-links-deeper-preview'])
            ->where('prefix','^api$');

        Route::post('{prefix}/get/filter/leaf/result',
            ['uses' => 'Front\Ajax\FilterLeafListController@getLeafFilterData', 'as'=>'leaf-result-preview'])
            ->where('prefix','^api$');

        /*
        * Filter Leaf
        */



        /*
         * Data get
        */

        Route::get('{prefix}/get/month/names/by/lang/{tag}', function($prefix, $tag){
            App::setLocale($tag);
            return Lang::get('translations');
        })->where('prefix','^api$');

        /*
         * Data get
         */



        ///////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////LivePreview////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////


        Route::get('/administrator/livepreview/', ['uses' => 'Front\HomePageController@index', 'as' => 'homepage-preview'])->middleware('auth', 'livepreview');


        $suffix_agendas = \App\Entities\ViewProfile::where('type','agenda')->get();
        $suffix_content = \App\Entities\ViewProfile::where('type','content')->get();

        $reg_agendas = \App\Services\DshHelper::makeRegexSuffixFromCollection($suffix_agendas);
        $reg_content = \App\Services\DshHelper::makeRegexSuffixFromCollection($suffix_content);

        /*
         * CMS
         */

        Route::get('/administrator/livepreview/{singleslugagenda}', ['uses' => 'Front\CmsController@indexSingleAgenda', 'as' => 'single-slug-agenda-preview'])
            ->where('singleslugagenda','[a-z0-9-]*,('.$reg_agendas.')');

        Route::get('/administrator/livepreview/{singleslugcontent}', ['uses' => 'Front\CmsController@indexSingleContent', 'as' => 'content-slug-agenda-preview'])
            ->where('singleslugcontent','[a-z0-9-]*,('.$reg_content.')');

        Route::get('/administrator/livepreview/'.LaravelLocalization::transRoute('routes.publication'), 'Front\CmsController@indexSinglePublication')
            ->where('singleslugpublication','[a-z0-9-]*,[a-z]*');

        Route::get('/administrator/livepreview/{primary}', ['uses' => 'Front\CmsController@indexPrimaryLink', 'as' => 'primary-preview'])
            ->where('primary','[a-z0-9-]*')->middleware('auth', 'livepreview');

        Route::get('/administrator/livepreview/{primary}/{last}', ['uses' => 'Front\CmsController@indexDeeperLink', 'as' => 'primary-last-preview'])
            ->where('primary','[a-z0-9-]*')
            ->where('last','[a-z0-9-]*');

        /////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////LivePreview//////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////

        ////////////////////////////////LeadScene Ajax/////////////////////////////////////

        Route::post('/administrator/get/leadscenes', 'Admin\Super\LeadSceneController@getLeadScenes');
        Route::post('/administrator/get/current/leadscene', 'Admin\Super\LeadSceneController@getCurrentLeadScene');
        Route::put('/update/leadscene/name', 'Admin\Super\LeadSceneController@updateLeadSceneName');
        Route::delete('/administrator/delete/lead/scene/{id}', 'Admin\Super\LeadSceneController@deleteLeadScene');
        Route::get('/administrator/show/leadscene/{id}', 'Admin\Super\LeadSceneController@showPreviewLeadScene');
        Route::put('/change/content/data/update/other', 'Admin\Super\LeadSceneController@updateLeadSceneDateAndOther');
        Route::post('/upload/custom/leadscene/image', 'Admin\Super\LeadSceneController@uploadImageCustomView');
        Route::post('/upload/banner/leadscene/image', 'Admin\Super\LeadSceneController@uploadImageBannerView');
        Route::put('/add/new/homepage/structure', 'Admin\Super\LeadSceneController@createFrontPageStructure');
        Route::put('/update/current/homepage/structure', 'Admin\Super\LeadSceneController@updateCurrentFrontPageStructure');


        ////////////////////////////////LeadScene Ajax/////////////////////////////////////


        ///////////////////////////////Contents Ajax////////////////////////////////////////

        Route::post('/administrator/get/contents', 'Admin\Super\ContentsController@getContents');
        Route::put('/change/content/data', 'Admin\Super\ContentsController@changeData');
        Route::get('/get/full/content/data/{id}', 'Admin\Super\ContentsController@getFullData');
        Route::put('/create/new/content', 'Admin\Super\ContentsController@createNewContent');
        Route::put('/update/content/data', 'Admin\Super\ContentsController@updateContentData');

        ///////////////////////////////Contents Ajax////////////////////////////////////////


        ///////////////////////////////Links Ajax////////////////////////////////////////

        Route::put('/update/links/tree', 'Admin\Super\LinksController@updateTree');
        Route::get('/get/link/by/id/{id}', 'Admin\Super\LinksController@getLinkById');
        Route::get('/get/link/icon/{id}', 'Admin\Super\LinksController@getLinkIconAndLinkData');
        Route::get('/get/color/if/exist/{id}', 'Admin\Super\LinksController@getLinkColorndLinkData');
        Route::post('/check/is/name/on/level', 'Admin\Super\LinksController@checkIsNameOnLevel');
        Route::put('/set/new/order/link/data', 'Admin\Super\LinksController@setNewItemsOrder');
        Route::post('/fast/find/singel/data', 'Admin\Super\LinksController@fastFindSingleData');

        Route::put('/change/set/link/icon', 'Admin\Super\LinksController@setChangeIconLink');

        Route::put('/change/set/link/color', 'Admin\Super\LinksController@setChangeColorLink');

        Route::post('/get/config/data/single/{id}', 'Admin\Super\LinksController@getDataConfigSingle');
        Route::post('/get/config/data/filtered/{id}', 'Admin\Super\LinksController@getDataConfigFiltered');
        Route::post('/get/config/data/many/{id}', 'Admin\Super\LinksController@getDataConfigMany');
        Route::post('/get/config/data/unstandard/{id}', 'Admin\Super\LinksController@getDataConfigUnstandard');
        Route::post('/get/config/data/filterleaf/{id}', 'Admin\Super\LinksController@getDataConfigFilterLeaf');

        Route::put('/save/template/params', 'Admin\Super\LinksController@saveTemplateData');

        ///////////////////////////////Links Ajax////////////////////////////////////////


        ///////////////////////////////Categories Ajax///////////////////////////////////

        Route::post('/administrator/get/categories', 'Admin\Super\CategoriesController@getCategories');
        Route::get('/categories/params/get', 'Admin\Super\CategoriesController@getCategoryParams');
        Route::put('/create/new/category', 'Admin\Super\CategoriesController@createNewCategory');
        Route::put('/update/category', 'Admin\Super\CategoriesController@updateCategory');
        Route::put('/check/is/category', 'Admin\Super\CategoriesController@checkIsCategory');
        Route::put('/check/is/category/except/current', 'Admin\Super\CategoriesController@checkIsCategoryExceptCurrent');
        Route::get('/get/category/full/data/{id}', 'Admin\Super\CategoriesController@getCategoryFullData');

        ///////////////////////////////Categories Ajax///////////////////////////////////

        ///////////////////////////////Agendas Ajax//////////////////////////////////////

        Route::post('/administrator/get/agendas', 'Admin\Super\AgendasController@getAgendas');
        Route::put('/administrator/fast/place/create', 'Admin\Super\AgendasController@fastPlaceCreate');
        Route::put('/create/new/agenda', 'Admin\Super\AgendasController@createNewAgenda');
        Route::get('/get/full/agenda/data/{id}', 'Admin\Super\AgendasController@getFullData');
        Route::put('/update/agenda/data', 'Admin\Super\AgendasController@updateAgendaData');
        Route::put('/change/agenda/data', 'Admin\Super\AgendasController@changeAgendaData');
        Route::post('/get/rotors/by/phrase','Admin\Super\AgendasController@getRotorsByPhrase');

        ///////////////////////////////Agendas Ajax//////////////////////////////////////

        //////////////////////////////Publications Ajax//////////////////////////////////

        Route::post('/administrator/get/publications', 'Admin\Super\PublicationsController@getPublications');
        Route::put('/create/new/publication', 'Admin\Super\PublicationsController@createNewPublication');
        Route::put('/update/publication/data', 'Admin\Super\PublicationsController@updatePublicationData');
        Route::put('/change/publication/data', 'Admin\Super\PublicationsController@changePublicationDataField');
        Route::get('/get/full/publication/data/{id}', 'Admin\Super\PublicationsController@getFullData');


        //////////////////////////////Publications Ajax//////////////////////////////////

        ///////////////////////////////Galleries Ajax//////////////////////////////////////

        Route::post('/administrator/get/galleries', 'Admin\Super\GalleriesController@getGalleries');
        Route::put('/create/new/gallery', 'Admin\Super\GalleriesController@createNewGallery');
        Route::put('/update/gallery/{id}', 'Admin\Super\GalleriesController@updateGallery');
        Route::get('/get/full/gallery/data/{id}', 'Admin\Super\GalleriesController@getFullDataGallery');

        //////////////////////////////Galleries Ajax//////////////////////////////////////


        ///////////////////////////////Medias Ajax//////////////////////////////////////

        Route::post('/administrator/get/media', 'Admin\Super\MediaController@getMedias');
        Route::post('/administrator/trash/file', 'Admin\Super\MediaController@trashFile');

        //////////////////////////////Medias Ajax//////////////////////////////////////

        ///////////////////////////////Members Ajax//////////////////////////////////////

        Route::post('/administrator/get/members', 'Admin\Super\MembersController@getMembers');
        Route::put('/change/member/data', 'Admin\Super\MembersController@changeData');

        //////////////////////////////Members Ajax//////////////////////////////////////


        /////////////////////View Profile Ajax/////////////////////////////////////////

        Route::put('/change/vie/profile/color', 'Admin\Super\ViewProfilesController@changeColor');
        Route::put('/change/vie/profile/icon', 'Admin\Super\ViewProfilesController@changeIcon');
        Route::put('/change/vie/profile/name', 'Admin\Super\ViewProfilesController@changeName');
        Route::put('/check/is/suffix/exits', 'Admin\Super\ViewProfilesController@checkSuffix');
        Route::put('/change/vie/profile/suffix', 'Admin\Super\ViewProfilesController@changeSuffix');

        /////////////////////View Profile Ajax/////////////////////////////////////////


        ///////////////////////////////Logotypes Ajax//////////////////////////////////////

        Route::post('/administrator/get/logotypes', 'Admin\Super\LogotypesController@getLogotypes');
        Route::get('/get/logotypes/record/{id}', 'Admin\Super\LogotypesController@getLogotypesOne');
        Route::post('/update/logotypes/data', 'Admin\Super\LogotypesController@updateData');
        Route::post('/create/logotypes', 'Admin\Super\LogotypesController@createLogotype');

        //////////////////////////////Logotypes Ajax//////////////////////////////////////


        //////////////////////////////Groups Ajax//////////////////////////////////////

        Route::post('/administrator/get/groups', 'Admin\Super\GroupsController@getGroups');

        //////////////////////////////Groups Ajax//////////////////////////////////////


        //////////////////////////////Newsletter//////////////////////////////////////

        Route::post('/administrator/get/members', 'Admin\Super\NewsletterController@getMembers');
        Route::put('/administrator/update/member/field', 'Admin\Super\NewsletterController@updateMemberField');
        Route::delete('/delete/member/element/{id}', 'Admin\Super\NewsletterController@deleteMember');
        Route::get('/administrator/export/members', 'Admin\Super\NewsletterController@exportMembers');

        //////////////////////////////Newsletter//////////////////////////////////////


        /////////////////////////////Resources Jobs///////////////////////////////////////

        Route::post('/resources/upload/images', 'Resources\AjaxController@uploadImages');
        Route::post('/resources/upload/images/cropped', 'Resources\AjaxController@uploadImagesCropped');
        Route::post('/resources/upload/files', 'Resources\AjaxController@uploadFiles');
        Route::put('/create/media/in/path', 'Resources\AjaxController@makeDir');
        Route::post('/upload/media/to/folder', 'Resources\AjaxController@uploadMediaFiles');
        Route::post('/resources/upload/logotype', 'Resources\AjaxController@uploadLogotype');


        /////////////////////////////Resources Jobs///////////////////////////////////////

        /////////////////////////////Pdfs Program////////////////////////////////////////
        Route::post('/upload/program/pdf', 'Resources\AjaxController@uploadProgramPdf');
        /////////////////////////////Pdfs Program////////////////////////////////////////

        Route::group(
            [
                'prefix' => LaravelLocalization::setLocale(),
                'middleware' => [ 'localeSessionRedirect', 'localizationRedirect' ]
            ], function()
        {

            ////////////////////////////////////////////////////////////////////////////

            Route::get('/',['uses' => 'Auth\LoginController@showLoginForm', 'as' => 'login']);
            Route::post('/login',['uses' => 'Auth\LoginController@login']);
            Route::post('/logout',['uses' => 'Auth\LoginController@logout', 'as' => 'logout']);


            //////////////////////////////////////////////////////////////////////////////


            Route::get('/administrator', 'Admin\Super\FrontController@index');
            Route::get('/administrator/homepage', 'Admin\Super\LeadSceneController@index');
            Route::get('/administrator/links', 'Admin\Super\LinksController@index');
            Route::get('/administrator/contents', 'Admin\Super\ContentsController@index');
            Route::get('/administrator/publications', 'Admin\Super\PublicationsController@index');
            Route::get('/administrator/agendas', 'Admin\Super\AgendasController@index');
            Route::get('/administrator/categories', 'Admin\Super\CategoriesController@index');
            Route::get('/administrator/groups', 'Admin\Super\GroupsController@index');
            Route::get('/administrator/members', 'Admin\Super\MembersController@index');
            Route::get('/administrator/media', 'Admin\Super\MediaController@index');
            Route::get('/administrator/youtube', 'Admin\Super\YoutubeController@index');
            Route::get('/administrator/galleries', 'Admin\Super\GalleriesController@index');
            Route::get('/administrator/pictures', 'Admin\Super\PicturesController@index');
            Route::get('/administrator/slides', 'Admin\Super\SlidesController@index');
            Route::get('/administrator/newsletter', 'Admin\Super\NewsletterController@index');
            Route::get('/administrator/pdfprogram', 'Admin\Super\PdfProgramController@index');
            Route::get('/administrator/viewprofiles', 'Admin\Super\ViewProfilesController@index');
            Route::get('/administrator/logotypes', 'Admin\Super\LogotypesController@index');


            ///////////////////////////////////////////////////////////////////////////


        });


        Route::get('/pictures/folder/tree/{disk}', function($disk){

            return \ResourceRecursion\PictureRecursion\PictureRecursion::getResourceFolderTree($disk);

        })->middleware('auth');

        Route::get('/media/folder/tree/{disk}', function($disk){

            return \ResourceRecursion\MediaRecursion\MediaRecursion::getResourceFolderTree($disk);

        })->middleware('auth');

        Route::get('/media/folder/tree/level/{disk}', function($disk){

            return \ResourceRecursion\MediaRecursion\MediaRecursion::getResourceFolderTreeLevel($disk);

        })->middleware('auth');

        Route::put('/media/get/folder/{disk}', function($disk, \Illuminate\Http\Request $request){

            return \ResourceRecursion\MediaRecursion\MediaRecursion::getGetFolderMedia($disk, $request->all());

        })->middleware('auth');


        Route::get('/get/sections/icons', function(){

            return glob('sections_icons/*{.svg}', GLOB_BRACE);

        })->middleware('auth');

        Route::get('/get/all/colors', function(){

            return config('services')['template_colors'];

        })->middleware('auth');

        Route::get('/get/default/color', function(){

            return config('services')['default_template_color'];

        })->middleware('auth');

        Route::get('/get/color/and/icons', function(){

            $std = new stdClass();
            $std->icons = glob('sections_icons/*{.svg}', GLOB_BRACE);
            $std->colors = config('services')['template_colors'];

            return \GuzzleHttp\json_encode($std);

        })->middleware('auth');


        Route::get('/get/view/profiles/{lang_id}/{type}', function($lang_id, $type){


            return \App\Entities\Language::find($lang_id)->viewprofiles()->where('type',$type)->get();

        })->middleware('auth');


        Route::get('/links/tree/get/{langid}', function(\App\Repositories\LinkRepositoryEloquent $link, $langid){

            return $link->getTreeArrayTreeUi($langid);

        });

        Route::get('/tree/get/for/content/{langid}/{conid}', function(\App\Repositories\LinkRepositoryEloquent $link, $langid, $conid){

            return $link->getTreeArrayContentLinks($langid, $conid);

        })->middleware('auth');



        Route::get('/tree/empty/get/for/content/{langid}', function(\App\Repositories\LinkRepositoryEloquent $link, $langid){

            return $link->getTreeArrayContentLinksEmpty($langid);

        })->middleware('auth');


        Route::get('/tree/get/for/agenda/{langid}/{aid}', function(\App\Repositories\LinkRepositoryEloquent $link, $langid, $aid){

            return $link->getTreeArrayAgendaLinks($langid, $aid);

        })->middleware('auth');


        Route::get('/tree/empty/get/for/agenda/{langid}', function(\App\Repositories\LinkRepositoryEloquent $link, $langid){

            return $link->getTreeArrayAgendaLinksEmpty($langid);

        })->middleware('auth');


        Route::put('/pictures/get/folder/{disk}', function($disk, \Illuminate\Http\Request $request){

            return \ResourceRecursion\PictureRecursion\PictureRecursion::getGetFolderImages($disk, $request->all());

        })->middleware('auth');

        Route::put('/pictures/get/file/{disk}', function($disk, \Illuminate\Http\Request $request){

            return \ResourceRecursion\PictureRecursion\PictureRecursion::getImageInfo($disk, $request->all());

        })->middleware('auth');

        Route::post('/get/places/by/query', function(\Illuminate\Http\Request $request){

            return \App\Entities\Place::where('name', 'LIKE', '%'.$request->get('query').'%')->get();

        })->middleware('auth');

        Route::get('/get/all/categories', function(\App\Repositories\CategoryRepositoryEloquent $category){

            return $category->getCategoriesAndLinksRelationships();
            return \App\Entities\Category::all();

        })->middleware('auth');


        Route::get('/get/all/galleries', function(){

            return \App\Entities\Gallery::all();

        })->middleware('auth');


        Route::get('/get/all/templates', function(){

            $ar = [];

            foreach(\App\Entities\Template::all() as $k=>$t){
                $t->params = \GuzzleHttp\json_decode($t->params);
                array_push($ar, $t);
            }

            return $ar;

        })->middleware('auth');

        Route::get('/get/config/templates', function(){

            return config('template_config')['template_config_data'];

        })->middleware('auth');

        Route::get('/get/full/link/data/{id}', function($id){

            $link = Link::find($id);

            if(!is_null($link->description_links)){
                $link->description_links = \GuzzleHttp\json_decode($link->description_links);
            }

            return $link;

        })->middleware('auth');

        Route::get('/get/template/by/link/id/{id}', function($id){

            $tid = \App\Entities\Link::find($id)->template_id;

            if(is_null($tid)){
                return 0;
            }

            return \App\Entities\Template::where('id', $tid)->first();

        })->middleware('auth');

        Route::get('/get/gallery/by/id/{id}', function($id, \App\Repositories\GalleryRepositoryEloquent $gallery){

            return $gallery->getGalleryWithPicturesByIdFastView($id);

        })->middleware('auth');

        Route::get('/get/all/languages', function(){

            return LaravelLocalization::getSupportedLocales();

        })->middleware('auth');


        Route::get('/get/current/leadscene/data', function(\App\Repositories\LeadsceneRepositoryEloquent $leadscene){

            return $leadscene->findWhere(['active'=>1])->first();

        })->middleware('auth');

        Route::get('/get/leadscene/by/{id}', function($id, \App\Repositories\LeadsceneRepositoryEloquent $leadscene){

            return $leadscene->find($id);

        })->middleware('auth');



        Route::post('/get/all/pictures/by/criteria', function(\Illuminate\Http\Request $request, \App\Repositories\PictureRepositoryEloquent $picture){

            return $picture->searchByCriteriaFastFullData($request->all());

        })->middleware('auth');

        Route::post('/fast/find/agendas/by/title', function(\Illuminate\Http\Request $request, \App\Repositories\AgendaRepositoryEloquent $agenda){

            return $agenda->fastSearchByTitle($request->get('frase'));

        })->middleware('auth');


        Route::post('/fast/find/contents/by/title', function(\Illuminate\Http\Request $request, \App\Repositories\ContentRepositoryEloquent $content){

            return $content->fastSearchByTitle($request->get('frase'));

        })->middleware('auth');


        Route::post('/get/search/rotors', function(\Illuminate\Http\Request $request){

            $phrase = $request->get('phrase');

            return \App\Entities\Logotype::where(function($q) use ($phrase) {
                $q->where('name','LIKE','%'.$phrase.'%');
                $q->orWhere('rotor_title','LIKE','%'.$phrase.'%');
            })->get();

        })->middleware('auth');



        ////////////////////////////////////////////////DELETE ROUTES FUNCTIONS////////////////////////////////////////////////////////////////////////

        Route::delete('/delete/agenda/element/{id}', function($id){

            \App\Entities\Agenda::find($id)->links()->detach();
            \App\Entities\Agenda::find($id)->medias()->detach();
            \App\Entities\Agenda::find($id)->galleries()->detach();
            \App\Entities\Agenda::find($id)->categories()->detach();
            \App\Entities\Agenda::find($id)->delete();

        })->middleware('auth');

        Route::delete('/delete/content/element/{id}', function($id){

            \App\Entities\Content::find($id)->links()->detach();
            \App\Entities\Content::find($id)->medias()->detach();
            \App\Entities\Content::find($id)->galleries()->detach();
            \App\Entities\Content::find($id)->categories()->detach();
            \App\Entities\Content::find($id)->delete();

        })->middleware('auth');

        Route::delete('/delete/category/element/{id}', function($id){

            \App\Entities\Category::find($id)->agendas()->detach();
            \App\Entities\Category::find($id)->contents()->detach();
            \App\Entities\Category::find($id)->delete();

        })->middleware('auth');

        Route::delete('/delete/gallery/element/{id}', function($id){

            \App\Entities\Gallery::find($id)->delete();

        })->middleware('auth');


        Route::delete('/delete/publication/element/{id}', function($id){

            \App\Entities\Publication::find($id)->medias()->detach();
            \App\Entities\Publication::find($id)->delete();

        })->middleware('auth');

        Route::delete('/delete/gallery/element/{id}', function($id){

            \App\Entities\Gallery::find($id)->agendas()->detach();
            \App\Entities\Gallery::find($id)->contents()->detach();
            \App\Entities\Gallery::find($id)->pictures()->detach();
            \App\Entities\Gallery::find($id)->delete();

        })->middleware('auth');


        Route::delete('/delete/logotypes/element/{id}', function($id){

            \App\Entities\Logotype::find($id)->agendas()->detach();
            \App\Entities\Logotype::find($id)->contents()->detach();
            \App\Entities\Logotype::find($id)->delete();

        })->middleware('auth');


        ////////////////////////////////////////////////DELETE ROUTES FUNCTIONS////////////////////////////////////////////////////////////////////////


        Route::post('/check/is/pdf/program/exist',function(\Illuminate\Http\Request $request){

            $lang = \App\Entities\Language::find($request->get('lang'));

            $boolean = Storage::disk('media')->exists('/programy/'.$request->get('year').'/'.$request->get('month').'/program-'.$lang->tag.'.pdf');
            if($boolean){
                return response('{"success":true}', 200, ['Content-Type'=>'application/json']);
            }else{
                return response('{"success":false}', 200, ['Content-Type'=>'application/json']);
            }


        })->middleware('auth');


        Route::get('/get/lang/by/id/{id}', function($id){

            return \App\Entities\Language::find($id);

        })->middleware('auth');



    });

//});