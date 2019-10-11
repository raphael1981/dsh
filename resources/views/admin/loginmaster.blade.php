<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" ng-app="app">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link type="text/css" rel="stylesheet/less" href="{{ asset('less/app.less') }}">
    <script src="//cdnjs.cloudflare.com/ajax/libs/less.js/2.7.1/less.min.js"></script>

</head>
<body ng-controller="GlobalController" class="alphaHide" id="body">
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
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        {{--<li><a href="{{ route('login') }}">Login</a></li>--}}
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
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

    {!! $content !!}

</div>

<script type="text/javascript" src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
{{--<script type="text/javascript" src="{{ asset('js/jquery-ui.js') }}"></script>--}}
<script type="text/javascript" src="{{ asset('js/jquery-migrate-1.4.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('angular/angular.min.js') }}"></script>
{{--<script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>--}}
{{--<script type="text/javascript" src="{{ asset('js/datetimepicker.js') }}"></script>--}}
{{--<script type="text/javascript" src="{{ asset('js/datetimepicker.templates.js') }}"></script>--}}
{{--<script type="text/javascript" src="{{ asset('js/ui-bootstrap-tpls-2.3.1.min.js') }}"></script>--}}
<script type="text/javascript" src="{{ asset('angular/angular-sanitize.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('angular/angular-route.min.js') }}"></script>
{{--<script type="text/javascript" src="{{ asset('js/angular-file-upload.js') }}"></script>--}}
{{--<script type="text/javascript" src="{{ asset('js/angular-drag-and-drop-lists.js') }}"></script>--}}
{{--<script type="text/javascript" src="{{ asset('js/ngDialog.js') }}"></script>--}}
{{--<script type="text/javascript" src="{{ asset('tinymce/js/tinymce.min.js') }}"></script>--}}
{{--<script type="text/javascript" src="{{ asset('js/xeditable.js') }}"></script>--}}
{{--<script type="text/javascript" src="{{ asset('js/bootstrap-colorpicker-module.js') }}"></script>--}}
{{--<script type="text/javascript" src="{{ asset('js/angular-wysiwyg.js') }}"></script>--}}
{{--<script type="text/javascript" src="{{ asset('js/ng-file-upload-shim.min.js') }}"></script>--}}
{{--<script type="text/javascript" src="{{ asset('js/ng-file-upload.min.js') }}"></script>--}}
<script type="text/javascript" src="{{ asset('controllers/'.$controller) }}"></script>
{{--<script type="text/javascript" src="{{ asset('js/thumbnails-directive.js') }}"></script>--}}
{{--<script type="text/javascript" src="{{ asset('js/draggable.js') }}"></script>--}}

</body>
</html>
