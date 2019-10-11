<div
        class="controller-neutral"
        ng-controller="ManyOneListController"
        ng-model="lid"
        ng-model="lang_tag"
        ng-init="
            lang_tag='{{ $language['tag'] }}';
            lid={{ $data->link->id  }};
            initData()
        "
>

    <div class="dsh-container one-view one-many-view">



        @foreach($data->many_data as $key=>$el)


            <div class="gird-col out-off full-width-gird">


                <div class="grid-left-one">


                    <div class="image-cont">


                            @if(!is_null($el->disk) && !is_null($el->image) && !is_null($el->image_path))

                                <a href="/{{ $el->id }}-{{ $el->alias }},{{ $el->suffix }}">

                                    <img src="{{ \App\Services\DshHelper::createUrlFromDiskParams($el->image, $el->disk, $el->image_path) }}" class="img-responsive image-in-box">

                                </a>

                            @else

                                <a href="/{{ $el->id }}-{{ $el->alias }},{{ $el->suffix }}" class="color-banner-col banner-{{ $data->color->classname }}">

                                    <img src="{{ $el->params->icon }}" class="img-responsive">

                                </a>

                            @endif


                    </div>

                </div>


                <div class="gird-right-double">


                    <div class="right-content-elements">


                            @if($el->lgb_linkgables_type=='App\Entities\Agenda')

                                {{--<div class="author-info">--}}
                                    {{--{{ $el }}--}}
                                {{--</div>--}}

                            @endif

                            @if($el->lgb_linkgables_type=='App\Entities\Content')

                                @if(!is_null($el->cnt_author))
                                   <div class="author-info">
                                    {{ $el->cnt_author }}
                                   </div>
                                @endif

                            @endif


                        <div class="element-title">

                            @if($el->lgb_linkgables_type=='App\Entities\Agenda')

                                <h2>
                                    <a href="/{{ $el->ag_id }}-{{ $el->ag_alias }},{{ $el->suffix }}">
                                        {{ $el->ag_title }}
                                    </a>
                                </h2>

                            @endif

                            @if($el->lgb_linkgables_type=='App\Entities\Content')

                                <h2>
                                    <a href="/{{ $el->cnt_id }}-{{ $el->cnt_alias }},{{ $el->suffix }}">
                                        {{ $el->cnt_title }}
                                    </a>
                                </h2>

                            @endif

                        </div>



                        <div class="intro-txt">

                            @if($el->lgb_linkgables_type=='App\Entities\Agenda')

                                {{ $el->ag_intro }}

                            @endif

                            @if($el->lgb_linkgables_type=='App\Entities\Content')

                                 {{ $el->cnt_intro }}

                            @endif

                        </div>

                        <div class="tags-list">

                            @if($el->lgb_linkgables_type=='App\Entities\Agenda')

                                <ul class="list-links">
                                    <?php
                                        $ag_lnk= \App\Entities\Agenda::find($el->cnt_id)->links();
                                        $ag_coll = $ag_lnk->get();
                                        $ag_lkey = $ag_lnk->count()-1;
                                    ?>
                                    @foreach($ag_coll as $k=>$ln)

                                        @if($ag_lkey==$k)
                                            <li class="tag-el">
                                                <a href="#">{{ $ln->title }}</a>
                                            </li>
                                        @else
                                            <li class="tag-el">
                                                <a href="#">{{ $ln->title }}</a>,&nbsp;
                                            </li>
                                        @endif

                                    @endforeach
                                </ul>

                            @endif

                            @if($el->lgb_linkgables_type=='App\Entities\Content')

                                <ul class="list-links">
                                <?php
                                    $cnt_lnk = \App\Entities\Content::find($el->cnt_id)->links();
                                    $cnt_coll = $cnt_lnk->get();
                                    $cntlkey = $cnt_lnk->count()-1;
                                ?>
                                @foreach($cnt_coll as $k=>$ln)

                                    @if($cntlkey==$k)
                                        <li class="tag-el">
                                            <a href="{{ url($ln->link) }}">{{ $ln->title }}</a>&nbsp;&nbsp;
                                        </li>
                                    @else
                                        <li class="tag-el">
                                             <a href="{{ url($ln->link) }}">{{ $ln->title }}</a>,&nbsp;&nbsp;
                                          </li>
                                    @endif

                                @endforeach
                                </ul>

                            @endif

                        </div>

                    </div>

                </div>

                <div class="color-line-vertical" style="background: #{{ $data->params->color->rgb }};"></div>

            </div>


        @endforeach



    </div>


</div>