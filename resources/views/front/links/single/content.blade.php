<div class="dsh-container content-single-cont">

    <div class="gird-row transparent-gird-row">

        <div class="merging-cont">

            <div class="gird-col gird-single no-margin transparent-col first">

                <div class="show-categories">
                    <div class="head-box-64px">
                        <h2>
                            {{ $data->viewprofile->name }}
                        </h2>
                    </div>

                    <ul class="cats">
                        @foreach($data->links as $key=>$link)
                            <li>
                                <a href="{{ url($link->link) }}">
                                    {{ $link->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                </div>

                <div class="clear-fix"></div>

                <div class="show-attachments">
                    <div class="head-min-64">
                        <span class="text-lowercase attach-head">
                            do pobrania
                        </span>
                    </div>
                    <div class="attachments-list">
                        @foreach($data->attachments as $key=>$attach)

                            <div class="attach-element">
                                {{--<div class="attach-icon">--}}
                                {{--<a href="{{ url('media'.$attach->media_relative_path) }}">--}}
                                {{--<img src="{{ $attach->params->icon }}" class="img-responsive">--}}
                                {{--</a>--}}
                                {{--</div>--}}

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

                <div class="show-www">

                    <div class="head-min-64">
                        <span class="text-lowercase www-head">
                            polecane strony
                        </span>
                    </div>

                    <div class="recomm-sites">

                    </div>

                </div>

            </div>

            <div class="gird-col gird-double transparent-col second">

                <div class="head-of-single">
                    <h2>
                        {{ $data->single->title }}
                    </h2>
                </div>

                @if(!is_null($data->single->published_at))
                    <div class="data-of-single">
                        <div class="date-col">
                            {{ \App\Services\DshHelper::refactorDateForLangFromTimestamp($data->single->published_at,$language['tag'])->date }}
                        </div>
                        <div class="vertical-date-line">
                            <div class="line"></div>
                        </div>
                        <div class="time-col">
                            godzina {{ \App\Services\DshHelper::refactorDateForLangFromTimestamp($data->single->published_at,$language['tag'])->time }}
                        </div>
                    </div>
                @endif

                <hr class="margin-bottom-48-minus">

                <div class="content-of-single">

                    @if(!is_null($data->single->disk) && !is_null($data->single->image) && !is_null($data->single->path))

                        <img src="{{ url('image/'.$data->single->image.'/'.$data->single->disk.'/'.$data->single->path.'/700') }}" class="img-responsive">

                    @endif

                    {!! $data->single->content !!}

                </div>

                <hr class="margin-bottom-48-minus">

                <div class="social-bottom-beam">
                    <ul class="list-inline">
                        <li class="info-li">
                            udostępnij:
                        </li>
                        <li class="social-bt">
                            facebook
                        </li>
                        <li class="social-bt">
                            twitter
                        </li>
                        <li class="social-bt">
                            google+
                        </li>
                    </ul>
                </div>


            </div>


        </div>

    </div>

</div>