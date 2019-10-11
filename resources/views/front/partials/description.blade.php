<div
        class="dsh-container dsh-container-link-desc {{ $all->params->color->classname }}"
>
    <div class="gird-row">
        <div class="gird-col gird-single no-margin first">
            <div class="desc-head">
                <h2>
                    {{ $all->link->title }}
                </h2>
            </div>
        </div>

        <div class="gird-col gird-double second">

            <div class="desc-text">
                @if(!is_null($all->link->description))
                    {{ $all->link->description }}
                @endif
            </div>
            <div class="desc-links">
                @if(!is_null($all->link->description))
                    <ul class="link-desc-menu">
                    @foreach($all->link->description_links as $k=>$lnk)
                        <li>
                            <div class="point">
                                <span class="color-point"></span>
                            </div>
                            <a href="{{ $lnk->link }}" target="{{ $lnk->target }}" class="link-dsc">
                                {{ $lnk->name }}
                            </a>

                        </li>
                    @endforeach
                    </ul>
                @endif
            </div>

        </div>

    </div>
</div>