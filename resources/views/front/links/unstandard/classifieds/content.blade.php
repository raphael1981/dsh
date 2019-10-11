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

    <div class="dsh-container-unstandard unstandard-view classfields-view-show">

        <div class="background-container menu-double-cols" style="background: url('{{ asset('images/15.jpg') }}') no-repeat;background-size: cover;background-attachment: fixed;">

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

                <div class="gird-row transparent-gird-row gird-classfields">

                    @foreach($data->ent_data as $k=>$p)

                        <div class="gird-col gird-single gird-out-off publication-col">

                            <div class="pub-inside-content">
                                <div class="date-pub-view">
                                    {{ App\Services\DshHelper::getDayNumberFromStringTimestamp($p->created_at) }}&nbsp;
                                    {{ __('translations.month:'.App\Services\DshHelper::getMonthNumberFromStringTimestamp($p->created_at)) }}
                                    &nbsp;{{ App\Services\DshHelper::getYearNumberFromStringTimestamp($p->created_at) }}
                                </div>

                                <div class="title-pub-view">

                                    <h2>
                                        {{ $p->title }}
                                    </h2>

                                </div>

                                <div class="readmore-pub">
                                    <a href="{{ url('ogloszenia/'.$p->id.'-'.$p->alias.','.$p->suffix) }}">
                                        {{ __('translations.readmore_text') }}
                                    </a>
                                </div>
                            </div>


                        </div>

                    @endforeach


                </div>

            </div>

        </div>
    </div>
</div>