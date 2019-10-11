<div class="dsh-container content-single-cont">

    <div class="gird-row transparent-gird-row">

        <div class="merging-cont">

            <div class="gird-col gird-single no-margin transparent-col first">

                <div class="show-categories">
                    <div class="head-box-64px">
                        <h2>
                            Ogłoszenie
                        </h2>
                    </div>

                </div>

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
                                {{--<div class="attach-icon">--}}
                                    {{--<a href="{{ url('media'.$attach->media_relative_path) }}">--}}
                                        {{--<img src="{{ $attach->params->icon }}" class="img-responsive">--}}
                                    {{--</a>--}}
                                {{--</div>--}}
                                <div class="attach-name">
                                    <a href="{{ url('media'.$attach->media_relative_path) }}">
                                        <span class="attach-name-span">
                                            {{ $attach->filename }}
                                        </span>
                                    </a>

                                </div>
                            </div>

                        @endforeach
                    </div>

                </div>

                @endif


            </div>

            <div class="head-color-480-to-end">
                <h2 class="section-title">
                    {{ $data->viewprofile->name }}
                </h2>

                <h3 class="content-title">
                    {{ $data->single->title }}
                </h3>

                <div class="data-of-single-white">
                        <div class="date-col">
                            {{ \App\Services\DshHelper::refactorDateForLangFromTimestamp($data->single->created_at,$language['tag'])->date }}
                        </div>
                        <div class="vertical-date-line">
                            <div class="line"></div>
                        </div>
                        <div class="time-col">
                            godzina {{ \App\Services\DshHelper::refactorDateForLangFromTimestamp($data->single->created_at,$language['tag'])->time }}
                        </div>
                </div>

            </div>

            <div class="gird-col gird-double transparent-col second">

                <div class="head-of-single">
                    <h2>
                        {{ $data->single->title }}
                    </h2>
                </div>

                <div class="date-col hide-less-480">
                    <div class="data-of-single">
                    <div class="date-col">
                    {{ \App\Services\DshHelper::refactorDateForLangFromTimestamp($data->single->created_at,$language['tag'])->date }}
                    </div>
                    <div class="vertical-date-line">
                    <div class="line"></div>
                    </div>
                    <div class="time-col">
                    godzina {{ \App\Services\DshHelper::refactorDateForLangFromTimestamp($data->single->created_at,$language['tag'])->time }}
                    </div>
                    </div>
                </div>


                <div class="content-of-single">

                    {!! $data->single->content !!}

                </div>


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


                <div class="mobile-attachments-1024-640">

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
                                            {{ $attach->filename }}
                                        </span>
                                    </a>

                                </div>
                            </div>

                        @endforeach
                    </div>

                </div>


            </div>


        </div>

    </div>

</div>