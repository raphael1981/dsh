<div class="{{ $colclass }}">


    <div class="merging-cont">
        <div class="gird-col gird-double no-margin first one-element">

            <div class="image-icon-big" style="position:relative">
                @if($data->entity_data->type=='external')

                    @if($data->image_type=='show_uplaod_image')
                        <a href="{{ $data->entity_data->url }}" target="_blank">
                            <span class="hidden-link">Link do informacji {{ $data->entity_data->title }}</span>
{{--                            <img
                                    src="/image/{{ $data->custom_image->name }}/{{ $data->custom_image->disk }}/{{ $data->custom_image->path }}/1000"
                                    class="img-responsive"
                                    title="Obrazek do informacji {{ $data->entity_data->title }}"
                                    alt="Obrazek do informacji {{ $data->entity_data->title }}"
                            >--}}
                        <!-------------------------------------------------------------------->
                            @if(isset($data->custom_image->format) && $data->custom_image->format->name == 'panoram')
                                <img
                                        src="/image/{{ $data->custom_image->name }}/{{ $data->custom_image->disk }}/{{ $data->custom_image->path }}/448/{{$data->custom_image->format->name}}"
                                        class="img-big-{{ $data->custom_image->format->name }}"
                                        title="Obrazek do informacji {{ $data->entity_data->title }}"
                                        alt="Obrazek do informacji {{ $data->entity_data->title }}"
                                >
                            @else

                                <img
                                        src="/image/{{ $data->custom_image->name }}/{{ $data->custom_image->disk }}/{{ $data->custom_image->path }}/1000"
                                        class="img-responsive"
                                        title="Obrazek do informacji {{ $data->entity_data->title }}"
                                        alt="Obrazek do informacji {{ $data->entity_data->title }}"
                                >
                            @endif
                        <!-------------------------------------------------------------------->
                        </a>
                    @elseif($data->image_type=='show_entity_image')
                        <a href="{{ $data->entity_data->url }}" target="_blank">
                            <span class="hidden-link">Link do informacji {{ $data->entity_data->title }}</span>
                            <img
                                    src="/image/{{ $data->entity_data->image }}/{{ $data->entity_data->disk }}/{{ $data->entity_data->image_path }}/1000"
                                    class="img-responsive"
                                    title="Obrazek do informacji {{ $data->entity_data->title }}"
                                    alt="Obrazek do informacji {{ $data->entity_data->title }}"
                            >
                        </a>
                    @elseif($data->image_type=='show_icon_image')
                        <a href="{{ $data->entity_data->url }}" target="_blank" class="color-banner-col banner-{{ $data->entity_data->params->color->classname }}">
                            <span class="hidden-link">Link do informacji {{ $data->entity_data->title }}</span>
                            <img
                                    src="{{ $data->entity_data->params->icon }}"
                                    class="img-responsive"
                                    title="Obrazek do informacji {{ $data->entity_data->title }}"
                                    alt="Obrazek do informacji {{ $data->entity_data->title }}"
                            >
                        </a>
                    @endif

                @endif

                @if($data->entity_data->type=='internal')
                        @if($data->image_type=='show_uplaod_image')
                            {{--{{ print_r($data->entity_data) }}--}}
                            <a href="{{ App\Services\DshHelper::makeFrontUrl($lang_tag.'/'.$data->entity_data->id.'-'.$data->entity_data->alias.','.$data->entity_data->suffix) }}">
                                <span class="hidden-link">Link do informacji {{ $data->entity_data->title }}</span>
{{--                                <img
                                        src="/image/{{ $data->custom_image->name }}/{{ $data->custom_image->disk }}/{{ $data->custom_image->path }}/1000"
                                        class="img-responsive"
                                        title="Obrazek do informacji {{ $data->entity_data->title }}"
                                        alt="Obrazek do informacji {{ $data->entity_data->title }}"
                                >--}}
