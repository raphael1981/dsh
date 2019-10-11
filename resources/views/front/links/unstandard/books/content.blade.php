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

    <div class="dsh-container-unstandard unstandard-view books-view-show">

        <div class="background-container menu-double-cols" style="background: url('{{ asset('images/17.jpg') }}');background-size: cover;background-attachment: fixed">

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
                                Księgarnia XX wieku
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
                                ul. Karowa 20<br>
                                00-324 Warszawa
                                <br><br>
                                czynne: wtorek-niedziela 12.00-20.00
                            </div>
                            <div class="uns-half-content second">
                                tel.: 22 255-05-02
                                <br>
                                e-mail: <a href="mailto:ksiegarnia@dsh.waw.pl">ksiegarnia@dsh.waw.pl</a>
                                <br><br>
                                <i><b><a href="http://bookstore.dsh.waw.pl" target="_blank">Zapraszamy do zakupów online!</a></b></i>
                            </div>
                        </div>


                    </div>

                </div>


            </div>

        </div>

        <div class="uns-row-two-cols">

            <div class="uns-section-left col black-bg">
                Dla posiadaczy Karty
                Warszawiaka i Karty
                Młodego Warszawiaka
                wszystkie książki wydane
                nakładem Domu Spotkań z
                Historią oraz wybrane
                pozycje, których DSH jest
                współwydawcą, można
                kupić z 10% rabatem.
            </div>

            <div class="uns-section-right col" style="background: url('{{ asset('images/10.jpg') }}');background-size: cover;background-attachment: fixed">

            </div>

        </div>

        <div class="uns-row-two-cols">

            <div class="uns-section-left col" style="background-color: #{{ $data->color->rgb }};">

                <div class="visit-book-store">
                    <div class="l-text">
                        Odwiedź naszą księgarnię online
                    </div>
                    <div class="r-icon">
                        <img src="{{ asset('images/bookstore-icon.svg') }}" class="img-responsive">
                    </div>

                    <a href="http://bookstore.dsh.waw.pl/" target="_blank"></a>
                </div>

            </div>

            <div class="uns-section-right col">

                <div class="uns-dystans"></div>

                <div class="row-half-content">
                    <div class="uns-half-content first">
                        Oferujemy publikacje dotyczące historii Polski i Europy Środkowo-Wschodniej w XX wieku (w tym również wydawnictwa obcojęzyczne):
                        <ul style="margin:0">
                            <li>około 2500 tytułów publikacji dotyczących I i II wojny światowej, historii II RP, Armii Krajowej, Powstania Warszawskiego, Holocaustu i tematyki żydowskiej, stosunków polsko-ukraińskich, historii ZSRR, historii PRL, „Solidarności” i opozycji, Stanu Wojennego, historii państw bałkańskich, Czech i Słowacji, historii Warszawy. Wiele sprzedawanych przez nas książek jest dostępne tylko w naszej księgarni, a proponowane przez nas ceny należą do najniższych;</li>
                        </ul>
                    </div>
                    <div class="uns-half-content second">
                        <ul>
                            <li>co miesiąc w specjalnej ofercie wybrany hit wydawniczy;</li>
                            <li>czasopisma („Karta”), broszury edukacyjne, katalogi wystaw organizowanych przez DSH;</li>
                            <li>pocztówki wykonane z archiwalnych zdjęć;</li>
                            <li>dział taniej książki;</li>
                            <li>możliwość lektury poszczególnych pozycji w naszej czytelni;</li>
                            <li>udział w „Spotkaniach z książką”, dyskusje z autorami i historykami.</li>
                        </ul>
                    </div>
                </div>

                <div class="uns-dystans"></div>

            </div>

        </div>

        <div class="unstandard-image-view" style="background: url('{{ asset('images/17.jpg') }}');background-size: cover;background-attachment: fixed">
        </div>

    </div>
</div>
