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

    <div class="dsh-container-unstandard unstandard-view media-view-show" style="background: url('{{ asset('images/18.jpg') }}');background-size: cover;background-attachment: fixed">

        <div class="background-container menu-double-cols">

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

            <div class="gird-row transparent-gird-row gird-media">

                <div class="gird-col gird-single gird-out-off">
                    <div class="head-of-media-element">
                        <img src="{{ asset('images/icon-file.svg') }}" class="img-responsive">
                    </div>
                    <div class="content-of-media-element">

                        <a href="{{ url('do-pobrania/DSHlogo.rar') }}" class="link-big">
                            Pobierz logo DSH (manual i wersje kolorystyczne)
                        </a>
                        <br>
 {{--                       <a href="{{ url('do-pobrania/logo_syrenka.rar') }}" class="link-big">
                            Pobierz logo DSH (wersja z warszawską syrenką)
                        </a>--}}


                    </div>
                </div>

                <div class="gird-col gird-single gird-out-off">
                    <div class="head-of-media-element">
                        <img src="{{ asset('images/icon-file.svg') }}" class="img-responsive">
                    </div>
                    <div class="content-of-media-element">

                        <a href="{{ url('do-pobrania/StatutDSH.pdf') }}" target="_blank" class="link-big">
                            Statut DSH
                        </a>
                        <br>
                        <a href="{{ url('do-pobrania/regulamin_DSH.pdf') }}" target="_blank" class="link-big">
                            Regulamin zwiedzania DSH
                        </a>


                    </div>
                </div>

{{--                <div class="gird-col gird-single gird-out-off">
                    <div class="head-of-media-element">
                        <img src="{{ asset('images/icon-file.svg') }}" class="img-responsive">
                    </div>
                    <div class="content-of-media-element">                                               
                        <a class="link-big" href="{{ url('do-pobrania/Logo_Stolica_Wolnosci.zip') }}">
                            Logo warszawskich obchodów 100-lecia niepodległości
                        </a>
                    </div>
                </div>--}}

                <div class="gird-col gird-single gird-out-off">
                    <div class="head-of-media-element">
                        <img src="{{ asset('images/icon-file.svg') }}" class="img-responsive">
                    </div>
                    <div class="content-of-media-element">

                        <a href="{{ url('http://starastrona.dsh.waw.pl/wp-content/uploads/2016/04/formularz.pdf') }}" class="link-big">
                            Formularz wypożyczenia wystawy
                        </a>



                    </div>
                </div>
                <div class="gird-col gird-single gird-out-off">
                    <div class="head-of-media-element">
                        <img src="{{ asset('images/icon-file.svg') }}" class="img-responsive">
                    </div>
                    <div class="content-of-media-element">

                        <a href="{{ url('do-pobrania/DSH_SPRAWOZDANIE_2018_www.pdf') }}" target="_blank" class="link-big">
                            Sprawozdanie z działalności w 2018 roku
                        </a>
                        <br><br>
                        <a href="{{ url('do-pobrania/DSH_SPRAWOZDANIE_2019_POŁ_ROKU.pdf') }}" target="_blank" class="link-big">
                            Sprawozdanie z działalności<br />
                            od 1 stycznia – 30 czerwca 2019 roku
                        </a>


                    </div>
                </div>

            </div>

        </div>
    </div>
</div>