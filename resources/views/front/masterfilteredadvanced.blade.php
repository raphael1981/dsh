<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" ng-app="app">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - {{ ucfirst($all->link->title) }}</title>
    <link rel="icon" href="{{ url('favicon.ico') }}">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Crimson+Text:400,400i,600,600i,700,700i" rel="stylesheet">
    @include('front.partials.styles')
</head>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-63055772-2', 'auto');
    ga('send', 'pageview');

</script>
<body ng-controller="GlobalController" class="alphaHide {{ $colorclass }}" id="body" ng-model="language" ng-model="langtag" ng-init="language='{{ $lang["id"] }}';" resize>


@include('front.partials.menu')

@if($all->params->is_show_desc)
    @include('front.partials.description')
@endif

{!! $content !!}


@if($lang['tag'] == 'pl')
    @include('front.partials.rodonewsletter')
@endif

@include('front.partials.footer')


<script type="text/javascript" src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-migrate-1.4.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('angular/angular.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('angular/angular-sanitize.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('angular/angular-route.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('directives/rodo-newsletter.js') }}"></script>
<script type="text/javascript" src="{{ asset('controllers/'.$controller) }}"></script>



</body>
</html>