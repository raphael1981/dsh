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

                    <div class="uns-section-content col" style="min-height:600px">

                        <div class="col-double-inside head-title">
                            <h2>
                                Bookshop
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
                                Karowa 20<br>
                                00-324 Warsaw
                                <br><br>
                                Opening hours: Tuesday-Sunday 12pm – 8pm
                            </div>
                            <div class="uns-half-content second">
                                Phone: 22 255-05-02
                                <br>
                                e-mail: <a href="mailto:ksiegarnia@dsh.waw.pl">ksiegarnia@dsh.waw.pl</a>
                                <br><br>
                                <i><b><a href="http://bookstore.dsh.waw.pl" target="_blank">Be the guest of our online shop!</a></b></i>
                            </div>
                        </div>


                    </div>

                </div>


            </div>

        </div>

        <div class="uns-row-two-cols">

            <div class="uns-section-left col black-bg">
{{--                Dla posiadaczy Karty
                Warszawiaka i Karty
                Młodego Warszawiaka
                wszystkie książki wydane
                nakładem Domu Spotkań z
                Historią oraz wybrane
                pozycje, których DSH jest
                współwydawcą, można
                kupić z 10% rabatem.--}}
                <br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;

            </div>

            <div class="uns-section-right col" style="background: url('{{ asset('images/10.jpg') }}');background-size: cover;background-attachment: fixed">

            </div>

        </div>

        <div class="uns-row-two-cols">

            <div class="uns-section-left col" style="background-color: #{{ $data->color->rgb }};">

                <div class="visit-book-store">
                    <div class="l-text">
                        Visit our online bookshop!
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
                        We offer publications on the history of Poland and Central and Eastern Europe in the 20th century (including foreign publications).
                        <ul style="margin:0">
                            <li>Approximately 2500 publications on World War I and World War II, the history of interwar Poland, the Home Army, the Warsaw Uprising, holocaust and the Jewish issues, Poland-Ukraine relations, the history of the Soviet Union, the history of the Polish People’s Republic, Solidarity movement and the opposition, Martial Law, the history of the Balkan states, the Czech Republic and Slovakia, the history of Warsaw. A lot of publications are only available in our bookshop and are competitively priced.</li>
                        </ul>
                    </div>
                    <div class="uns-half-content second">
                        <ul>
                            <li>A monthly offer on a selected best seller;</li>
                            <li>Journals (Karta), educational booklets, catalogues of exhibitions organized by the HMH;</li>
                            <li>Postcards made of archived photographs;</li>
                            <li>Low-priced books section;</li>
                            <li>The chance to read various publications in our reading room;</li>
                            <li>Participation in book promoting events, discussions with authors and historians;</li>
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
