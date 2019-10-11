<div class="dsh-container one-view">



        <div class="gird-col out-off full-width-gird" ng-repeat="el in data.result">


            <div class="grid-left-one">


                <div class="image-cont">


                    <div ng-if="el.entity.disk!=null && el.entity.alias!=null && el.entity.suffix!=null">

                        <a href="/[[ el.entity.id ]]-[[ el.entity.alias ]],[[ el.entity.suffix ]]">

                            <img src="/image/[[ el.entity.image ]]/[[ el.entity.disk ]]/[[ el.entity.image_path ]]/1000" class="img-responsive image-in-box">

                        </a>

                    </div>


                    <div ng-if="el.entity.disk==null">


                        <a href="/[[ el.ag_id ]]-[[ el.ag_alias ]],[[ el.cnt_suffix ]]" class="color-banner-col banner-{{ $data->color->classname }}">

                            <img src="[[ el.params.icon ]]" class="img-responsive">


                        </a>

                    </div>


                </div>


            </div>

            <div class="gird-right-double">


                <div class="right-content-elements">

                    <div class="author-info" ng-if="el.linkgables_type=='App\\Entities\\Agenda' && el.entity.author!=null" class="author-info">
                        [[ el.entity.author ]]
                    </div>

                    <div ng-if="el.linkgables_type=='App\\Entities\\Content'" class="author-info">
                        [[ el.entity.author ]]
                    </div>

                    <div class="element-title">

                        <h2>
                            <a href="/[[ el.entity.id ]]-[[ el.entity.alias ]],[[ el.entity.suffix ]]">
                                [[ el.entity.title ]]
                            </a>
                        </h2>

                    </div>


                    <div class="intro-txt">

                        <div ng-if="el.entity.intro.length>3" ng-bind-html="el.entity.intro">


                        </div>

                        <div ng-if="el.entity.intro.length<=3" ng-bind-html="el.clear_content | cut_text">


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


            </div>

            <div class="color-line-vertical" style="background-color: #{{ $data->params->color->rgb }}"></div>

        </div>


</div>
