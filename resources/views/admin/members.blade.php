<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" ng-app="app">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/datetimepicker.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/ngDialog.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/ngDialog-theme-dialog.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/myxeditable.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/foldertree.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/select.css') }}">
    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/angular_material/1.1.0/angular-material.min.css">
    <link type="text/css" rel="stylesheet/less" href="{{ asset('less/app.less') }}">
    <script src="//cdnjs.cloudflare.com/ajax/libs/less.js/2.7.1/less.min.js"></script>

</head>
<body ng-controller="GlobalController" class="alphaHide" id="body" ng-model="language" ng-init="language='{{ $lang["id"] }}'">

<div id="app">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container-fluid">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/'.$lang['tag'].'/administrator') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->

                @include('admin.topmenu')

                        <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            Język ({{ $lang['name'] }}) <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            @foreach($languages as $k=>$l)

                                @if($lang['tag']!=$k)
                                    <li>
                                        <a href="{{ url($k.'/administrator') }}">
                                            {{ $l['name'] }}
                                        </a>
                                    </li>
                                @endif

                            @endforeach
                        </ul>
                    </li>

                    @if (Auth::guest())
                        {{--<li><a href="{{ route('login') }}">Login</a></li>--}}
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>


                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                {!! $content !!}
            </div>
        </div>
    </div>


</div>

<script type="text/javascript" src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-ui.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-migrate-1.4.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('angular/angular.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('angular/angular-sanitize.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('angular/angular-route.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('angular/angular-animate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('angular/angular-aria.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('angular/angular-messages.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/sortable.js') }}"></script>
<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/t-114/svg-assets-cache.js"></script>
<!-- Angular Material Library -->
<script src="http://ajax.googleapis.com/ajax/libs/angular_material/1.1.0/angular-material.min.js"></script>

<script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/datetimepicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/datetimepicker.templates.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ui-bootstrap-tpls-2.3.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular-file-upload.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular-drag-and-drop-lists.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ngDialog.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/xeditable.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-colorpicker-module.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular-wysiwyg.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ng-file-upload-shim.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ng-file-upload.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/treeView.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular-img-cropper.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/select.js') }}"></script>
<script type="text/javascript" src="{{ asset('controllers/'.$controller) }}"></script>



</body>
</html>