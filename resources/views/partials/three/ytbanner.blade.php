<div class="{{ $colclass }} trio-element" home="content-column">

    <div class="image-icon">

        <a href="https://www.youtube.com/watch?v={{ $data->youtube_id }}" target="_blank">
            <span class="hidden-link">Link do filmu</span>
            <img
                    src="https://img.youtube.com/vi/{{ $data->youtube_id }}/0.jpg"
                    class="img-responsive"
                    title="Obrazek filmu na youtube {{ $data->youtube_title }}"
                    alt="Obrazek filmu na youtube {{ $data->youtube_title }}"
            >
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
                <a href="https://www.youtube.com/watch?v={{ $data->youtube_id }}" target="_blank">
                    <span class="hidden-link">Link do filmu</span>
                    <span class="cut_title">{{ \App\Services\DshHelper::cutTitleBySigns($data->youtube_title,84) }}</span>
                </a>
            </h2>
        </div>

        {{--<div class="desc">--}}
            {{--{{ $data->youtube_desc }}--}}
        {{--</div>--}}

        <div class="tags">

        </div>
    </div>
    <div class="color-line" style="background-color: #{{ $data->border_color->rgb }}"></div>

</div>