<!-------------------------------------------------------------------->
              @if(isset($data->custom_image->format) && $data->custom_image->format->name == 'panoram')
                                    <img
                                            src="/image/{{ $data->custom_image->name }}/{{ $data->custom_image->disk }}/{{ $data->custom_image->path }}/448/{{$data->custom_image->format->name}}"
                                            class="img-big-{{ $data->custom_image->format->name }}"
                                            title="Obrazek do informacji {{ $data->entity_data->title }}"
                                            alt="Obrazek do informacji {{ $data->entity_data->title }}"
                                    >
                                @else

                                    <img
                                            src="/image/{{ $data->custom_image->name }}/{{ $data->custom_image->disk }}/{{ $data->custom_image->path }}/1000"
                                            class="img-responsive"
                                            title="Obrazek do informacji {{ $data->entity_data->title }}"
                                            alt="Obrazek do informacji {{ $data->entity_data->title }}"
                                    >
                                @endif
                            <!-------------------------------------------------------------------->

                            </a>
                        @elseif($data->image_type=='show_entity_image')
                            <a href="{{ App\Services\DshHelper::makeFrontUrl($lang_tag.'/'.$data->entity_data->id.'-'.$data->entity_data->alias.','.$data->entity_data->suffix) }}">
                                <span class="hidden-link">Link do informacji {{ $data->entity_data->title }}</span>
{{--                                <img
                                        src="/image/{{ $data->entity_data->image }}/{{ $data->entity_data->disk }}/{{ $data->entity_data->image_path }}/1000"
                                        class="img-responsive"
                                        title="Obrazek do informacji {{ $data->entity_data->title }}"
                                        alt="Obrazek do informacji {{ $data->entity_data->title }}"
                                >--}}

                                <!-------------------------------------------------------------------->

                                @if(isset($data->entity_data->params->format_intro_image) && $data->entity_data->params->format_intro_image->name == 'panoram')

                                    <img
                                            src="/image/{{ $data->entity_data->image }}/{{ $data->entity_data->disk }}/{{ $data->entity_data->image_path }}/448/panoram"
                                            class="img-big-panoram"
                                            title="Obrazek do informacji {{ $data->entity_data->title }}"
                                            alt="Obrazek do informacji {{ $data->entity_data->title }}"
                                    >

                                @else

                                    <img
                                            src="/image/{{ $data->entity_data->image }}/{{ $data->entity_data->disk }}/{{ $data->entity_data->image_path }}/1000"
                                            class="img-responsive"
                                            title="Obrazek do informacji {{ $data->entity_data->title }}"
                                            alt="Obrazek do informacji {{ $data->entity_data->title }}"
                                    >
                            @endif
                            <!-------------------------------------------------------------------->
                            </a>
                        @elseif($data->image_type=='show_icon_image')
                            <a href="{{ App\Services\DshHelper::makeFrontUrl($lang_tag.'/'.$data->entity_data->id.'-'.$data->entity_data->alias.','.$data->entity_data->suffix) }}" class="color-banner-col banner-{{ $data->entity_data->params->color->classname }}">
                                <span class="hidden-link">Link do informacji {{ $data->entity_data->title }}</span>
                                <img
                                        src="{{ $data->entity_data->params->icon }}"
                                        class="img-responsive"
                                        title="Obrazek do informacji {{ $data->entity_data->title }}"
                                        alt="Obrazek do informacji {{ $data->entity_data->title }}"
                                >
                            </a>
                        @endif
                @endif
            </div>


        </div>

        <div class="gird-col gird-single second col-with-border-right">

            <div class="content-gird-show-big">

                <div class="date-show">

                    {{--@if(!is_null($data->entity_data->published_at))--}}

                        {{--{{ App\Services\DshHelper::getDayFromDateTime($data->entity_data->published_at) }}&nbsp;--}}
                        {{--{{ __('translations.month:'.App\Services\DshHelper::getMonthFromDateTime($data->entity_data->published_at)) }}--}}
                        {{--&nbsp;{{ App\Services\DshHelper::getYearFromDateTime($data->entity_data->published_at) }}--}}

                    {{--@else--}}

                        {{--{{ App\Services\DshHelper::getDayNumberFromString(date('Y-m-d')) }}&nbsp;--}}
                        {{--{{ __('translations.month:'.App\Services\DshHelper::getMonthNumberFromString(date('Y-m-d'))) }}--}}
                        {{--&nbsp;{{ App\Services\DshHelper::getYearNumberFromString(date('Y-m-d')) }}--}}

                    {{--@endif--}}

                </div>

                <div class="title-show">
                    <h2>

                        @if($data->entity_data->type=='external')
                            <a href="{{ $data->entity_data->url }}" target="_blank">
                                <span class="hidden-link">Link do informacji {{ $data->entity_data->title }}</span>
                                <span class="cut_title">{{ \App\Services\DshHelper::cutTitleBySigns($data->entity_data->title,55) }}</span>
                            </a>
                        @endif

                        @if($data->entity_data->type=='internal')
                            <a href="{{ App\Services\DshHelper::makeFrontUrl($lang_tag.'/'.$data->entity_data->id.'-'.$data->entity_data->alias.','.$data->entity_data->suffix) }}">
                                <span class="hidden-link">Link do informacji {{ $data->entity_data->title }}</span>
                                <span class="cut_title">{{ \App\Services\DshHelper::cutTitleBySigns($data->entity_data->title,55) }}</span>
                            </a>
                        @endif

                    </h2>
                </div>

                <div class="desc">
                    {{ App\Services\DshHelper::trimDescByWords($data->entity_data->intro) }}
                </div>

                <div class="tags">
                    @if(!is_null($data->first_tag))
                        <span class="cat-tags">
                            {{ $data->first_tag->title }},&nbsp;
                        </span>
                    @endif
                    @foreach($data->entity_data->categories as $key=>$cat)

                        <span class="cat-tags">
                            {{ $cat->name }}
                        </span>@if(($key+1)!=count($data->entity_data->categories)), @endif&nbsp;

                    @endforeach
                </div>

            </div>

            <div class="color-line-vertical" style="background-color: #{{ $data->border_color->rgb }}"></div>
        </div>
    </div>

</div>