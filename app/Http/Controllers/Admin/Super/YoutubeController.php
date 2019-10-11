<?php

namespace App\Http\Controllers\Admin\Super;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class YoutubeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
}
