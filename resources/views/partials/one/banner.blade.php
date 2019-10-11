<div class="{{ $colclass }}">

    <div class="merging-cont">
        <div class="gird-col gird-double no-margin first">

            <div class="image-icon-big" style="position:relative">
                <a href="{{ $data->banner_url }}" target="{{ $data->banner_url_target }}" title="{{ $data->banner_title }}">
                    <span class="hidden-link">Link do {{ $data->banner_title }}</span>
{{--                    <img src="/image/{{ $data->banner->name }}/{{ $data->banner->disk }}/{{ $data->banner->path }}/1200"
                         class="img-responsive"
                         title="Grafika banera dla {{ $data->banner_title }}"
                         alt="Grafika banera dla {{ $data->banner_title }}"
                    >--}}
                    @if(isset($data->banner->format) && $data->banner->format->name == 'panoram')
                        <img src="/image/{{ $data->banner->name }}/{{ $data->banner->disk }}/{{ $data->banner->path }}/448/{{ $data->banner->format->name }}"
                             class="img-big-{{ $data->banner->format->name }}"
                             title="Grafika banera dla {{ $data->banner_title }}"
                             alt="Grafika banera dla {{ $data->banner_title }}"
                        >
                    @else
                        <img src="/image/{{ $data->banner->name }}/{{ $data->banner->disk }}/{{ $data->banner->path }}/1200"
                             class="img-responsive"
                             title="Grafika banera dla {{ $data->banner_title }}"
                             alt="Grafika banera dla {{ $data->banner_title }}"
                        >
                    @endif

                </a>
            </div>

        </div>

        <div class="gird-col gird-single second col-with-border-right">

            <div class="content-gird-show-big">

                <div class="date-show">
                    {{-- {{ App\Services\DshHelper::getDayNumberFromString(date('Y-m-d')) }}&nbsp;
                    {{ __('translations.month:'.App\Services\DshHelper::getMonthNumberFromString(date('Y-m-d'))) }}
                    &nbsp;{{ App\Services\DshHelper::getYearNumberFromString(date('Y-m-d')) }} --}}
                </div>

                <div class="title-show">
                    <h2>
                        <a href="{{ $data->banner_url }}" target="{{ $data->banner_url_target }}" title="{{ $data->banner_title }}">
                            <span class="hidden-link">Link do {{ $data->banner_title }}</span>
                            <span class="cut_title">{{ \App\Services\DshHelper::cutTitleBySigns($data->banner_title,80) }}</span>
                        </a>
                    </h2>
                </div>

                <div class="desc">
                    {{ App\Services\DshHelper::trimDescByWords($data->banner_desc) }}
                </div>

                <div class="tags">

                </div>

            </div>

            <div class="color-line-vertical" style="background-color: #{{ $data->border_color->rgb }}"></div>
        </div>
    </div>

</div>