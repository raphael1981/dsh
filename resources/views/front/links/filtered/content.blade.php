<div
        class="controller-neutral"
        ng-controller="FilterCategoryController"
        ng-model="lid"
        ng-model="lang_tag"
        ng-model="order"
        ng-init="
            lang_tag='{{ $language['tag'] }}';
            lid={{ $data->link->id  }};
            order='{{ $data->params->order->type }}';
            initData()
        "
>


    <div class="fliter-color-beam">

        <div class="dsh-container filter-section-cont less-min-height">

            <div class="gird-row transparent-gird-row filter-row-cont">

                <div class="merging-cont">

                    <div class="gird-col gird-single no-margin transparent-col first">

                        <div class="filter-view-head">
                            <h2>
                                {{ $data->link->title }}
                            </h2>
                        </div>

                        @if($data->params->elastic_view->type=='all')
                        <div class="filter-view-type">

                            <div class="gird-filter-row">
                                <div class="label-f-type-gird">
                                    widok:
                                </div>
                                <div class="filter-type-btns">
                                    <img
                                            src="{{ asset('images/line-view-btn.svg') }}"
                                            ng-click="view_type='line_elements'"
                                            class="img-responsive"
                                            title="Obrazek przełączający po kliknięciu na widok repertuarowy"
                                            alt="Obrazek przełączający po kliknięciu na widok repertuarowy"
                                    >
                                    <img
                                            src="{{ asset('images/col-view-btn.svg') }}"
                                            ng-click="view_type='trio_columns'"
                                            class="img-responsive"
                                            title="Obrazek przełączający po kliknięciu na widok kafelkowy"
                                            alt="Obrazek przełączający po kliknięciu na widok kafelkowy"
                                    >
                                </div>
                            </div>

                        </div>
                        @endif


                    </div>

                    <div class="gird-col gird-double gird-no-min-height transparent-col second">

                        <div class="dyst-48"></div>

                        <div class="inside-gird-row tags-filter">
                            <div class="gird-inside-col label-gird">
                                filtruj:
                            </div>
                            <div class="gird-inside-col content-gird">
                                <ul class="cat-labels filter-labels">
                                    <li
                                            class="element"
                                            ng-repeat="filter in data.filters"
                                    >
                                        <a href="#"
                                           tabindex="0"
                                           role="button"
                                           ng-if="!checkIsFilterActive(filter) || filter_action=='add_from_zero'"
                                           ng-click="$event.preventDefault();changeCurrentFilter(filter,$index,'add')">
                                            <span class="hidden-link">Pokaż wydarzenia z kategorii </span>
                                            [[ filter.name ]]
                                        </a>
                                        <a href="#"
                                           tabindex="0"
                                           role="button"
                                           ng-if="checkIsFilterActive(filter) && filter_action=='add_push_to'"
                                           class="active"
                                           ng-click="$event.preventDefault();changeCurrentFilter(filter,$index,'remove')">
                                            <span class="hidden-link">Schowaj wydarzenia z kategorii </span>
                                            [[ filter.name ]]
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                                href="#"
                                                ng-click="$event.preventDefault();changeAllFilters()"
                                                tabindex="0"
                                                role="button"
                                        >
                                            <span class="hidden-link">Pokaż wydarzenia ze wszystkich kategorii</span>
                                            <img
                                                    src="{{ asset('images/clear-filters-icon.svg') }}"
                                                    class="clear-filter"
                                                    title="ikona x czysczenia filtrów wydarzeń"
                                                    alt="ikona x czysczenia filtrów wydarzeń"
                                            >
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{--<div class="dyst-48"></div>--}}

                        <div class="inside-gird-row years-filter">
                            <div class="gird-inside-col label-gird">
                                rok:
                            </div>
                            <div class="gird-inside-col content-gird">

                                <ul class="years-labels filter-labels">

                                    <li class="arrow">
                                        <a href="#" ng-click="$event.preventDefault();changeYearLine('prev')" tabindex="0" role="button">
                                            <span class="hidden-link">Pokaż wydarzenia z roku [[ data.selected_year-1 ]]</span>

                                            <img
                                                    src="{{ asset('images/left-year-arrow.svg') }}"
                                                    class="clear-filter"
                                                    title="Lewa strzałka"
                                                    alt="Lewa strzałka"
                                            >
                                        </a>
                                    </li>

                                    <li
                                            class="element"
                                            ng-repeat="year in data.filter_years"
                                    >
                                        <a href="#" ng-if="year.active" class="active" tabindex="0" role="button">
                                            <span class="hidden-link">Pokaż wydarzenia z roku </span>
                                            [[ year.value ]]
                                        </a>
                                        <a href="#" ng-if="!year.active" ng-click="$event.preventDefault();changeYearByClick(year);" tabindex="0" role="button">
                                            <span class="hidden-link">Pokaż wydarzenia z roku </span>
                                            [[ year.value ]]
                                        </a>

                                    </li>

                                    <li class="arrow">
                                        <a href="#" ng-click="$event.preventDefault();changeYearLine('next')" tabindex="0" role="button">
                                            <span class="hidden-link">Pokaż wydarzenia z roku [[ data.selected_year+1 ]]</span>
                                            <img
                                                    src="{{ asset('images/right-year-arrow.svg') }}"
                                                    class="clear-filter"
                                                    title="Prawa strzałka"
                                                    alt="Prawa strzałka"
                                            >
                                        </a>
                                    </li>

                                </ul>

                            </div>
                        </div>

                        <div class="inside-gird-row years-filter double-elements">

                            <div class="label-mobile-filter">
                                widok:
                            </div>

                            <div class="col-filter-mobile-view-type">
                                <div class="filter-type-btns">
                                    <img src="{{ asset('images/line-view-btn.svg') }}" ng-click="view_type='line_elements'" class="img-responsive">
                                    <img src="{{ asset('images/col-view-btn.svg') }}" ng-click="view_type='trio_columns'" class="img-responsive">
                                </div>
                            </div>

                            <div class="label-mobile-filter">
                                rok:
                            </div>

                            <div class="col-filter-mobile-year">

                                <ul class="years-labels filter-labels">

                                    <li class="arrow">
                                        <a href="#" ng-click="$event.preventDefault();changeYearLine('prev')" tabindex="0" role="button">
                                            <span class="hidden-link">Pokaż wydarzenia z roku [[ data.selected_year-1 ]]</span>

                                            <img
                                                    src="{{ asset('images/left-year-arrow.svg') }}"
                                                    class="clear-filter"
                                                    title="Lewa strzałka"
                                                    alt="Lewa strzałka"
                                            >
                                        </a>
                                    </li>

                                    <li
                                            class="element"
                                            ng-repeat="year in data.filter_years"
                                    >
                                        <a href="#" ng-if="year.active" class="active" tabindex="0" role="button">
                                            <span class="hidden-link">Pokaż wydarzenia z roku </span>
                                            [[ year.value ]]
                                        </a>
                                        <a href="#" ng-if="!year.active" ng-click="$event.preventDefault();changeYearByClick(year);" tabindex="0" role="button">
                                            <span class="hidden-link">Pokaż wydarzenia z roku </span>
                                            [[ year.value ]]
                                        </a>

                                    </li>

                                    <li class="arrow">
                                        <a href="#" ng-click="$event.preventDefault();changeYearLine('next')" tabindex="0" role="button">
                                            <span class="hidden-link">Pokaż wydarzenia z roku [[ data.selected_year+1 ]]</span>
                                            <img
                                                    src="{{ asset('images/right-year-arrow.svg') }}"
                                                    class="clear-filter"
                                                    title="Prawa strzałka"
                                                    alt="Prawa strzałka"
                                            >
                                        </a>
                                    </li>

                                </ul>

                            </div>

                        </div>

                        {{--<div class="dyst-48"></div>--}}

                        {{--<div class="inside-gird-row">--}}
                            {{--<div class="gird-inside-col label-gird">--}}
                                {{--miesiąc:--}}
                            {{--</div>--}}
                            {{--<div class="gird-inside-col content-gird">--}}

                                {{--<ul class="years-labels filter-labels">--}}

                                    {{--<li class="arrow">--}}
                                        {{--<a href="#" ng-click="$event.preventDefault();changeMonthLine('prev')">--}}
                                            {{--<img src="{{ asset('images/left-year-arrow.svg') }}" class="clear-filter">--}}
                                        {{--</a>--}}
                                    {{--</li>--}}

                                    {{--<li--}}
                                            {{--class="element"--}}
                                            {{--ng-repeat="month in data.filter_months"--}}
                                    {{-->--}}
                                        {{--<a href="#" ng-if="month.active" class="active">--}}
                                            {{--[[ month.name ]]--}}
                                        {{--</a>--}}
                                        {{--<a href="#" ng-if="!month.active" ng-click="$event.preventDefault();changeMonthByClick(month);">--}}
                                            {{--[[ month.name ]]--}}
                                        {{--</a>--}}

                                    {{--</li>--}}

                                    {{--<li class="arrow">--}}
                                        {{--<a href="#" ng-click="$event.preventDefault();changeMonthLine('next')">--}}
                                            {{--<img src="{{ asset('images/right-year-arrow.svg') }}" class="clear-filter">--}}
                                        {{--</a>--}}
                                    {{--</li>--}}

                                {{--</ul>--}}

                            {{--</div>--}}
                        {{--</div>--}}


                    {{--</div>--}}


                </div>

            </div>

        </div>

        </div>


        <div class="head-mobile-filter">
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

        <div class="filters-mobiles-rel-cont" ng-class="filter_mobile_open">
            <div class="filters-mobiles">

                <ul class="cat-labels-mobile">
                    <li
                            class="element-f-mobile"
                            ng-repeat="filter in data.filters"
                    >
                        <a href="#" ng-if="!checkIsFilterActive(filter) || filter_action=='add_from_zero'" ng-click="$event.preventDefault();changeCurrentFilter(filter,$index,'add')">
                            [[ filter.name ]]
                        </a>
                        <a href="#" ng-if="checkIsFilterActive(filter) && filter_action=='add_push_to'" class="active" ng-click="$event.preventDefault();changeCurrentFilter(filter,$index,'remove')">
                            [[ filter.name ]]
                        </a>
                    </li>
                </ul>

                <div class="mobile-months">
                    <ul class="years-labels filter-labels">

                        <li class="arrow">
                            <a href="#" ng-click="$event.preventDefault();changeYearLine('prev')" tabindex="0" role="button">
                                <span class="hidden-link">Pokaż wydarzenia z roku [[ data.selected_year-1 ]]</span>

                                <img
                                        src="{{ asset('images/left-year-arrow.svg') }}"
                                        class="clear-filter"
                                        title="Lewa strzałka"
                                        alt="Lewa strzałka"
                                >
                            </a>
                        </li>

                        <li
                                class="element"
                                ng-repeat="year in data.filter_years"
                        >
                            <a href="#" ng-if="year.active" class="active" tabindex="0" role="button">
                                <span class="hidden-link">Pokaż wydarzenia z roku </span>
                                [[ year.value ]]
                            </a>
                            <a href="#" ng-if="!year.active" ng-click="$event.preventDefault();changeYearByClick(year);" tabindex="0" role="button">
                                <span class="hidden-link">Pokaż wydarzenia z roku </span>
                                [[ year.value ]]
                            </a>

                        </li>

                        <li class="arrow">
                            <a href="#" ng-click="$event.preventDefault();changeYearLine('next')" tabindex="0" role="button">
                                <span class="hidden-link">Pokaż wydarzenia z roku [[ data.selected_year+1 ]]</span>
                                <img
                                        src="{{ asset('images/right-year-arrow.svg') }}"
                                        class="clear-filter"
                                        title="Prawa strzałka"
                                        alt="Prawa strzałka"
                                >
                            </a>
                        </li>

                    </ul>
                </div>

            </div>
        </div>

    </div>

    <div class="res-filter-global-cnt background-repertuar-{{ $data->link->alias }}">

        <div class="dsh-container filter-section-cont none-background-cnt">

            @if($data->params->group->type)

                @include('front.links.filtered.types.group')

            @else

                @include('front.links.filtered.types.ungroup')

            @endif


        </div>

    </div>

</div>