<div class="{{ $colclass }}">

    <div class="merging-cont">
        <div class="gird-col gird-double no-margin first one-element">

            <div class="image-icon-big">
            <iframe
                    style="width: 100%"
                    height=""
                    src="https://www.youtube.com/embed/{{ $data->youtube_id }}"
                    frameborder="0"
                    allowfullscreen></iframe>
            </div>

        </div>

        <div class="gird-col gird-single second col-with-border-right">

            <div class="content-gird-show-big">

                <div class="date-show">
                    {{--{{ App\Services\DshHelper::getDayNumberFromString(date('Y-m-d')) }}&nbsp;--}}
                    {{--{{ __('translations.month:'.App\Services\DshHelper::getMonthNumberFromString(date('Y-m-d'))) }}--}}
                    {{--&nbsp;{{ App\Services\DshHelper::getYearNumberFromString(date('Y-m-d')) }}--}}
                </div>

                <div class="title-show">
                    <h2>
                        <span class="cut_title">{{ \App\Services\DshHelper::cutTitleBySigns($data->youtube_title,55) }}</span>
                    </h2>
                </div>

                <div class="desc">
                    {{ App\Services\DshHelper::trimDescByWords($data->youtube_desc) }}
                </div>

                <div class="tags">

                </div>

            </div>

            <div class="color-line-vertical" style="background-color: #{{ $data->border_color->rgb }}"></div>
        </div>
    </div>

</div>