<div
        class="controller-neutral"
        ng-controller="UnstandardController"
        ng-model="lid"
        ng-model="lang_tag"
        ng-init="
            lang_tag='{{ $language['tag'] }}';
            lid={{ $data->link->id  }};
            initData()
        "
>

    <div class="dsh-container-unstandard unstandard-view contact-view-show">

        <div class="background-container menu-double-cols" style="background: url('{{ asset('images/8.jpg') }}');background-size: cover;background-attachment: fixed">

            <div class="shadow-background">

                <div class="uns-first-row unstandard-desktop-links">

                    <div class="uns-section-name col">
                        <div class="head-of-link">
                            <h2>
                                {{ $data->link->title }}
                            </h2>
                        </div>
                    </div>

                    <div class="uns-section-menu col">
                        <ul class="filter-labels">
                            @foreach($data->other_links as $k=>$l)
                                <li class="element">
                                    @if($l->active)
                                        <a href="{{ url($l->link) }}" class="active">
                                            {{ $l->title }}
                                        </a>
                                    @else
                                        <a href="{{ url($l->link) }}">
                                            {{ $l->title }}
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>

                <div class="head-mobile-filter-unstadard">
                    <div class="row-mobile-filter-inside">

                        <div class="name-of-section">
                            <h2>
                                {{ $data->link->title }}
                            </h2>
                        </div>

                        <div class="button-filter-open">
                            <a href="#" ng-click="$event.preventDefault();(filter_mobile_open=='hidden')?(filter_mobile_open=''):(filter_mobile_open='hidden')">
                                filtruj
                            </a>
                        </div>

                    </div>
                </div>

                <div class="filters-mobiles-rel-cont" ng-class="filter_mobile_open" ng-init="filter_mobile_open='hidden'">
                    <div class="filters-mobiles">

                        <ul class="cat-labels-mobile">
                            @foreach($data->other_links as $k=>$l)
                                <li class="element">
                                    @if($l->active)
                                        <a href="{{ url($l->link) }}" class="filter active">
                                            {{ $l->title }}
                                        </a>
                                    @else
                                        <a href="{{ url($l->link) }}" class="filter">
                                            {{ $l->title }}
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                    </div>
                </div>

                <div class="uns-second-row color-by-template">

                    <div class="uns-section-empty col">

                    </div>

                    <div class="uns-section-content col">

                        <div class="col-double-inside head-title">
                            <h2>
                                Dom Spotkań z Historią
                            </h2>
                        </div>

                        <div class="border-half-row">
                            <div class="border-left">

                            </div>
                            <div class="border-right">

                            </div>
                        </div>

                        <div class="border-full-row">
                            <div class="border-line">

                            </div>
                        </div>

                        <div class="row-half-content">
                            <div class="uns-half-content first">
                                ul. Karowa 20, 00-324 Warszawa<br>
                                poniedziałek: nieczynne<br>
                                wtorek–piątek: 10:00 – 20:00<br>
                                sobota–niedziela: 12:00 – 20:00<br>
                                wstęp wolny<br>
                                Budynek DSH jest przystosowany do potrzeb
                                osób niepełnosprawnych.
                            </div>
                            <div class="uns-half-content second">
                                informacja: +48 22 255 05 00<br>
                                sekretariat: +48 22 255 05 05<br>
                                faks (+48) 22 255 05 04<br>
                                <a href="mailto:dsh@dsh.waw.pl">dsh@dsh.waw.pl</a>
                            </div>
                        </div>


                    </div>

                </div>

            </div>

        </div>

        <div class="uns-row-two-cols">

            <div class="uns-section-left col black-bg">
                Zobacz mapę okolicy.
            </div>

            <div class="uns-section-right col col-google-map">
                <div id="map_canvas" style="width:100%; height:600px"></div>
            </div>

        </div>

        <div class="uns-row-two-cols">


            <div class="uns-section-left col" style="background-color: #{{ $data->color->rgb }};">
                Jak dojechać?
            </div>

            <div class="uns-section-right col">

                <div class="uns-dystans"></div>

                <div class="row-half-content">

                    <div class="half-titles">

                        <div class="title-left">
                            <h2>
                                Komunikacja miejska
                            </h2>
                        </div>

                        <div class="title-right">
                            <h2>
                                Samochód i rower
                            </h2>
                        </div>

                    </div>

                    <div class="border-half-row">
                        <div class="border-left">

                        </div>
                        <div class="border-right">

                        </div>
                    </div>

                    <div class="border-full-row">
                        <div class="border-line">

                        </div>
                    </div>

                    <div class="uns-half-content first">

                        <h4>Do Domu Spotkań z Historią można dojechać:</h4>

                        <strong>autobusami:</strong><br>
                        116, 178, 180, 222 w kierunku Placu Zamkowego – przystanek Hotel Bristol 02 (160m od DSH)<br>
                        116, 128, 175, 178, 180, 222 w kierunku Królewskiej – przystanek Hotel Bristol 01 (150m od DSH)<br>
                        102, 105, 111, 128, 175, 503, 518, E-2 – przystanek Uniwersytet (350m od DSH)<br>
                        160, 190, 527 – przystanek Stare Miasto (800m od DSH)<br><br>
                        <strong>metrem:</strong><br>
                        linia M2 – stacja Nowy Świat Uniwersytet (700m od DSH)<br><br>
                        <strong>tramwajami:</strong><br>
                        4, 13, 20, 23, 26 – przystanek Stare Miasto (800m od DSH)

                    </div>
                    <div class="uns-half-content second">
                        Aby dojechać do Domu Spotkań z Historią samochodem, należy jechać ulicą Karową od strony wschodniej. Nie ma możliwości wjazdu na Karową od ulicy Krakowskie Przedmieście – obowiązuje tam ograniczenie ruchu drogowego, mogą tamtędy przejeżdżać tylko autobusy, taksówki, rowery oraz samochody z odpowiednim identyfikatorem zezwalającym na poruszanie się w tej strefie.
                        Przy ulicy Karowej znajduje się parking, który od poniedziałku do piątku w godzinach 08:00-18:00 jest płatny. Opłata minimalna (10 min.) wynosi 50 groszy, pierwsza godzina postoju kosztuje 3zł, druga –3,60zł, trzecia – 4,20zł, czwarta i kolejne – 3zł.
                        Można również skorzystać z Warszawskiego Roweru Publicznego. Na skrzyżowaniu ulicy Karowej z Krakowskim Przedmieściem, 80m od DSH, znajduje się stacja rowerów elektrycznych (stacja nr 9710), a na skrzyżowaniu Krakowskiego Przedmieścia z Trębacką jest stacja zwykłych rowerów (stacja nr 9468). Osoby, które chcą przyjechać na własnym rowerze, mogą skorzystać ze stojaków na rowery znajdujących się przy ul. Karowej 18.
                    </div>
                </div>

                <div class="uns-dystans"></div>

            </div>

        </div>

        <div class="unstandard-image-view" style="background: url('{{ asset('images/13.jpg') }}');background-size: cover;background-attachment: fixed">
        </div>

    </div>
</div>
