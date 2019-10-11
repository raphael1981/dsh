<div class="dsh-container double-view double-many-view">



    <div class="gird-col gird-half gird-out-off many-half-gird" ng-repeat="el in data.result.not_group">

        <div class="image-icon" style="position:relative">


            <div ng-if="el.disk!=null && el.alias!=null && el.suffix!=null">

                <a href="/[[ el.id ]]-[[ el.alias ]],[[ el.suffix ]]" ng-if="el.type=='internal'">

                    <img src="/image/[[ el.image ]]/[[ el.disk ]]/[[ el.image_path ]]/600/panoram" class="img-middle-panoram image-in-box" ng-if="el.params.format_intro_image != null && el.params.format_intro_image.name == 'panoram'">


                    <img src="/image/[[ el.image ]]/[[ el.disk ]]/[[ el.image_path ]]/1000" class="img-responsive image-in-box" ng-if="el.params.format_intro_image == null">

                </a>

                <a href="[ el.url ]]" ng-if="el.type=='external'" target="_blank">

                    <img src="/image/[[ el.image ]]/[[ el.disk ]]/[[ el.image_path ]]/600/panoram" class="img-middle-panoram image-in-box" ng-if="el.params.format_intro_image != null && el.params.format_intro_image.name == 'panoram'">


                    <img src="/image/[[ el.image ]]/[[ el.disk ]]/[[ el.image_path ]]/1000" class="img-responsive image-in-box" ng-if="el.params.format_intro_image == null">

                </a>

            </div>


            <div ng-if="el.disk==null">


                <a href="/[[ el.id ]]-[[ el.alias ]],[[ el.suffix ]]"  ng-if="el.type=='internal'" class="color-banner-col banner-{{ $data->color->classname }}">

                    <img src="[[ el.params.icon ]]" class="img-responsive">

                </a>

                <a href="[[ el.url ]]"  ng-if="el.type=='external'" class="color-banner-col banner-{{ $data->color->classname }}" target="_blank">

                    <img src="[[ el.params.icon ]]" class="img-responsive">

                </a>

            </div>

        </div>

        <div class="many-double-content">

            <div class="title-show">

                <h2>
                    <a href="/[[ el.id ]]-[[ el.alias ]],[[ el.suffix ]]" ng-if="el.type=='internal'">
                        [[ el.title | cut_title_by_words: 64: windowWidth ]]
                    </a>
                    <a href="[[ el.url ]]" ng-if="el.type=='external'" target="_blank">
                        [[ el.title | cut_title_by_words: 64: windowWidth ]]
                    </a>
                </h2>

            </div>


            <div class="many-intro-text">


                <div ng-if="el.intro.length>3" ng-bind-html="el.intro">


                </div>

                <div ng-if="el.intro.length<=3" ng-bind-html="el.content | cut_text_less">


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

        <div class="color-line" style="background-color: #{{ $data->params->color->rgb }}"></div>

    </div>



</div>
