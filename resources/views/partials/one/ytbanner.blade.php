<div class="{{ $colclass }}">

    <div class="merging-cont">
        <div class="gird-col gird-double no-margin first one-element">

            <div class="image-icon-big">
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
                        <a href="https://www.youtube.com/watch?v={{ $data->youtube_id }}" target="_blank">
                            <span class="hidden-link">Link do filmu</span>
                            <span class="cut_title">{{ \App\Services\DshHelper::cutTitleBySigns($data->youtube_title,55) }}</span>
                        </a>
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