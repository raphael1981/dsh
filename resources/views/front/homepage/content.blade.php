<div ng-controller="HomePageController" class="dsh-container content-home">

    @if(!is_null($structure))

        @foreach($structure as $k=>$el)

            @if(!is_null($el->rowclass))
                <div class="gird-row {{ $el->rowclass }}" home="content-column">
                    <div class="merging-cont">
                        {!! $el->htmlview !!}
                    </div>
                </div>
            @else
                {!! $el->htmlview !!}
            @endif

        @endforeach

    @endif


    {{--<div class="gird-col gird-out-off gird-single no-margin first">--}}

    {{--</div>--}}

    {{--<div class="gird-col gird-out-off gird-single second">--}}

    {{--</div>--}}

    {{--<div class="gird-col gird-out-off gird-single third">--}}

    {{--</div>--}}

    {{--<div class="gird-col gird-out-off gird-single no-margin first">--}}

    {{--</div>--}}

    {{--<div class="gird-col gird-out-off gird-single second">--}}

    {{--</div>--}}

    {{--<div class="gird-col gird-out-off gird-single third">--}}

    {{--</div>--}}



    {{--<div class="gird-row white-gird-row">--}}

        {{--<div class="merging-cont">--}}
            {{--<div class="gird-col gird-double no-margin first">--}}

            {{--</div>--}}

            {{--<div class="gird-col gird-single second">--}}

            {{--</div>--}}
        {{--</div>--}}


    {{--</div>--}}


    {{--<div class="gird-col gird-out-off gird-single no-margin first">--}}

    {{--</div>--}}

    {{--<div class="gird-col gird-out-off gird-single second">--}}

    {{--</div>--}}

    {{--<div class="gird-col gird-out-off gird-single third">--}}

    {{--</div>--}}

    {{--<div class="gird-col gird-out-off gird-single no-margin first">--}}

    {{--</div>--}}

    {{--<div class="gird-col gird-out-off gird-single second">--}}

    {{--</div>--}}

    {{--<div class="gird-col gird-out-off gird-single third">--}}

    {{--</div>--}}

</div>