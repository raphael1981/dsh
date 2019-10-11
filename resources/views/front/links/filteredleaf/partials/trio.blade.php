<div class="dsh-container trio-view trio-many-view">


    <div class="over-load-filter" ng-class="overload" ng-init="overload='hidden'">

    </div>


        <div
                ng-repeat="res in data.result"
                class="gird-col gird-out-off gird-single">

            <div class="image-icon">


                <div ng-if="res.entity.disk!=null && res.entity.alias!=null && res.entity.suffix!=null">

                    <a href="/[[ res.entity.id ]]-[[ res.entity.alias ]],[[ res.entity.suffix ]]">

                        <img src="/image/[[ res.entity.image ]]/[[ res.entity.disk ]]/[[ res.entity.image_path ]]/1000" class="img-responsive image-in-box">

                    </a>

                </div>


                <div ng-if="res.entity.disk==null">


                    <a href="/[[ res.entity.id ]]-[[ res.entity.alias ]],[[ res.entity.suffix ]]" class="color-banner-col banner-{{ $data->color->classname }}">

                        <img src="[[ res.params.icon ]]" class="img-responsive">

                    </a>

                </div>


            </div>

            <div class="content-gird-show">

                <div class="date-show">

                    <div ng-if="res.entity.begin">
                        [[ res.entity.begin | get_date_agenda: res.entity.end ]]
                    </div>

                </div>

                <div class="title-show">

                    <h2>
                        <a href="/[[ res.entity.id ]]-[[ res.entity.alias ]],[[ res.entity.suffix ]]">
                            [[ res.entity.title | cut_title ]]
                        </a>
                    </h2>

                </div>

                <div class="tags">

                            <span ng-repeat="c in res.categories">
                                <span class="cat-tags">
                                    <a href="#">[[ c.name ]]</a>&nbsp;&nbsp;
                                </span><span ng-if="!$last">,</span>
                            </span>

                </div>

            </div>

            <div class="bottom-beam-color" style="background: #{{ $data->params->color->rgb }};"></div>

        </div>




</div>