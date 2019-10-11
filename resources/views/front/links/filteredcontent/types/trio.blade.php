<div class="dsh-container trio-view trio-many-view">


    <div class="over-load-filter" ng-class="overload" ng-init="overload='hidden'">

    </div>


    <div class="result-cont">

        <div
                ng-repeat="res in data.result.not_group"
                class="gird-col gird-out-off gird-single">

            <div class="image-icon" style="position:relative">


                <div ng-if="res.disk!=null && res.alias!=null && res.suffix!=null">

                    <a href="/[[ res.id ]]-[[ res.alias ]],[[ res.suffix ]]" ng-if="res.type=='internal'">


                        <img src="/image/[[ res.image ]]/[[ res.disk ]]/[[ res.image_path ]]/448/panoram" class="img-panoram image-in-box" ng-if="res.params.format_intro_image.name=='panoram'">

                        <img src="/image/[[ res.image ]]/[[ res.disk ]]/[[ res.image_path ]]/1000" class="img-responsive image-in-box" ng-if="!res.params.format_intro_image || res.params.format_intro_image.name!='panoram'">

                    </a>


                    <a href="[[ res.url ]]" target="_blank" ng-if="res.type=='external'">

                        <img src="/image/[[ res.image ]]/[[ res.disk ]]/[[ res.image_path ]]/1000" class="img-panoram image-in-box" ng-if="res.params.format_intro_image.name=='panoram'">

                        <img src="/image/[[ res.image ]]/[[ res.disk ]]/[[ res.image_path ]]/1000" class="img-responsive image-in-box" ng-if="!res.params.format_intro_image || res.params.format_intro_image.name!='panoram'">

                    </a>

                </div>


                <div ng-if="res.disk==null">


                    <a href="/[[ res.id ]]-[[ res.alias ]],[[ res.suffix ]]" ng-if="res.type=='internal'" class="color-banner-col banner-{{ $data->color->classname }}">

                        <img src="[[ res.params.icon ]]" class="img-responsive">

                    </a>


                    <a href="[[ res.url ]]" target="_blank" ng-if="res.type=='external'" class="color-banner-col banner-{{ $data->color->classname }}">

                        <img src="[[ res.params.icon ]]" class="img-responsive">

                    </a>

                </div>


            </div>

            <div class="content-gird-show">

                <div class="date-show">

                    {{--<div ng-if="res.published_at!=null">--}}
                        {{--[[ res.published_at | get_date_content ]]--}}
                    {{--</div>--}}

                </div>

                <div class="title-show">

                    <h2>

                        <a href="/[[ res.id ]]-[[ res.alias ]],[[ res.suffix ]]" ng-if="res.type=='internal'">

                            [[ res.title | cut_title_by_words: 64: windowWidth ]]

                        </a>


                        <a href="[[ res.url ]]" target="_blank" ng-if="res.type=='external'">

                            [[ res.title | cut_title_by_words: 64: windowWidth ]]

                        </a>

                    </h2>

                </div>

                <div class="tags">

                    <span>
                        <span>
                            {{ $data->link->title }},
                        </span>
                    </span>&nbsp;
                    <span ng-repeat="c in res.categories">
                        <span class="cat-tags">
                            [[ c.name ]]<span ng-if="!$last">,</span>
                        </span>&nbsp;
                    </span>&nbsp;

                </div>

            </div>

            <div class="bottom-beam-color" style="background: #{{ $data->params->color->rgb }};"></div>

        </div>


    </div>


</div>