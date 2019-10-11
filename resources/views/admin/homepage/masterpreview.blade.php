<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" ng-app="app">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Crimson+Text:400,400i,600,600i,700,700i" rel="stylesheet">
    <link type="text/css" rel="stylesheet/less" href="{{ asset('less/front.less') }}">
    <script src="//cdnjs.cloudflare.com/ajax/libs/less.js/2.7.1/less.min.js"></script>
</head>
<body ng-controller="GlobalController" class="alphaHide {{ $colorclass }}" id="body" ng-model="language" ng-init="language='{{ $lang["id"] }}'" resize>


@include('front.partials.menu')


@if(!is_null($slides))
    @include('front.partials.rotor')
@endif

{!! $content !!}

@include('front.partials.contact')

@include('front.partials.newsletter')

@include('front.partials.footer')


<script type="text/javascript" src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-migrate-1.4.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('angular/angular.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('angular/angular-sanitize.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('angular/angular-route.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('controllers/'.$controller) }}"></script>



</body>
</html>