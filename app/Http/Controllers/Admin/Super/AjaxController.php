<?php

namespace App\Http\Controllers\Admin\Super;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class AjaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


}
