<div class="{{ $colclass }}" home="content-column">

    <div class="image-icon" style="position:relative">

        <a href="{{ $data->banner_url }}" target="{{ $data->banner_url_target }}" title="{{ $data->banner_title }}">
            <span class="hidden-link">Link do {{ $data->banner_title }}</span>
            @if(isset($data->banner->format) && $data->banner->format->name == 'panoram')
                <img src="/image/{{ $data->banner->name }}/{{ $data->banner->disk }}/{{ $data->banner->path }}/224/{{ $data->banner->format->name }}"
                     class="img-{{ $data->banner->format->name }}"
                     title="Grafika banera dla {{ $data->banner_title }}"
                     alt="Grafika banera dla {{ $data->banner_title }}"
                >
            @else
                <img src="/image/{{ $data->banner->name }}/{{ $data->banner->disk }}/{{ $data->banner->path }}/400"
                     class="img-responsive"
                     title="Grafika banera dla {{ $data->banner_title }}"
                     alt="Grafika banera dla {{ $data->banner_title }}"
                >
            @endif
        </a>

    </div>

    <div class="content-gird-show">
        <div class="date-show">

            {{--{{ App\Services\DshHelper::getDayNumberFromString(date('Y-m-d')) }}&nbsp;--}}
            {{--{{ __('translations.month:'.App\Services\DshHelper::getMonthNumberFromString(date('Y-m-d'))) }}--}}
            {{--&nbsp;{{ App\Services\DshHelper::getYearNumberFromString(date('Y-m-d')) }}--}}

        </div>

        <div class="title-show">
            <h2>
                <a href="{{ $data->banner_url }}" target="{{ $data->banner_url_target }}" title="{{ $data->banner_title }}">
                    <span class="hidden-link">Link do {{ $data->banner_title }}</span>
                    <span class="cut_title">{{ \App\Services\DshHelper::cutTitleBySigns($data->banner_title,84) }}</span>
                </a>
            </h2>
        </div>

        {{--<div class="desc">--}}
            {{--{{ $data->banner_desc }}--}}
        {{--</div>--}}

        <div class="tags">

        </div>
    </div>
    <div class="color-line" style="background-color: #{{ $data->border_color->rgb }}"></div>



</div>