<div
        class="controller-neutral"
        @if($data->params->is_year_in_filter->value)
                @if($data->params->is_filters_active->value)
                    ng-controller="FilterCategoryContentYearController"
                @else
                    ng-controller="FilterCategoryStaticController"
                @endif
        @else

            @if($data->params->is_filters_active->value)
                ng-controller="FilterCategoryContentNoYearController"
            @else
                ng-controller="FilterCategoryStaticController"
            @endif
        @endif
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

    @if($data->params->is_filters_active->value)
    <div class="fliter-color-beam">

        <div class="dsh-container filter-section-cont less-min-height">

            <div class="gird-row transparent-gird-row filter-row-cont">

                <div class="merging-cont">

                    <div class="gird-col gird-single no-margin transparent-col first">

                        &nbsp;
                        {{--<div class="filter-view-head">--}}
                            {{--<h2>--}}
                                {{--{{ $data->link->title }}--}}
                            {{--</h2>--}}
                        {{--</div>--}}


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
                                        <a href="#" ng-if="!checkIsFilterActive(filter) || filter_action=='add_from_zero'" ng-click="$event.preventDefault();changeCurrentFilter(filter,$index,'add')">
                                            [[ filter.name ]]
                                        </a>
                                        <a href="#" ng-if="checkIsFilterActive(filter) && filter_action=='add_push_to'" class="active" ng-click="$event.preventDefault();changeCurrentFilter(filter,$index,'remove')">
                                            [[ filter.name ]]
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" ng-click="$event.preventDefault();changeAllFilters()">
                                            <img src="{{ asset('images/clear-filters-icon.svg') }}" class="clear-filter">
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{--<div class="dyst-48"></div>--}}

                        @if($data->params->is_year_in_filter->value)

                            <div class="inside-gird-row years-filter">
                                <div class="gird-inside-col label-gird">
                                    rok:
                                </div>
                                <div class="gird-inside-col content-gird">

                                    <ul class="years-labels filter-labels">

                                        <li class="arrow">
                                            <a href="#" ng-click="$event.preventDefault();changeYearLine('prev')">
                                                <img src="{{ asset('images/left-year-arrow.svg') }}" class="clear-filter">
                                            </a>
                                        </li>

                                        <li
                                                class="element"
                                                ng-repeat="year in data.filter_years"
                                        >
                                            <a href="#" ng-if="year.active" class="active">
                                                [[ year.value ]]
                                            </a>
                                            <a href="#" ng-if="!year.active" ng-click="$event.preventDefault();changeYearByClick(year);">
                                                [[ year.value ]]
                                            </a>

                                        </li>

                                        <li class="arrow">
                                            <a href="#" ng-click="$event.preventDefault();changeYearLine('next')">
                                                <img src="{{ asset('images/right-year-arrow.svg') }}" class="clear-filter">
                                            </a>
                                        </li>

                                    </ul>

                                </div>
                            </div>


                        @endif


                        <div class="inside-gird-row years-filter double-elements">

                            @if($data->params->is_year_in_filter->value)

                                <div class="label-mobile-filter">
                                    rok:
                                </div>

                                <div class="col-filter-mobile-year">

                                    <ul class="years-labels filter-labels">

                                        <li class="arrow">
                                            <a href="#" ng-click="$event.preventDefault();changeYearLine('prev')">
                                                <img src="{{ asset('images/left-year-arrow.svg') }}" class="clear-filter">
                                            </a>
                                        </li>

                                        <li
                                                class="element"
                                                ng-repeat="year in data.filter_years"
                                        >
                                            <a href="#" ng-if="year.active" class="active">
                                                [[ year.value ]]
                                            </a>
                                            <a href="#" ng-if="!year.active" ng-click="$event.preventDefault();changeYearByClick(year);">
                                                [[ year.value ]]
                                            </a>

                                        </li>

                                        <li class="arrow">
                                            <a href="#" ng-click="$event.preventDefault();changeYearLine('next')">
                                                <img src="{{ asset('images/right-year-arrow.svg') }}" class="clear-filter">
                                            </a>
                                        </li>

                                    </ul>

                                </div>


                            @endif

                        </div>



                </div>

            </div>

        </div>


        </div>


    </div>


    <div class="dsh-container filter-section-cont">


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

        </div>
    </div>

    @endif

    @include('front.links.filteredcontent.types.'.$data->params->config->show)


    </div>

</div>