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
    <link type="text/css" rel="stylesheet" href="{{ asset('css/photoswipe.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/default-skin/default-skin.css') }}">
    @include('front.partials.styles')
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId            : '1926890427336458',
                autoLogAppEvents : true,
                xfbml            : true,
                version          : 'v2.9'
            });
            FB.AppEvents.logPageView();
        };

        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
</head>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-63055772-2', 'auto');
    ga('send', 'pageview');

</script>
<body ng-controller="GlobalController" class="alphaHide {{ $colorclass }}" id="body" ng-model="language" ng-init="language='{{ $lang["id"] }}'" resize>


@include('front.partials.menu')

@if($all->params->is_show_desc)
    @include('front.partials.description')
@endif

{!! $content !!}


@include('front.partials.rodonewsletter')

@include('front.partials.footer')


<script type="text/javascript" src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-migrate-1.4.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/photoswipe.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/photoswipe-ui-default.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/gallery.js') }}"></script>
<script type="text/javascript" src="{{ asset('angular/angular.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('angular/angular-sanitize.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('angular/angular-route.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('directives/rodo-newsletter.js') }}"></script>
<script type="text/javascript" src="{{ asset('controllers/'.$controller) }}"></script>
<script>
    document.getElementById('shareBtnFacebook').onclick = function() {
        FB.ui({
            method: 'share',
            display: 'popup',
            href: 'https://developers.facebook.com/docs/',
        }, function(response){});
    }
</script>


</body>
</html>