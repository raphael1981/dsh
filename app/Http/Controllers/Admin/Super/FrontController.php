<?php

namespace App\Http\Controllers\Admin\Super;

use App\Entities\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class FrontController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {

//        dd(Route::has('homepage-preview'));

        $language = [
            'name'=>LaravelLocalization::getCurrentLocaleName(),
            'tag'=>LaravelLocalization::getCurrentLocale(),
            'id'=>Language::where('tag',LaravelLocalization::getCurrentLocale())->first()->id
        ];
        ;

        $content = '';

        return view('admin.homemaster',
            [
                'content'=>$content,
                'controller'=>'admin/home/home.controller.js',
                'lang'=>$language,
                'languages'=>LaravelLocalization::getSupportedLocales()
            ]
        );

    }

}
