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

        <div class="background-container menu-double-cols siren" style="background: url('{{ asset('images/8.jpg') }}');background-size: cover;background-attachment: fixed" id="scale-by-content-first">

            <div class="siren-background">

            <div class="uns-first-row unstandard-desktop-links">
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

                <div class="uns-section-empty col">

                </div>

                <div class="uns-section-content col">

                    <div class="col-double-inside head-title">
                        <h2>
                            The History Meeting House, a Warsaw’s municipal institution of culture
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
                            The History Meeting House was established in 2005 on the initiative of the KARTA Centre. Since March 2006 it has been a Warsaw’s municipal institution of culture. At HMH we are focused on the history of Poland and Central and Eastern Europe in the twentieth century, with the emphasis on Nazism and communism. We have been successfully popularizing the history of the capital city and its residents. We use various forms of communication to present history. Our main focus is social history, intersting biographies, extraordinary life stories of ordinary people. We are convinced that the memory of the twentieth century builds our identity, both as individuals and as a community. At the HMH we organize exhibitions, documentary and feature film screenings, discussions, conferences, educational workshops , walks, Warsaw bike tours, art installations, happenings, location-based games, and paratheatrical events.
                        </div>
                        <div class="uns-half-content second">
                            The HMH promotes history through various sources and personal testimonies. With the KARTA centre it runs Oral History archive, the largest collection presenting the records of the twentieth century history witnesses, which are amassed, compiled, and shared. We also digitize photographs, documents, and films. The admission to all meeting cycles, events, and exhibitions is free of charge.
<br><br>
                            Programme Board of the History Meeting House: Zbigniew Gluza, Danuta Przywara, Agnieszka Rudzińska, Maciej Drygas, Jerzy Kochanowski, Jacek Leociak, Dariusz Stola.
                        </div>
                    </div>


                </div>

            </div>

            </div>

        </div>

        <div class="uns-row-two-cols">

            <div class="uns-section-left col black-bg">
{{--                DSH realizuje wiele różnorodnych
                projektów edukacyjnych
                (w tym międzynarodowych)
                oraz organizuje cykle spotkań,
                pokazy filmów, promocje wydawnicze,
                dyskusje, seminaria i konferencje.--}}
                <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;

            </div>

            <div class="uns-section-right col" style="background: url('{{ asset('images/14.jpg') }}');background-size: cover;background-attachment: fixed">

            </div>

        </div>

{{--        <div class="uns-row-two-cols last-row">

            <div class="uns-section-left col" style="background-color: #{{ $data->color->rgb }};">

            </div>

            <div class="uns-section-right col">

                <div class="uns-dystans"></div>

                <div class="row-half-content">

                    <div class="col-double-inside head-title">
                        <h2>
                            Główne obszary działań
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
                        W dwóch galeriach wewnętrznych oraz na pobliskim skwerze im. Ks. Jana Twardowskiego organizujemy wystawy historyczne. Nasze ekspozycje opowiadają o historii poprzez źródła: fotografie, relacje, dokumenty, nagrania audio i wideo. Wystawom towarzyszą spotkania, pokazy filmowe, warsztaty i zajęcia edukacyjne.<br><br>
                        Wystawy najczęściej mają charakter fotograficzny, na przykład: "1947 BARWY RUIN. Warszawa i Polska w odbudowie na zdjęciach Henry'ego N. Cobba", "Karol Beyer – pierwsze fotografie Krakowskiego Przedmieścia", "Chodząc po ziemi. 50 lat fotografii prasowej Aleksandra Jałosińskiego", "Budujemy nowy dom. Odbudowa Warszawy w latach 1945-1952", "Architektoniczna spuścizna socrealizmu w Warszawie i Berlinie. MDM / KMA", "Cztery pory Gierka. Polska lat 1970-1980 w fotografiach z Agencji FORUM", "W obiektywie wroga. Niemieccy fotoreporterzy w okupowanej Warszawie (1939–1945)", "Miasto na szklanych negatywach. Warszawa 1916 w fotografiach Willy’ego Römera",
                    </div>
                    <div class="uns-half-content second">
                        <p>
                            "Warszawa z wysoka. Niemieckie zdjęcia lotnicze 1940–1945 z National Archives w College Park", "60 lat temu w Warszawie. Fotografie PAP 1947–48", "Zdjęcia osobiste i zakazane. Życie codzienne w Rumunii w czasach Nicolae Ceauşescu". Wiele ekspozycji ma charakter multimedialny, m.in. "Oblicza totalitaryzmu", "Ocaleni z Mauthausen", "Amerykanin w Warszawie. Stolica w obiektywie Juliena Bryana 1936–1974"
                        </p>
                        DSH wydaje książki dotyczące historii XX wieku, w tym albumy historyczne, varsavianistyczne, wspomnienia. Część albumów towarzyszy wystawom, m.in.: "1947 BARWY RUIN", "Budujemy nowy dom", "Karol Beyer 1818–1877", "Polacy z wyboru". Wiele pozycji ma charakter varsavianistyczny – poza wymienionymi wyżej – to m.in.: "Ostańce. Kamienice warszawskie i ich mieszkańcy", "Kapliczki warszawskie". W budynku DSH znajduje się księgarnia z publikacjami dotyczącymi historii Europy Środkowo-Wschodniej. Księgarnia organizuje promocje książek i spotkania z autorami
                    </div>
                </div>

                <div class="uns-dystans"></div>

            </div>--}}

        </div>

        <div class="unstandard-image-view" style="background: url('{{ asset('images/13.jpg') }}');background-size: cover;background-attachment: fixed">
        </div>

    </div>
</div>