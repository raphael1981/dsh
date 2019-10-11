<?php

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


$domains = config('services')['domains'];

Route::group(['domain' => $domains['admin']], function () {

    Route::get('/get/link/by/id/{id}', 'Admin\Super\LinksController@getItemsByLinkId');
    Route::put('/set/new/status/link', 'Admin\Super\LinksController@setNewStatus');
    Route::put('/set/active', 'Admin\Super\LinksController@changeActive');
    Route::get('/get/fromlink/{id}', 'Admin\Super\LinksController@getItemFromLink');
    Route::delete('/remove/fromlink', 'Admin\Super\LinksController@removeFromLink');
    Route::get('/get/oldexhib', 'OldBaseController@getExhibitions');
    Route::get('/get/olditems/{type}', 'OldBaseController@getItems');
    Route::get('/get/oldevents/{date}', 'OldBaseController@getEvents');
    Route::get('/get/oldeditions', 'OldBaseController@getEditions');
    Route::get('administrator/oldbase','OldBaseController@index');
    Route::post('/upload/carusel/image', 'Admin\Super\SlidesController@uploadImage');
    Route::post('/add/slide', 'Admin\Super\SlidesController@addSlide');
    Route::get('/get/slides','Admin\Super\SlidesController@getAllSlides');
    Route::get('/get/slide/{id}','Admin\Super\SlidesController@getSlideById');
    Route::put('/get/slides/active','Admin\Super\SlidesController@setSlideActive');
    Route::put('/set/new/order/carusel/data','Admin\Super\SlidesController@setOrder');
    Route::delete('/remove/slide','Admin\Super\SlidesController@removeSlide');
    Route::post('/create/image','Admin\Super\SlidesController@createFile');
    Route::post('/change/slide','Admin\Super\SlidesController@changePhoto');
    Route::get('/pic/{filename}/{disk}/{basesize?}', 'Images\ImageController@picFromRoot');
    Route::put('/change/slide/color','Admin\Super\SlidesController@setSlideColor');
    Route::put('/change/slide/textfield','Admin\Super\SlidesController@changeText');


});
