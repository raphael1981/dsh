<div class="dsh-container one-view">



    <div class="gird-col out-off full-width-gird" ng-repeat="el in data.result">


        <div class="grid-left-one">


            <div class="image-cont">


                <div ng-if="el.ag_disk!=null && el.ag_image!=null && el.ag_image_path!=null">

                    <a href="/[[ el.ag_id ]]-[[ el.ag_alias ]],[[ el.cnt_suffix ]]" ng-if="el.lgb_linkgables_type=='App\\Entities\\Agenda'">

                        <img src="/image/[[ el.ag_image ]]/[[ el.ag_disk ]]/[[ el.ag_image_path ]]/1000" class="img-responsive image-in-box">

                    </a>

                </div>


                <div ng-if="el.ag_disk==null">


                    <a href="/[[ el.ag_id ]]-[[ el.ag_alias ]],[[ el.cnt_suffix ]]" ng-if="el.lgb_linkgables_type=='App\\Entities\\Agenda'" class="color-banner-col banner-{{ $data->color->classname }}">

                        <img src="[[ el.params.icon ]]" class="img-responsive">


                    </a>

                </div>




                <div ng-if="el.cnt_disk!=null && el.cnt_image!=null && el.cnt_image_path!=null">

                    <a href="/[[ el.cnt_id ]]-[[ el.cnt_alias ]],[[ el.cnt_suffix ]]" ng-if="el.lgb_linkgables_type=='App\\Entities\\Content'">

                        <img src="/image/[[ el.cnt_image ]]/[[ el.cnt_disk ]]/[[ el.cnt_image_path ]]/1000" class="img-responsive image-in-box">

                    </a>

                </div>

                <div ng-if="el.cnt_disk==null">

                    <a href="/[[ el.cnt_id ]]-[[ el.cnt_alias ]],[[ el.cnt_suffix ]]" ng-if="el.lgb_linkgables_type=='App\\Entities\\Content'">

                        <img src="[[ el.params.icon ]]" class="img-responsive">

                    </a>

                </div>


            </div>


        </div>

        <div class="gird-right-double">


            <div class="right-content-elements">

                <div class="author-info" ng-if="el.lgb_linkgables_type=='App\\Entities\\Agenda' && el.ag_author!=null" class="author-info">
                    [[ el.ag_author ]]
                </div>

                <div ng-if="el.lgb_linkgables_type=='App\\Entities\\Content'" class="author-info">
                    [[ el.cnt_author ]]
                </div>

                <div class="element-title" ng-if="el.lgb_linkgables_type=='App\\Entities\\Agenda'">

                    <h2>
                        <a href="/[[ el.ag_id ]]-[[ el.ag_alias ]],[[ el.ag_suffix ]]">
                            [[ el.ag_title ]]
                        </a>
                    </h2>

                </div>

                <div class="element-title" ng-if="el.lgb_linkgables_type=='App\\Entities\\Content'">

                    <h2>
                        <a href="/[[ el.cnt_id ]]-[[ el.cnt_alias ]],[[ el.cnt_suffix ]]">
                            [[ el.cnt_title ]]
                        </a>
                    </h2>

                </div>


                <div class="intro-txt">

                    <div ng-if="el.lgb_linkgables_type=='App\\Entities\\Agenda' && el.ag_intro.length>3" ng-bind-html="el.ag_intro">


                    </div>

                    <div ng-if="el.lgb_linkgables_type=='App\\Entities\\Agenda' && el.ag_intro.length<3" ng-bind-html="el.ag_intro">


                    </div>

                    <div ng-if="el.lgb_linkgables_type=='App\\Entities\\Content' && el.cnt_intro.length>3" ng-bind-html="el.cnt_intro">

                    </div>

                    <div ng-if="el.lgb_linkgables_type=='App\\Entities\\Content' && el.cnt_intro.length<3" ng-bind-html="el.cnt_intro">

                    </div>

                </div>


                <div class="tags-list">

                    <ul class="list-links" ng-if="el.lgb_linkgables_type=='App\\Entities\\Agenda'">

                        <li class="tag-el" ng-repeat="cat in el.categories">
                            <a href="#">
                                [[ cat.name ]]
                            </a>
                        </li>

                    </ul>

                    <ul class="list-links" ng-if="el.lgb_linkgables_type=='App\\Entities\\Content'">

                        <li class="tag-el" ng-repeat="cat in el.categories">
                            <a href="/[[ cat.link ]]">
                                [[ cat.title ]]
                            </a>
                        </li>

                    </ul>

                </div>

            </div>


        </div>

        <div class="color-line-vertical" style="background-color: #{{ $data->params->color->rgb }}"></div>

    </div>


</div>
