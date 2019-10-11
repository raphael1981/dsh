<div class="neutral-con" ng-controller="SearchController">

    <div class="search-beam-form" ng-init="initData()">
        <div class="dsh-container none-background-cnt">

            <div class="gird-col out-off full-width-gird transparent-col">
                <div class="col-half-beam-search">
                    <h3 class="title-search">{{ __('translations.search_results') }}</h3>
                </div>
                <div class="col-half-beam-search form-search">

                    <form class="form-horizontal" ng-submit="searchIndexResult()">

                        <div class="form-group">
                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <input
                                        name="search"
                                        type="text"
                                        ng-model="search"
                                        ng-change="searchIndexResult()"
                                        placeholder="{{ __('translations.search_title') }}"
                                >
                            </div>
                            <div class="col-xs-4 col-sm-4 col-md-4">
                                <img src="{{ asset('images/clear-search.svg') }}" ng-click="search=''">
                            </div>
                        </div>


                    </form>

                </div>
            </div>

        </div>
    </div>

    <div class="dsh-container none-background-cnt result-of-search-cnt">

        <div class="if-result-cnt" ng-if="show_result">

            <div class="gird-row"  ng-repeat="r in $parent.view_results">

                <div class="gird-col-search-result single">

                    <div
                            class="col-half image-show"
                            style="background: url('{{ url('') }}[[ r.image ]]');background-size: cover"
                            ng-if="r.is_image"
                    >
                        <img src="[[ r.image ]]" class="img-responsive opacity-zero">
                    </div>

                    <div
                            class="col-half image-show"
                            style="background-color: #[[ r.rgb ]]"
                            ng-if="!r.is_image"
                    >
                        <img src="[[ r.icon ]]" class="img-responsive">
                    </div>

                    <div class="col-half">

                    </div>

                </div>

                <div class="gird-col-search-result double">
                    <h2 class="h2-s-res">
                        <a ng-if="r.is_external" href="[[ r.url ]]" target="_blank">
                            [[ r.title ]]
                        </a>
                        <a ng-if="!r.is_external" href="[[ r.link ]]">
                            [[ r.title ]]
                        </a>
                    </h2>

                    <div class="tags">


                        <span
                                class="cat-tags"
                                ng-repeat="c in r.categories"
                        >
                            <span ng-if="$last">
                                [[ c.name ]]
                            </span>
                            <span ng-if="!$last">
                                [[ c.name ]],
                            </span>
                        </span>

                    </div>

                </div>


                <div class="line-search-color" style="background: #[[ r.rgb ]]"></div>

            </div>

            <div class="loading-res-search-beam" ng-if="count!=now_iter || count==0">
                <img src="{{ asset('images/loading-more-icon.svg') }}" class="img-responsive loading-svg" ng-click="addMoreResults()">
            </div>

        </div>

    </div>

</div>