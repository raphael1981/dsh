<div
        class="dsh-container-rotor relative-cont"
        ng-controller="SlidesController"
        ng-model="how_many"
        ng-model="now"
        ng-model="next"
        ng-model="stop"
        ng-init="
            how_many={{ $count_slides }};
            now=0;
            next=1;
            stop=0;
            initRotor()
        "
>


    <div class="screen-cont">
        @foreach($slides as $k=>$s)

            <div
                    class="slide slide-{{ $k }} {{ $k==0?'active':'' }}"
                    style="background: url('{{  '/pictures/'.$s->image }}') center top; background-attachment: fixed; background-size: cover;"
            >
                <a href="{{ $s->url }}">
                    <img
                            src="{{  asset('pictures/'.$s->image) }}"
                            class="img-responsive"
                            title="obrazek slajdu do wydarzenia {{ $s->title }}"
                            alt="obrazek slajdu do wydarzenia {{ $s->title }}"
                    >
                </a>
            </div>

        @endforeach
    </div>


    <div class="relative-cont-for-desc-beam">

        <div class="rotor-bottom-gird">

            @foreach($slides as $k=>$s)

                {{-- <div class="tags tags-{{ $k }} {{ $k==0?'active':'' }}">
                    <ul>
                        <li>
                            <a href="#">
                                wystawy
                            </a>
                        </li>
                    </ul>
                </div> --}}

                <div class="rotor-title title-{{ $k }} {{ $k==0?'active':'' }}">
                    <h2>
                        <a href="{{ $s->url }}">
                            {{ $s->title }}
                        </a>
                    </h2>
                </div>

                <div class="date-view date-{{ $k }} {{ $k==0?'active':'' }}">
                    {{ $s->description }}
                </div>


                <div class="color-beam color-beam-{{ $k  }} {{ $k==0?'active':'' }}" style="background-color:#{{ $s->color->rgb }}">
                </div>

            @endforeach

        </div>

    </div>

    <div class="relative-cont-for-desc-beam">


        <div class="rotor-points">

            <ul class="rotbtns">
            @foreach($slides as $k=>$s)

                <li>
                    <span class="sbtn rotbtn rotbtn-{{ $k }} {{ $k==0?'active':'' }}" ng-click="$event.preventDefault();changeSlideByButton({{ $k }})"></span>
                </li>

            @endforeach
            </ul>

        </div>


    </div>

</div>