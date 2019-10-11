@if($data->params->elastic_view->type=='tiles' || $data->params->elastic_view->type=='all')

    <div
            class="dsh-container content-filter-results"
            @if($data->params->elastic_view->type=='tiles')
            @elseif($data->params->elastic_view->type=='all')
            ng-if="view_type=='trio_columns' || windowWidth<772"
            @endif
    >
        <div class="over-load-filter" ng-class="overload" ng-init="overload='hidden'">

        </div>



            <div class="month-results">

                <div
                        ng-repeat="res in data.result.not_group"
                        class="gird-col gird-out-off gird-single agenda_custom_image">

                    <div class="image-icon" style="position:relative">


                        <a href="/[[ res.id ]]-[[ res.alias ]],[[ res.suffix ]]" ng-if="res.disk!=null && res.image!=null && res.image_path!=null">
                            <span class="hidden-link">Link do wydarzenia [[ res.title ]]</span>

{{--                            <img src="/image/[[ res.image ]]/[[ res.disk ]]/[[ res.image_path ]]/400" class="img-responsive image-in-box" ng-if="res.params.format_intro_image.name == 'normal' || res.params.format_intro_image == null">
--}}
                            <img src="/image/[[ res.image ]]/[[ res.disk ]]/[[ res.image_path ]]/224/panoram" class="img-panoram image-in-box" ng-if="res.params.format_intro_image.name == 'panoram'">

                            <img
                                    src="[[ res.image_path | create_path ]]/[[ res.image ]]"
                                    class="img-responsive image-in-box"
                                    title="Obrazek wydarzenia [[ res.title ]]"
                                    alt="Obrazek wydarzenia [[ res.title ]]"
                                    ng-if="res.params.format_intro_image.name == 'normal' || res.params.format_intro_image == null"
                            >
                        </a>

                        <a href="/[[ res.id ]]-[[ res.alias ]],[[ res.suffix ]]" ng-if="res.disk==null && res.image==null && res.image_path==null" class="color-banner-col" style="background-color: #{{ $data->params->color->rgb }};">
                            <span class="hidden-link">Link do wydarzenia [[ res.title ]]</span>
                            <img
                                    src="[[ res.params.icon ]]"
                                    class="img-responsive"
                                    title="Obrazek wydarzenia [[ res.title ]]"
                                    alt="Obrazek wydarzenia [[ res.title ]]"
                            >
                        </a>

                    </div>

                    <div class="content-gird-show">

                        <div class="date-show">
                            <span ng-bind-html="res.begin | get_date_agenda: res.end:translations"></span>
                        </div>

                        <div class="title-show">

                            <h2>
                                <a href="/[[ res.id ]]-[[ res.alias ]],[[ res.suffix ]]">
                                    <span class="hidden-link">Link do wydarzenia [[ res.title ]]</span>
                                    [[ res.title | cut_title_by_words: 64: windowWidth ]]
                                </a>
                            </h2>

                        </div>

                        <div class="tags">

                            <span>
                                <span class="cat-tags">
                                    {{ $data->link->title }},
                                </span>
                            </span>&nbsp;
                            <span ng-repeat="c in res.categories">
                                <span class="cat-tags">
                                    [[ c.name ]]<span ng-if="!$last">,</span>
                                </span>&nbsp;
                            </span>

                        </div>

                    </div>

                    <div class="bottom-beam-color" style="background: #{{ $data->params->color->rgb }};"></div>

                </div>

            </div>


    </div>


@endif





@if($data->params->elastic_view->type=='repertuar' || $data->params->elastic_view->type=='all')

    <div
            class="dsh-container content-filter-results-block background-repertuar-{{ $data->link->alias }}"
            @if($data->params->elastic_view->type=='repertuar')

            @elseif($data->params->elastic_view->type=='all')
            ng-if="view_type=='line_elements' && windowWidth>771"
            @endif
    >

        <div class="over-load-filter" ng-class="overloadlayer">

        </div>


            <div class="gird-row white-gird-row" ng-repeat="res in data.result.not_group">

                <div class="merging-cont">

                    <div class="gird-col no-margin gird-line-full">


                        <div class="date-line-left">

                            <div class="inner-cont-left">

                                <div class="left-date">

                                    <div class="day">
                                        <h3>
                                            [[ res.begin | get_date_agenda: res.end ]]
                                        </h3>
                                    </div>

                                    <div class="week-day">
                                        <h4>
                                            [[ res.begin | get_week_day_name: translations ]]
                                        </h4>
                                    </div>

                                </div>

                                <div class="right-date">
                                    <h4>
                                        [[ res.begin_time | cut_time: res.end_time ]]
                                    </h4>
                                </div>

                            </div>

                        </div>

                        <div class="content-line-right">

                            <div class="inner-cont-right">
                                <div class="title-line">
                                    <h2>
                                        <a href="/[[ res.id ]]-[[ res.alias ]],[[ res.suffix ]]">
                                            <span class="hidden-link">Link do wydarzenia [[ res.title ]]</span>
                                            [[ res.title ]]
                                        </a>
                                    </h2>
                                </div>
                                <div class="tags">

                                    <span>
                                        <span class="cat-tags">
                                            {{ $data->link->title }},
                                        </span>
                                    </span>&nbsp;
                                    <span ng-repeat="c in res.categories">
                                        <span class="cat-tags">
                                            [[ c.name ]]<span ng-if="!$last">,</span>
                                        </span>&nbsp;
                                    </span>


                                </div>
                            </div>

                        </div>

                    </div>


                </div>

            </div>

    </div>

@endif