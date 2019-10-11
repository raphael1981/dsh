<div class="uns-first-row unstandard-desktop-links">
    <div class="uns-section-name col">
        <div class="head-of-link">
            <h2>
                {{ $data->link->title }}
            </h2>
        </div>
    </div>

    <div class="uns-section-menu col" id="plus-content-height">
        <ul class="filter-labels">
            @foreach($data->other_links as $k=>$l)
                <li class="element">
                    @if($l->active)
                        <a href="{{ url($l->link) }}" class="active">
                            {{ $l->title }}
                        </a>
                    @else
                        <a href="{{ url($l->link) }}">
                            {{ $l->title }}
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

</div>

<div class="head-mobile-filter-unstadard">
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

<div class="filters-mobiles-rel-cont" ng-class="filter_mobile_open" ng-init="filter_mobile_open='hidden'">
    <div class="filters-mobiles">

        <ul class="cat-labels-mobile">
            @foreach($data->other_links as $k=>$l)
                <li class="element">
                    @if($l->active)
                        <a href="{{ url($l->link) }}" class="filter active">
                            {{ $l->title }}
                        </a>
                    @else
                        <a href="{{ url($l->link) }}" class="filter">
                            {{ $l->title }}
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>

    </div>
</div>