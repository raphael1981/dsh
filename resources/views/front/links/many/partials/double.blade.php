<div
        class="controller-neutral"
        ng-controller="ManyDoubleListController"
        ng-model="lid"
        ng-model="lang_tag"
        ng-init="
            lang_tag='{{ $language['tag'] }}';
            lid={{ $data->link->id  }};
            initData()
        "
>

    <div class="dsh-container double-view double-many-view">




        @foreach($data->many_data as $key=>$el)


            <div class="gird-col gird-half gird-out-off many-half-gird">

                <div class="image-icon" style="position:relative; overflow:hidden">


                    @if($el->type=='external')


                        @if(!is_null($el->disk) && !is_null($el->image) && !is_null($el->image_path))

                            <a href="{{ $el->url }}" target="_blank">

                                @if(isset($el->params->format_intro_image) &&
                                          $el->params->format_intro_image->name=="panoram")
                                    <img src="/image/{{$el->image}}/{{$el->disk}}/{{$el->image_path}}/600/panoram" class="img-middle-panoram image-in-box">
                                @else
                                    <img src="{{ \App\Services\DshHelper::createUrlFromDiskParams($el->image, $el->disk, $el->image_path) }}" class="img-responsive image-in-box">
                                @endif

                            </a>

                        @else

                            <a href="{{ $el->url }}" class="color-banner-col banner-{{ $data->color->classname }}" target="_blank">

                                <img src="{{ $el->params->icon }}" class="img-responsive">

                            </a>

                        @endif

                    @endif

                    @if($el->type=='internal')

                            @if(!is_null($el->disk) && !is_null($el->image) && !is_null($el->image_path))

                                <a href="/{{ $el->id }}-{{ $el->alias }},{{ $el->suffix }}">

                                    @if(isset($el->params->format_intro_image) &&
                                              $el->params->format_intro_image->name=="panoram")
                                        <img src="/image/{{$el->image}}/{{$el->disk}}/{{$el->image_path}}/600/panoram" class="img-middle-panoram image-in-box">
                                    @else
                                        <img src="{{ \App\Services\DshHelper::createUrlFromDiskParams($el->image, $el->disk, $el->image_path) }}" class="img-responsive image-in-box">
                                    @endif

                                </a>

                            @else

                                <a href="/{{ $el->id }}-{{ $el->alias }},{{ $el->suffix }}" class="color-banner-col banner-{{ $data->color->classname }}">

                                    <img src="{{ $el->params->icon }}" class="img-responsive">

                                </a>

                            @endif

                    @endif



                </div>

                <div class="many-double-content">

                    <div class="title-show">


                       @if($el->type=='external')

                            <h2>

                                <a href="{{ $el->url }}" target="_blank">
                                    {{ $el->title }}
                                </a>

                            </h2>

                       @endif


                       @if($el->type=='internal')

                               <h2>

                                   <a href="/{{ $el->id }}-{{ $el->alias }},{{ $el->suffix }}">
                                       {{ $el->title }}
                                   </a>

                               </h2>

                       @endif




                    </div>


                    <div class="many-intro-text">

                        {!! \App\Services\DshHelper::trimDescByWords($el->intro, 30) !!}

                    </div>


                    <div class="tags-list">


                        @if($el->type=='internal')

                            <ul class="list-links">
                                <?php
                                $ag_lnk= \App\Entities\Content::find($el->id)->links();
                                $ag_coll = $ag_lnk->get();
                                $ag_lkey = $ag_lnk->count()-1;
                                ?>
                                @foreach($ag_coll as $k=>$ln)

                                    @if($ag_lkey==$k)
                                        <li class="tag-el">
                                            {{ $ln->title }}&nbsp;&nbsp;
                                        </li>
                                    @else
                                        <li class="tag-el">
                                            {{ $ln->title }},&nbsp;&nbsp;
                                        </li>
                                    @endif

                                @endforeach

                            </ul>

                        @endif


                        @if($el->type=='external')

                            <a href="{{ $el->url }}" target="_blank" class="link-external-tag">
                                {{ str_replace('http://','',$el->url) }}
                            </a>

                        @endif

                    </div>

                </div>

                <div class="color-line" style="background: #{{ $data->params->color->rgb }};"></div>

            </div>


        @endforeach



    </div>


</div>