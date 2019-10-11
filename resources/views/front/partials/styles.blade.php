{{--<link type="text/css" rel="stylesheet/less" href="{{ asset('less/front.less') }}">--}}
{{--<link type="text/css" rel="stylesheet/less" href="{{ asset('less/robert.less') }}">--}}
{{--<link href="{{ asset( 'css/unstandard.css' )}}" rel="stylesheet">--}}
{{--<script src="//cdnjs.cloudflare.com/ajax/libs/less.js/2.7.1/less.min.js"></script>--}}
<link type="text/css" rel="stylesheet" href="{{ asset('css/front.css') }}?{{ str_slug(Carbon\Carbon::now(),'-') }}">