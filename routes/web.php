<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
$domains = config('services')['domains'];

Route::middleware(['https.protocol'])->group(function () use($domains) {

    /*
     * Image render
     */

    Route::get('/image/{filename}/{disk}/{stringpath}/{basesize?}/{format?}', 'Images\ImageController@imageRender');


    /*
     * Image render
     */


/////////////////////////Test Routes////////////////////////////////////////


    Route::group(['domain' => $domains['customers']], function () {

        Route::get('/{prefix}/1', 'TestController@index1')->where('prefix', '^test$');
        Route::get('/{prefix}/2', 'TestController@index2')->where('prefix', '^test$');
        Route::get('/{prefix}/3', 'TestController@index3')->where('prefix', '^test$');
        Route::get('/{prefix}/4', 'TestController@index4')->where('prefix', '^test$');
        Route::get('/{prefix}/5/{json}', 'TestController@index5')->where('prefix', '^test$');
        Route::get('/{prefix}/6', 'TestController@index6')->where('prefix', '^test$');
        Route::get('/{prefix}/7', 'TestController@index7')->where('prefix', '^test$');
        Route::get('/{prefix}/8', 'TestController@index8')->where('prefix', '^test$');
        Route::get('/{prefix}/9', 'TestController@index9')->where('prefix', '^test$');
        Route::get('/{prefix}/10', 'TestController@index10')->where('prefix', '^test$');
        Route::get('/{prefix}/11', 'TestController@index11')->where('prefix', '^test$');
        Route::get('/{prefix}/12', 'TestController@index12')->where('prefix', '^test$');
        Route::get('/{prefix}/13', 'TestController@index13')->where('prefix', '^test$');
        Route::get('/{prefix}/14', 'TestController@index14')->where('prefix', '^test$');
        Route::get('/{prefix}/15', 'TestController@index15')->where('prefix', '^test$');

    });

//    Route::get(LaravelLocalization::transRoute('routes.about'),function(){
//        return View::make('welcome');
//    });


//Auth::routes();
/////////////////////////Test Routes////////////////////////////////////////


    Route::group(['domain' => $domains['customers']], function () {


        /*
         * Prefix api wyznacza routes nie związane za adreami cms
         */
        Route::put('{prefix}/member/newsletter/subscribe',
            ['uses' => 'Front\NewsletterController@subscribeNewsleterMember', 'as' => 'subscribe'])
            ->where('prefix', '^api$');
        Route::get('{prefix}/newsletter/unsubscribe/{token}',
            ['uses' => 'Front\NewsletterController@unsubscribeNewsletterMember', 'as' => 'unsubscribe'])
            ->where('prefix', '^api$');
        Route::post('{prefix}/check/is/member/email/newsletter',
            ['uses' => 'Front\NewsletterController@checkIsEmailInBaseForNewsletter', 'as' => 'check_is_member'])
            ->where('prefix', '^api$');
        Route::get('{prefix}/newsletter/confirm/{token}',
            ['uses' => 'Front\NewsletterController@subscribeConfirm', 'as' => 'subscribe_confirm'])
            ->where('prefix', '^api$');
        Route::get('{prefix}/get/language/full/data/by/{id}', function ($id) {
            return \App\Entities\Language::find($id);
        })->where('prefix', '^api$');


        Route::get('{prefix}', function ($prefix) {
            abort(404, 'Route nie może być widokiem');
        })->where('prefix', '^api$');
        /*
         * Prefix api wyznacza routes nie związane za adreami cms
         */

        Route::post('{prefix}/get/element/logotypes', function (\Illuminate\Http\Request $request) {


            switch ($request->get('type')) {

                case 'agenda':

                    $logotypes = \App\Entities\Agenda::find($request->get('id'))->logotypes()->get();

                    foreach ($logotypes as $k => $l) {
                        $logotypes[$k]->logotypes = \GuzzleHttp\json_decode($logotypes[$k]->logotypes);
                    }

                    return $logotypes;

                    break;

                case 'content':

                    $logotypes = \App\Entities\Content::find($request->get('id'))->logotypes()->get();

                    foreach ($logotypes as $k => $l) {
                        $logotypes[$k]->logotypes = \GuzzleHttp\json_decode($logotypes[$k]->logotypes);
                    }

                    return $logotypes;

                    break;

            }

        })->where('prefix', '^api$');


        /*
        * Filter Category Ajax
        */

        Route::get('{prefix}/get/link/data/{linkid}',
            'Front\Ajax\FilterCategoryController@getLinkFilters')
            ->where('prefix', '^api$');

        Route::post('{prefix}/get/filter/agenda/result',
            'Front\Ajax\FilterCategoryController@getFilterResults')
            ->where('prefix', '^api$');

        Route::post('{prefix}/get/filter/advanced/agenda/result/',
            'Front\Ajax\FilterCategoryController@getFilterAdvancedResults')
            ->where('prefix', '^api$');

        Route::post('{prefix}/get/filter/content/result',
            'Front\Ajax\FilterCategoryController@getFilterContentResults')
            ->where('prefix', '^api$');

        Route::post('{prefix}/get/filter/content/noyear/result',
            'Front\Ajax\FilterCategoryController@getFilterContentResultsNoYear')
            ->where('prefix', '^api$');

        Route::post('{prefix}/get/filter/content/static/result',
            'Front\Ajax\FilterCategoryController@getFilterContentResultsStatic')
            ->where('prefix', '^api$');


        Route::post('{prefix}/get/filter/archive/content/result',
            'Front\Ajax\FilterCategoryController@getFilterContentArchiveResults')
            ->where('prefix', '^api$');

        /*
         * Filter Category Ajax
         */


        /*
        * Filter Many List
        */


        Route::get('{prefix}/get/link/data/many/{id}',
            'Front\Ajax\ManyListController@getLinkData')
            ->where('prefix', '^api$');


        /*
         * Filter Many List
         */


        /*
        * Filter Leaf
        */

        Route::get('{prefix}/link/links/deeper/{id}',
            'Front\Ajax\FilterLeafListController@getLinkLeafs')
            ->where('prefix', '^api$');

        Route::post('{prefix}/get/filter/leaf/result',
            'Front\Ajax\FilterLeafListController@getLeafFilterData')
            ->where('prefix', '^api$');

        /*
        * Filter Leaf
        */


        /*
         * Data get
        */

        Route::get('{prefix}/get/month/names/by/lang/{tag}', function ($prefix, $tag) {
            App::setLocale($tag);
            return Lang::get('translations');
        })->where('prefix', '^api$');

        /*
         * Data get
         */


        /*
         * Search link
         */

        Route::post('{prefix}/get/search/in/index',
            'Front\SearchController@getIndexData')
            ->where('prefix', '^api$');

        /*
         * Search link
         */


    });


    Route::group(
        [
            'prefix' => LaravelLocalization::setLocale(),
            'middleware' => ['localeSessionRedirect', 'localizationRedirect']
        ], function () use ($domains) {

        Route::group(['domain' => $domains['customers']], function () {


            Route::get('/', ['uses' => 'Front\HomePageController@index', 'as' => 'homepage']);


            $suffix_agendas = \App\Entities\ViewProfile::where('type', 'agenda')->get();
            $suffix_content = \App\Entities\ViewProfile::where('type', 'content')->get();

            $reg_agendas = \App\Services\DshHelper::makeRegexSuffixFromCollection($suffix_agendas);
            $reg_content = \App\Services\DshHelper::makeRegexSuffixFromCollection($suffix_content);

            /*
             * CMS
             */

            Route::get('/{singleslugagenda}', 'Front\CmsController@indexSingleAgenda')
                ->where('singleslugagenda', '[a-z0-9-]*,(' . $reg_agendas . ')');

            Route::get('/{singleslugcontent}', 'Front\CmsController@indexSingleContent')
                ->where('singleslugcontent', '[a-z0-9-]*,(' . $reg_content . ')');

            Route::get(LaravelLocalization::transRoute('routes.publication'), 'Front\CmsController@indexSinglePublication')
                ->where('singleslugpublication', '[a-z0-9-]*,[a-z]*');

            Route::get(LaravelLocalization::transRoute('routes.searching'), ['as' => 'dosearch', 'uses' => 'Front\SearchController@index']);

            Route::get('/{primary}', 'Front\CmsController@indexPrimaryLink')
                ->where('primary', '[a-z0-9-]*');

            Route::get('/{primary}/{last}', 'Front\CmsController@indexDeeperLink')
                ->where('primary', '[a-z0-9-]*')
                ->where('last', '[a-z0-9-]*');


            /*
             * CMS
             */


        });


    });


    Route::get('glide/{path}', function ($path) {
        $server = \League\Glide\ServerFactory::create([
            'source' => app('filesystem')->disk('pictures')->getDriver(),
            'cache' => storage_path('glide'),
        ]);
        return $server->getImageResponse($path, Input::query());
    })->where('path', '.+');


});








