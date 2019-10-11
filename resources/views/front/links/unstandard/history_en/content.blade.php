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

    <div class="dsh-container-unstandard unstandard-view mission-view-show top_one">

        <div class="background-container menu-double-cols" style="background: url('{{ asset('images/8.jpg') }}');background-size: cover;background-attachment: fixed;" id="scale-by-content-first">

            <div class="siren-background">


                <div class="uns-first-row absolute-element unstandard-desktop-links">

                    <div class="uns-section-name col">
                        <div class="head-of-link">
                            <h2>
                                {{ $data->link->title }}
                            </h2>
                        </div>
                    </div>

                    <div class="uns-section-menu col" id="plus-content-height">
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

                <div class="uns-second-row color-by-template" id="content-first">

                    <div class="uns-section-empty col siren-cont">

                    </div>

                    <div class="uns-section-content col" style="position: relative;">



                        <div class="col-double-inside head-title">
                            <h2>
                                The history of Karowa street
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
                                <p>In the beginning was a gorge. It was exceptionally steep compared to those from which neighbouring Bednarska and Mostowa street emerged. Down the gorge the brooks draining water from where Piłsudski Square is now, hillside streams, and, with city expansion, community sewage were flowing.</p>

                                <p>The beginnings of the street date back to building a dike connecting the hill with the river embankment. A narrow track is approximately 4 metres, enough to fit wheelbarrows passing by, transporting municipal waste to the settlers of the river embankment. The street becomes refuse dump of the city and the carts known as kary (Latin currus) give rise to the name of the street. Karowa appears officially on the map of Warsaw in 1770. In 1855 the first symbols of the city, the gate with a fountain and a mermaid statue, appear near the embankment along Krakowskie Przedmieście.</p>

                                <p>
                                    As Marconi waterpipe is built the carts are replaced by the pipes pumping water to the water tower in the Saxon Garden and the city sewage into the Vistula. At the end of the nineteenth century Marconi waterpipe yielded ground to the sewage pumping station in Powiśle, a part of Lindley‘s waterwork system.
                                </p>
                            </div>
                            <div class="uns-half-content second">
                                <p>
                                    Below Karowa street, in the area of Hotel Bristol today, a huge overflow chamber appears. It pipes the excess water through storm drain to the Vistula during heavy rain. Urban ambitions of municipal authorities to convert the city into a representative artery connecting Powiśle with Krakowskie Przedmieście put an end to the characteristic gate with the fountain and the mermaid statue. A snail-shaped viaduct, a new symbol of the street, appears in their place. As there were plans to build underground in Karowa street, the viaduct, as its predecessor, was meant to be destroyed, which never happened.
                                </p>

                                <p>
                                    Karowa is one of the most peculiar streets of Śródmieście, comprising three distinct parts: the upper, stretching from Krakowskie Przedmieście to the viaduct, the snail-shaped viaduct, and the lower part which, as the riverbed receded from the embankment, would extend to Wybrzeże Kościuszkowskie. Karowa has never fully come into being. It is the street of two worlds, worse Powiśle and the city whose ambitions have never been realized. The ghost street...
                                </p>

                                <p>
                                    More on the history of the street can be found in the guide KAROWA published by the HMH and available in Księgarnia XX wieku bookshop.
                                </p>

                            </div>
                        </div>


                    </div>

                </div>


            </div>

        </div>



        <div class="uns-row-two-cols">

            <div class="uns-section-left col black-bg">
                The name Karowa probably derives from fire guard carts known as kary which often passed there on their way to the Vistula where they disposed of litter from the city.
            </div>

            <div class="uns-section-right col" style="background: url('{{ asset('images/14.jpg') }}');background-size: cover;background-attachment: fixed">

            </div>

        </div>

        <div class="uns-row-two-cols">

            <div class="uns-section-left col col-text-right" style="background-color: #{{ $data->color->rgb }};">

            </div>

            <div class="uns-section-right col">

                <div class="uns-dystans"></div>

                <div class="row-half-content">

                    <div class="col-double-inside head-title">
                        <h2>
                            Karowa 20. The history of the building.
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

                    <div class="uns-half-content first">
                        <p>
                            Initially a theatre was meant to be built where the History Meeting House stands now. Ignacy Paderewski’s company, the owner of Hotel Bristol and the neighbouring area, was planning to erect a large concert hall next to the hotel. The idea was abandoned as Paderewski became involved in Warsaw Philharmonic project. In 1928 the owner of Hotel Bristol and the adjacent grounds becomes Bank Cukrownictwa SA, which in 1932-1933 erects a tenement house according to a design by Antonii Jawornicki. Previously, there was a building accommodating hotel power generators at the back. It was adapted as storage in the 1920s.

                        </p>
                    </div>
                    <div class="uns-half-content second">
                        <p>The edifice of Bank Cukrownictwa SA survived the war unscathed. In the postwar period its interiors were converted and only few decorative elements and items of equipment were preserved. At present the History Meeting House occupies its basement and old banking hall with a mezzanine. One is reminded that there once was a bank by the blast door to the safe, now leading to the room where educational workshops are now held. The other storeys of the building are occupied by the offices and the University of Warsaw.
                        </p>
                    </div>
                </div>

                <div class="uns-dystans"></div>

            </div>

        </div>

        <div class="unstandard-image-view" style="background: url('{{ asset('images/6.jpg') }}');background-size: cover;background-attachment: fixed">
        </div>

    </div>
</div>
