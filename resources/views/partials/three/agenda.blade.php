<div class="{{ $colclass }}">

    <div class="image-icon">

    </div>

    <div class="date-show">

        @if($data->entity_data->begin==$data->entity_data->end)

            {{ App\Services\DshHelper::getDayNumberFromString($data->entity_data->begin) }}
            &nbsp;
            {{ __('translations.month:'.App\Services\DshHelper::getMonthNumberFromString($data->entity_data->begin)) }}

        @else

            {{ App\Services\DshHelper::getDayNumberFromString($data->entity_data->begin) }}
            {{ __('translations.month:'.App\Services\DshHelper::getMonthNumberFromString($data->entity_data->begin)) }}
            -
            {{ App\Services\DshHelper::getDayNumberFromString($data->entity_data->end) }}
            {{ __('translations.month:'.App\Services\DshHelper::getMonthNumberFromString($data->entity_data->end)) }}

        @endif

    </div>

    <div class="title-show">

    </div>

    <div class="tags">

    </div>

</div>