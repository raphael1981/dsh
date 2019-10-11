<div class="dsh-container one-view one-many-view">


    <div class="gird-col out-off full-width-gird" ng-repeat="el in data.result.not_group">


        <div class="grid-left-one">


            <div class="image-cont">


                <div ng-if="el.disk!=null && el.image_path!=null && el.image!=null" ng-if="el.type=='internal'">

                    <a href="/[[ el.id ]]-[[ el.alias ]],[[ el.suffix ]]" ng-if="el.type=='internal'">

                        <img src="/image/[[ el.image ]]/[[ el.disk ]]/[[ el.image_path ]]/448/panoram" class="img-big-panoram image-in-box" ng-if="el.params.format_intro_image.name == 'panoram' && el.params.format_intro_image.name == 'panoram'" style="position:static">

                        <img src="/image/[[ el.image ]]/[[ el.disk ]]/[[ el.image_path ]]/800" class="img-responsive image-in-box" ng-if="el.params.format_intro_image.name == 'portret'">						
						
                        <img src="/image/[[ el.image ]]/[[ el.disk ]]/[[ el.image_path ]]/1000" class="img-responsive image-in-box" ng-if="el.params.format_intro_image == null">

                    </a>

                     
                    <a href="[[ el.url ]]" target="_blank" ng-if="el.type=='external'">

                        <img src="/image/[[ el.image ]]/[[ el.disk ]]/[[ el.image_path ]]/448/panoram" class="img-big-panoram image-in-box" ng-if="el.params.format_intro_image.name == 'panoram' && el.params.format_intro_image.name == 'panoram'" style="position:static">
						
                        <img src="/image/[[ el.image ]]/[[ el.disk ]]/[[ el.image_path ]]/800" class="img-responsive image-in-box" ng-if="el.params.format_intro_image.name == 'portret'">						

                        <img src="/image/[[ el.image ]]/[[ el.disk ]]/[[ el.image_path ]]/1000" class="img-responsive image-in-box" ng-if="el.params.format_intro_image == null">

                    </a>

                </div>


                <div ng-if="el.disk==null">


                    <a href="/[[ el.id ]]-[[ el.alias ]],[[ el.suffix ]]" ng-if="el.type=='internal'" class="color-banner-col banner-{{ $data->color->classname }}">

                        <img src="[[ el.params.icon ]]" class="img-responsive">

                    </a>


                    <a href="[[ el.url ]]" target="_blank" ng-if="el.type=='external'" class="color-banner-col banner-{{ $data->color->classname }}">

                        <img src="[[ el.params.icon ]]" class="img-responsive">

                    </a>

                </div>


            </div>


        </div>

        <div class="gird-right-double">


            <div class="right-content-elements">


                <div class="author-info">
                    [[ el.author ]]
                </div>

                <div class="element-title">

                    <h2>


                        <a href="/[[ el.id ]]-[[ el.alias ]],[[ el.suffix ]]" ng-if="el.type=='internal'">

                            [[ el.title ]]

                        </a>


                        <a href="[[ el.url ]]" target="_blank" ng-if="el.type=='external'">

                            [[ el.title ]]

                        </a>

                    </h2>

                </div>


                <div class="intro-txt">

                    <div ng-if="el.intro.length>3" ng-bind-html="el.intro">


                    </div>

                    <div ng-if="el.intro.length<=3" ng-bind-html="el.content | cut_text">


                    </div>


                </div>


                <div class="tags-list">



                    <ul class="list-links">

                        <li class="tag-el">
                            <span>
                                {{ $data->link->title }},
                            </span>&nbsp;
                        </li>
                        <li class="tag-el" ng-repeat="cat in el.categories">
                            <span>
                                [[ cat.name ]]<span ng-if="!$last">,</span>
                            </span>&nbsp;
                        </li>

                    </ul>

                </div>

            </div>


        </div>

        <div class="color-line-vertical" style="background-color: #{{ $data->params->color->rgb }}"></div>

    </div>




</div>
