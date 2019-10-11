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
                                History Meeting House
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
                                Karowa Street 20, 00-324 Warsaw<br>
                                Monday: closed<br>
                                Tuesday – Sunday: 10p.m. &#150; 8p.m.<br>
                                Saturday, Sunday: 12p.m. &#150; 8p.m.<br>
                                admission free<br>
                                The building of DSH (the History Meeting House) is tailored to the needs of people with disabilities
                            </div>
                            <div class="uns-half-content second">
                                Info: +48 22 255 05 00<br>
                                Secretary’s office: +48 22 255 05 05<br>
                                Fax: (+48) 22 255 05 04<br>
                                <a hrefg="mailto:dsh@dsh.pl">dsh@dsh.pl</a>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="uns-row-two-cols">

            <div class="uns-section-left col black-bg">
                Find us on the map
            </div>

            <div class="uns-section-right col col-google-map">
                <div id="map_canvas" style="width:100%; height:600px"></div>
            </div>

        </div>

{{--        <div class="uns-row-two-cols">


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
                        DSH zajmuje się historią Polski i Europy Środkowo-
                        Wschodniej w XX wieku. Szczególne
                        miejsce poświęca przeszłości stolicy i historii
                        jej mieszkańców. W dwóch galeriach wewnętrznych
                        oraz na pobliskim skwerze organizuje
                        wystawy historyczne. DSH realizuje wiele
                        różnorodnych projektów edukacyjnych (w tym
                        międzynarodowych) oraz organizuje cykle
                        spotkań, pokazy filmów, promocje wydawnicze,
                        dyskusje, seminaria i konferencje.
                    </div>
                    <div class="uns-half-content second">
                        Publikuje książki historyczne, a w swojej księgarni
                        oferuje bogaty wybór pozycji dotyczących
                        XX wieku. W Mediotece (Archiwum Cyfrowym)
                        gromadzi, opracowuje i udostępnia relacje
                        świadków historii mówionej (wspólnie
                        z Ośrodkiem KARTA prowadzi Archiwum Historii
                        Mówionej) oraz digitalizuje zdjęcia, dokumenty
                        i filmy.
                    </div>
                </div>

                <div class="uns-dystans"></div>

            </div>

        </div>--}}

        <div class="unstandard-image-view" style="background: url('{{ asset('images/13.jpg') }}');background-size: cover;background-attachment: fixed">
        </div>

    </div>
</div>
