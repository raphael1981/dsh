<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,600i,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Crimson+Text" rel="stylesheet">
    <link type="text/css" rel="stylesheet/less" href="{{ asset('less/front.less') }}">
    <script src="//cdnjs.cloudflare.com/ajax/libs/less.js/2.7.1/less.min.js"></script>
    <title>Laravel</title>



</head>
<body>

    <a href="{{ url('test/4') }}" class="btn btn-primary btn-lg">
        Do menu
    </a>

    <div class="dsh-container content-home">

        @foreach($v as $k=>$el)

            @if(!is_null($el->rowclass))
            <div class="gird-row {{ $el->rowclass }}">
                <div class="merging-cont">
                    {!! $el->htmlview !!}
                </div>
            </div>
            @else
                {!! $el->htmlview !!}
            @endif

        @endforeach

    </div>



</body>
</html>
