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

<ul class="nav menu">

    @foreach($menu as $key=>$file)

        <li>
            <a href="{{ url('test/5/'.basename($file)) }}">
                {{ basename($file) }}
            </a>
        </li>

    @endforeach

</ul>



</body>
</html>
