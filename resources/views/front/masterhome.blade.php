<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" ng-app="app">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - {{  __('translations.homepage') }}</title>
    <link rel="icon" href="{{ url('favicon.ico') }}">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Crimson+Text:400,400i,600,600i,700,700i" rel="stylesheet">
    @include('front.partials.styles')
</head>

{{--<style>
    img {
        -webkit-filter: grayscale(100%); /* Safari 6.0 - 9.0 */
        filter: grayscale(100%);
    }



    .color-beam,
    .color-line,
    .bottom-beam,
    .foot-beam-full-color,
    .slide,
    .sbtn.rotbtn,
    .name-of-dsh
    {
        -webkit-filter: grayscale(100%); /* Safari 6.0 - 9.0 */
        filter: grayscale(100%);
    }

</style>--}}
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


@if(!is_null($slides))
@include('front.partials.rotor')
@endif

{!! $content !!}

@include('front.partials.contact')
@if($lang['tag'] == 'pl')
    @include('front.partials.rodonewsletter')
@endif
@include('front.partials.footer')



<!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC16V2w1jVFnwC8OJs4hF8r2aWqHmYBwHE"></script>-->
{{--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA0L5sTcPHVD3zi1eiRs19xS0avbvCvZ18"></script>--}}

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCoFUM69DcqNC6_cw_hBTuUSI7WfsrR04w"></script>

<script type="text/javascript" src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-migrate-1.4.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('angular/angular.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('angular/angular-sanitize.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('angular/angular-route.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('directives/rodo-newsletter.js') }}"></script>
<script type="text/javascript" src="{{ asset('controllers/'.$controller) }}"></script>

<script>

    var map;
    var dsh = new google.maps.LatLng(52.242280,21.0165000,17);

    var stylez = [
        {
            featureType: "all",
            elementType: "all",
            stylers: [
                { saturation: -100 } // <-- THIS
            ]
        }
    ];

    var mapOptions = {
        zoom: 16,
        center: dsh,
        zoomControl: false,
        mapTypeControl: false,
        scaleControl: false,
        streetViewControl: false,
        rotateControl: false,
        fullscreenControl: false,
        mapTypeControlOptions: {
            mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'tehgrayz']
        }
    };


    map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

    var mapType = new google.maps.StyledMapType(stylez, { name:"Grayscale" });
    map.mapTypes.set('tehgrayz', mapType);
    map.setMapTypeId('tehgrayz');


    marker = new google.maps.Marker({
        map:map,
        // draggable:true,
        // animation: google.maps.Animation.DROP,
        position: new google.maps.LatLng(52.242280,21.0165000,17),
        icon: '/images/marker.svg' // null = default icon
    });


    marker.setMap(map)

</script>


</body>
</html>