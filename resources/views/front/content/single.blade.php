<div class="dsh-container content-single-cont" style="margin-bottom:0px; padding-bottom:0px;">



    <div class="gird-row transparent-gird-row" style="margin-bottom:0px; padding-bottom:0px;margin-top:0px; padding-top:0px">

        <div class="merging-cont">

            <div class="gird-col gird-single no-margin transparent-col first">

                <div class="show-categories">
                    <div class="head-box-64px">
                        <h2>
                            {{ $data->viewprofile->name }}
                        </h2>
                    </div>



                    <ul class="cats">
                        @foreach($data->categories as $key=>$cat)
                            <li>
                                <span class="tag">
                                    {{ $cat->name }}
                                </span>

                            </li>
                        @endforeach
                    </ul>

                </div>


                @if($data->viewprofile->name=='Edukacja')

                    @if($data->single->id == 198)
                        <div class="single-contact-view">
                            Aby rozpocząć przygodę z Warszawiakiem wejdź na stronę <a href="http://www.razdwatrzywarszawiak.pl" target="_blank">www.razdwatrzywarszawiak.pl</a>, zarejestruj się i&nbsp;pobierz materiały
                        </div>
                    @else
{{--                        <div class="single-contact-view">
                            Zapisy pod numerem: 22 255 05 25<br>
                            Zajęcia są bezpłatne.
                        </div>--}}
                    @endif

                @endif

                <div class="clear-fix"></div>


                @if(count($data->attachments)>0)

                <div class="show-attachments">
                    <div class="head-min-64">
                        <span class="text-lowercase attach-head">
                            do pobrania
                        </span>
                    </div>
                    <div class="attachments-list">
                        @foreach($data->attachments as $key=>$attach)

                            <div class="attach-element">
                                <div class="attach-icon">
                                    <a href="{{ url('media'.$attach->media_relative_path) }}">
                                        <span class="hidden-link">Link do załącznika {{ $attach->filename }}</span>
                                        <img
                                                src="{{ asset('/attach_icons/file_icon_'.$data->viewprofile->color->classname.'.svg') }}"
                                                class="img-responsive"
                                                title="Ikona pliku"
                                                alt="Ikona pliku"
                                        >
                                    </a>
                                </div>
                                <div class="attach-name">
                                    <a href="{{ url('media'.$attach->media_relative_path) }}">
                                        <span class="hidden-link">Link do załącznika {{ $attach->filename }}</span>
                                        <span class="attach-name-span">
                                            @if($attach->title=='')
                                                {{ $attach->filename }}
                                            @else
                                                {{ $attach->title }}
                                            @endif
                                        </span>
                                    </a>

                                </div>
                            </div>

                        @endforeach
                    </div>

                </div>

                @endif

                {{--<div class="show-www">--}}

                    {{--<div class="head-min-64">--}}
                        {{--<span class="text-lowercase www-head">--}}
                            {{--polecane strony--}}
                        {{--</span>--}}
                    {{--</div>--}}

                    {{--<div class="recomm-sites">--}}

                    {{--</div>--}}

                {{--</div>--}}

            </div>

            <div class="head-color-480-to-end">
                <h2 class="section-title">
                    {{ $data->viewprofile->name }}
                </h2>

                <h3 class="content-title">
                    {{ $data->single->title }}
                </h3>

                <div class="data-of-single-white">

                </div>

            </div>

            <div class="gird-col gird-double transparent-col second">

                <div class="head-of-single hide-less-480">
                    <h2>
                        {{ $data->single->title }}
                    </h2>
                </div>

                {{--@if(!is_null($data->single->published_at))--}}
                    {{--<div class="data-of-single hide-less-480">--}}
                        {{--<div class="date-col">--}}
                            {{--{{ \App\Services\DshHelper::refactorDateForLangFromTimestamp($data->single->published_at,$language['tag'])->date }}--}}
                        {{--</div>--}}
                        {{--<div class="vertical-date-line">--}}
                            {{--<div class="line"></div>--}}
                        {{--</div>--}}
                        {{--<div class="time-col">--}}
                           {{--godzina {{ \App\Services\DshHelper::refactorDateForLangFromTimestamp($data->single->published_at,$language['tag'])->time }}--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--@endif--}}

                <hr class="margin-bottom-48-minus hide-less-480">

                <div class="mobile-tags-1024-640 hide-less-480">

                    <ul class="cats">
                        @foreach($data->categories as $key=>$cat)
                            <li>
                                <a href="#">
                                    {{ $cat->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>


                </div>


                <div class="mobile-tags-480-end">

                    @foreach($data->categories as $key=>$cat)
                        <span>
                            {{ $cat->name }}
                        </span>@if($key==(count($data->categories)-1))&nbsp;@else,&nbsp;@endif
                    @endforeach

                </div>

                <div class="content-of-single">

                    @if(!is_null($data->single->disk) && !is_null($data->single->image) && !is_null($data->single->path))

                        <img
                                src="{{ url('image/'.$data->single->image.'/'.$data->single->disk.'/'.$data->single->path.'/700') }}"
                                class="img-responsive"
                                title="Obrazek wydarzenia {{ $data->single->title }}"
                                alt="Obrazek wydarzenia {{ $data->single->title }}"
                        >

                    @endif

                    {!! $data->single->content !!}

                </div>

                <div class="galleries-single">
                    @foreach($data->galleries as $key=>$g)

                        <div class="gallery-head">
                            <h2>
                                {{ $g->gallery->title }}
                            </h2>
                        </div>


                        <div class="swp-gallery"
                                {{--ng-controller="GalleryController"--}}
                                {{--ng-model="hidden_image"--}}
                                {{--ng-model="hidden_shadow"--}}
                                {{--ng-init="hidden_image='hidden';hidden_shadow=''"--}}
                             itemscope
                             itemtype="http://schema.org/ImageGallery"
                        >

                            @if($g->is_more_open)

                                @foreach($g->pictures as $k=>$p)

                                    @if($k<8)
                                        {{--<div class="thumb">--}}

                                        <figure itemprop="associatedMedia" class="thumb" itemscope itemtype="http://schema.org/ImageObject">
                                            <a href="{{ url($p->real_url) }}" itemprop="contentUrl" data-size="{{ $p->size_to_gallery }}">
                                                @if($k==7)
                                                    <img
                                                            src="{{ url($p->real_url) }}"
                                                            itemprop="thumbnail"
                                                            class="img-responsive" alt=""
                                                            is-more="false"
                                                            title="@if($p->translations->{$language['tag']}=='') {{ $p->translations->{$language['tag']} }}
                                                            @else
                                                                    Obrazek z gallerii {{ $g->gallery->title }} dla wydarzenia {{ $data->single->title }}
                                                            @endif"
                                                            alt="title="@if($p->translations->{$language['tag']}=='') {{ $p->translations->{$language['tag']} }}
                                                            @else
                                                            Obrazek z gallerii {{ $g->gallery->title }} dla wydarzenia {{ $data->single->title }}
                                                @endif"

                                                />
                                                @else
                                                    <img
                                                            src="{{ url($p->real_url) }}"
                                                            itemprop="thumbnail"
                                                            class="img-responsive" alt=""
                                                            is-more="false"
                                                            title="@if($p->translations->{$language['tag']}=='') {{ $p->translations->{$language['tag']} }}
                                                            @else
                                                                    Obrazek z gallerii {{ $g->gallery->title }} dla wydarzenia {{ $data->single->title }}
                                                            @endif"
                                                            alt="title="@if($p->translations->{$language['tag']}=='') {{ $p->translations->{$language['tag']} }}
                                                            @else
                                                            Obrazek z gallerii {{ $g->gallery->title }} dla wydarzenia {{ $data->single->title }}
                                                @endif"
                                                />
                                                @endif
                                            </a>

                                            <figcaption itemprop="caption description">
                                                {{ $p->translations->{$language['tag']} }}
                                            </figcaption>
                                            @if($k==7)
                                                <div class="shadow-more" ng-class="hidden_shadow" ng-click="hidden_image='';hidden_shadow='hidden'" is-more="true">
                                                        <span class="plus-text" ng-click="hidden_image='';hidden_shadow='hidden'" is-more="true">
                                                             + {{ $g->plus }}
                                                        </span>
                                                </div>
                                            @endif
                                        </figure>
                                        {{--</div>--}}
                                    @else
                                        {{--<div class="thumb hidden" ng-class="hidden_image">--}}
                                        <figure itemprop="associatedMedia" class="thumb" ng-class="hidden_image" ng-init="hidden_image='hidden'" itemscope itemtype="http://schema.org/ImageObject">
                                            <a href="{{ url($p->real_url) }}" itemprop="contentUrl" data-size="{{ $p->size_to_gallery }}">
                                                <img
                                                        src="{{ url($p->real_url) }}" itemprop="thumbnail" class="img-responsive"
                                                        title="@if($p->translations->{$language['tag']}=='') {{ $p->translations->{$language['tag']} }}
                                                        @else
                                                                Obrazek z gallerii {{ $g->gallery->title }} dla wydarzenia {{ $data->single->title }}
                                                        @endif"
                                                        alt="title="@if($p->translations->{$language['tag']}=='') {{ $p->translations->{$language['tag']} }}
                                                        @else
                                                        Obrazek z gallerii {{ $g->gallery->title }} dla wydarzenia {{ $data->single->title }}
                                                @endif"
                                                />
                                            </a>
                                            <figcaption itemprop="caption description">
                                                {{ $p->translations->{$language['tag']} }}
                                            </figcaption>
                                        </figure>
                                        {{--</div>--}}

                                    @endif

                                @endforeach

                            @else

                                @foreach($g->pictures as $k=>$p)

                                    {{--<div class="thumb">--}}
                                    <figure itemprop="associatedMedia" class="thumb" itemscope itemtype="http://schema.org/ImageObject">
                                        <a href="{{ url($p->real_url) }}" itemprop="contentUrl" data-size="{{ $p->size_to_gallery }}">
                                            <img src="{{ url($p->real_url) }}" itemprop="thumbnail" class="img-responsive"
                                                 title="@if($p->translations->{$language['tag']}=='') {{ $p->translations->{$language['tag']} }}
                                                 @else
                                                         Obrazek z gallerii {{ $g->gallery->title }} dla wydarzenia {{ $data->single->title }}
                                                 @endif"
                                                 alt="title="@if($p->translations->{$language['tag']}=='') {{ $p->translations->{$language['tag']} }}
                                                 @else
                                                 Obrazek z gallerii {{ $g->gallery->title }} dla wydarzenia {{ $data->single->title }}
                                            @endif"
                                            />
                                        </a>
                                        <figcaption itemprop="caption description">
                                            {{ $p->translations->{$language['tag']} }}
                                        </figcaption>
                                    </figure>
                                    {{--</div>--}}

                                @endforeach

                            @endif


                        </div>

                    @endforeach

                    @include('front.partials.swipestructure')

                </div>

                {{--<hr class="margin-bottom-48-minus">--}}

                {{--<div class="social-bottom-beam">--}}
                    {{--<ul class="list-inline">--}}
                        {{--<li class="info-li">--}}
                            {{--udostępnij:--}}
                        {{--</li>--}}
                        {{--<li class="social-bt" id="shareBtnFacebook">--}}
                            {{--facebook--}}
                        {{--</li>--}}
                        {{--<li class="social-bt">--}}
                            {{--<a class="twitter-share-button" target="_blank"--}}
                               {{--onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"--}}
                               {{--href="https://twitter.com/intent/tweet">--}}
                                {{--twitter--}}
                            {{--</a>--}}

                        {{--</li>--}}
                        {{--<li class="social-bt">--}}
                            {{--<a href="https://plus.google.com/share?url={{ url()->current() }}"--}}
                               {{--onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">--}}
                                {{--google+--}}
                            {{--</a>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</div>--}}


                @if(count($data->attachments)>0)

                    <div class="mobile-attachments-1024-640">

                        <div class="head-min-64">
                        <span class="text-lowercase attach-head">
                            do pobrania
                        </span>
                        </div>
                        <div class="attachments-list">
                            @foreach($data->attachments as $key=>$attach)

                                <div class="attach-element">
                                    <div class="attach-icon">
                                        <a href="{{ url('media'.$attach->media_relative_path) }}">
                                            <img src="{{ asset('/attach_icons/file_icon_'.$data->viewprofile->color->classname.'.svg') }}" class="img-responsive">
                                        </a>
                                    </div>
                                    <div class="attach-name">
                                        <a href="{{ url('media'.$attach->media_relative_path) }}">
                                        <span class="attach-name-span">
                                            @if($attach->title=='')
                                                {{ $attach->filename }}
                                            @else
                                                {{ $attach->title }}
                                            @endif
                                        </span>
                                        </a>

                                    </div>
                                </div>

                            @endforeach
                        </div>

                    </div>

                @endif


            </div>


        </div>

    </div>

</div>

@if($data->single->logotypes()->count()>0)

<div class="logotypes-container full-width">

    <div class="dsh-container logotypes">


        <div class="gird-row transparent-gird-row">

            <div class="merging-cont">


                <div class="gird-col gird-single no-margin transparent-col first first-logotypes">
                    &nbsp;
                </div>


                <div class="gird-col gird-double transparent-col second second-logotypes">
                    <show-front-logotypes id="{{ $data->id }}" entitytype="content"></show-front-logotypes>
                </div>


            </div>


        </div>


    </div>


</div>

@endif

<div class="dsh-container content-single-cont" style="margin-top:0px; padding-top:0px">

    <div class="gird-row transparent-gird-row">

        <div class="gird-col gird-single no-margin transparent-col first">
            &nbsp;
        </div>


        <div class="gird-col gird-double transparent-col second">


            <hr class="margin-bottom-48-minus">

                <div class="social-bottom-beam">
                    <ul class="list-inline">
                        <li class="info-li">
                            udostępnij:
                        </li>
                        <li class="social-bt" id="shareBtnFacebook">
                            facebook
                        </li>
                        <li class="social-bt">
                            <a class="twitter-share-button" target="_blank"
                               onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"
                               href="https://twitter.com/intent/tweet">
                                twitter
                            </a>

                        </li>
                        <li class="social-bt">
                            <a href="https://plus.google.com/share?url={{ url()->current() }}"
                               onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">
                                google+
                            </a>
                        </li>
                    </ul>
                </div>


        </div>


    </div>


</div>