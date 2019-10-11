@inject('localization', 'Mcamara\LaravelLocalization\Facades\LaravelLocalization')
@inject('language', 'App\Entities\Language')
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" ng-app="app">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ url('favicon.ico') }}">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Crimson+Text:400,400i,600,600i,700,700i" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/photoswipe.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/default-skin/default-skin.css') }}">
    @include('front.partials.styles')
</head>
{{--<body ng-controller="GlobalController"--}}
      {{--class="alphaHide"--}}
      {{--id="body"--}}
      {{--ng-model="language"--}}
      {{--ng-init="language='{{ $language->where('tag' ,$localization->getCurrentLocale())->first()->id }}'"--}}
      {{--resize>--}}
<body>


{{--@include('front.partials.menu')--}}


{{--{!! $content !!}--}}


{{--@include('front.partials.newsletter')--}}

{{--@include('front.partials.footer')--}}


<script type="text/javascript" src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-migrate-1.4.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/photoswipe.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/photoswipe-ui-default.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/gallery.js') }}"></script>
<script type="text/javascript" src="{{ asset('angular/angular.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('angular/angular-sanitize.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('angular/angular-route.min.js') }}"></script>
{{--<script type="text/javascript" src="{{ asset('controllers/errors/controller.404.js) }}"></script>--}}



</body>
</html>