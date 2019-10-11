<div class="dsh-container double-view double-many-view">



        <div class="gird-col gird-half gird-out-off many-half-gird" ng-repeat="el in data.result">

            <div class="image-icon">


                <div ng-if="el.entity.disk!=null && el.entity.alias!=null && el.entity.suffix!=null">

                    <a href="/[[ el.entity.id ]]-[[ el.entity.alias ]],[[ el.entity.suffix ]]">

                        <img src="/image/[[ el.entity.image ]]/[[ el.entity.disk ]]/[[ el.entity.image_path ]]/1000" class="img-responsive image-in-box">

                    </a>

                </div>


                <div ng-if="el.entity.disk==null">


                    <a href="/[[ el.entity.id ]]-[[ el.entity.alias ]],[[ el.entity.suffix ]]" class="color-banner-col banner-{{ $data->color->classname }}">

                        <img src="[[ el.params.icon ]]" class="img-responsive">

                    </a>

                </div>

            </div>

            <div class="many-double-content">

                <div class="title-show">

                    <h2>
                        <a href="/[[ el.entity.id ]]-[[ el.entity.alias ]],[[ el.entity.suffix ]]">
                            [[ el.entity.title ]]
                        </a>
                    </h2>

                </div>


                <div class="many-intro-text">


                    <div ng-if="el.entity.intro.length>3" ng-bind-html="el.entity.intro">


                    </div>

                    <div ng-if="el.entity.intro.length<=3" ng-bind-html="el.clear_content | cut_text_less">


                    </div>

                </div>


                <div class="tags-list">

                    <ul class="list-links" ng-if="el.linkgables_id=='App\\Entities\\Agenda'">

                        <li class="tag-el" ng-repeat="cat in el.categories">
                            <a href="#">
                                [[ cat.name ]]
                            </a>
                        </li>

                    </ul>

                    <ul class="list-links" ng-if="el.linkgables_id=='App\\Entities\\Content'">

                        <li class="tag-el" ng-repeat="cat in el.categories">
                            <a href="/[[ cat.link ]]">
                                [[ cat.title ]]
                            </a>&nbsp;&nbsp;
                        </li>

                    </ul>

                </div>

            </div>

            <div class="color-line" style="background-color: #{{ $data->params->color->rgb }}"></div>

        </div>



</div>
