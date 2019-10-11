@inject('link', 'App\Entities\Link')
<div class="white-full-hd-cnt">
<div
        class="dsh-container-menu"
        id="topMenu"
        ng-class="menufixed"
        ng-controller="MenuController"
>
    <div class="gird-row no-scroll-min">
        <div class="gird-col gird-single no-margin first">

            <div class="logo-cont">
                <h1>
                    <a href="{{ route('homepage') }}" title="strona główna">
                        <span class="hidden-link">Przejście do strony głównej serwisu DSH</span>
                        @if($lang['id'] == 2)
                        <img src="{{ asset('images/logo-dsh.svg') }}" title="logo Domu Spotkań z Historią" alt="logo Domu Spotkań z Historią">
                           @else
                            <img src="{{ asset('images/logo_en_dsh.svg') }}" title="logo Domu Spotkań z Historią" alt="logo Domu Spotkań z Historią">
                         @endif
                    </a>
                </h1>
            </div>

            <div class="address-cont">
                <p class="name-of-inst">
                    {{ __('translations.institution_type') }}<br>
                </p>
                <p class="info-address">
                    {{ __('translations.institution_address') }}<br>
                </p>
                <p class="info-address">
                    +48 22 255 05 00 | <a href="mailto:dsh@dsh.waw.pl" title="adres mailowy domu spotkań z historią">dsh@dsh.waw.pl</a>
                </p>
            </div>

        </div>

        <div class="gird-col gird-double second">
            <div class="open-hours-cont social-cont">
                <div class="hour-left">
                    {{ __('translations.institution_hours') }} &nbsp;&nbsp;&nbsp;{{ __('translations.institution_happyhours') }}
                </div>
                <div class="social-right">


                    @if($lang['tag']=='pl')

                        <div class="language-btn btn-colect">
                            <a href="{{ url('en') }}">
                                <img src="{{ asset('images/lang-en.svg') }}">
                            </a>
                        </div>

                    @endif


                    @if($lang['tag']=='en')

                        <div class="language-btn btn-colect">
                            <a href="{{ url('pl') }}">
                                <img src="{{ asset('images/lang-pl.svg') }}">
                            </a>
                        </div>

                    @endif



                    <div class="social-btns btn-colect">
                        <ul class="social-ul">
                            <li>
                                <a href="https://www.facebook.com/DomSpotkanzHistoria" target="_blank">
                                    <span class="hidden-link">Przejście do strony DSH na facebooku</span>
                                    <img src="{{ asset('images/fb-btn.svg') }}" title="ikona facebook" alt="ikona facebook">
                                </a>
                            </li>
                            <li>
                                <a href="https://www.youtube.com/user/domspotkanzhistoria" target="_blank">
                                    <span class="hidden-link">Przejście do kanału DSH na Youtube</span>
                                    <img src="{{ asset('images/yt-btn.svg') }}" title="ikona youtube" alt="ikona youtube">
                                </a>
                            </li>
                            <li>
                                <a href="https://www.instagram.com/dsh.waw.pl/" target="_blank">
                                    <span class="hidden-link">Przejście do strony DSH na Instagram</span>
                                    <img src="{{ asset('images/inst-btn.svg') }}" title="ikona instagram" alt="ikona instagram">
                                </a>
                            </li>
                            <li>
                                <a href="https://bip.dsh.waw.pl/" target="_blank">
                                    <span class="hidden-link">Przejście do strony BIP</span>
                                    <img src="{{ asset('images/bip-logo.png') }}" title="ikona BIP" alt="ikona BIP" style="height:35px; width:auto" />
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="search-btn btn-colect">

                        <a href="{{ route('dosearch') }}" title="Przejście do sekcji wyszukiwania na stronie">
                            <span class="hidden-link">Przejście do sekcji wyszukiwania na stronie</span>
                            <img src="{{ asset('images/search-btn.svg') }}" title="ikona lupki" alt="ikona lupki">
                        </a>

                    </div>

                </div>
            </div>

            <div class="clear-fix"></div>

            <div class="menu-cont">
                <ul>
                @foreach($link->where(['ref'=>null, 'language_id'=>$lang['id'], 'status'=>1])->orderBy('ord','asc')->get() as $k=>$l)

                    <li>
                        <a href="{{ url($l->link) }}">
                            <span class="hidden-link">Przejście do sekcji</span> {{ $l->title }}
                        </a>
                    </li>

                    {{--@if(!$loop->last)--}}
                        {{--<li class="li-point">--}}
                            {{--<img src="{{ asset('images/point.svg') }}" class="img-responsive">--}}
                        {{--</li>--}}
                    {{--@endif--}}

                @endforeach
                </ul>
            </div>

        </div>
    </div>


    <div class="fixed-view-menu">
        <div class="left-fixed-logo">
            <a href="{{ route('homepage') }}" title="strona główna">
                <span class="hidden-link">Przejście do strony głównej serwisu DSH</span>
                <img src="{{ asset('images/dsh-logo-min.svg') }}" title="logo Domu Spotkań z Historią" alt="logo Domu Spotkań z Historią">
            </a>
        </div>
        <div class="right-fixed-links">
            <ul>
                @foreach($link->where(['ref'=>null, 'language_id'=>$lang['id'], 'status'=>1])->orderBy('ord','asc')->get() as $k=>$l)

                    <li>
                        <a href="{{ url($l->link) }}">
                            <span class="hidden-link">Przejście do sekcji</span> {{ $l->title }}
                        </a>
                    </li>

                    @if(!$loop->last)
                        <li class="li-point">
                            <img src="{{ asset('images/point.svg') }}" class="img-responsive" title="czerwona kropka" alt="czerwona kropka">
                        </li>
                    @endif

                @endforeach
            </ul>
        </div>
    </div>


</div>
</div>


<div
        class="dsh-container-menu-mobile menu-mobile"
        ng-controller="MenuController"
>

    <div class="gird-row-mobile">

        <div class="menu-logo-view hide-mobile" ng-class="show_menu" ng-init="show_menu='hide-mobile'">
            <a href="{{ route('homepage') }}" title="strona główna">
                <span class="hidden-link">Przejście do strony głównej serwisu DSH</span>
                <img src="{{ asset('images/logo-dsh.svg') }}" title="logo Domu Spotkań z Historią" alt="logo Domu Spotkań z Historią">
            </a>
            <div class="menu-mobile-content">
                <div class="logo-red-show">
                    <img src="{{ asset('images/logo-white.svg') }}" class="img-responsive" title="białe logo na czerwonym tle widoczne w wersji responsywnej" alt="białe logo na czerwonym tle widoczne w wersji responsywnej">
                </div>
                <div class="black-menu-links">
                    <ul>
                        @foreach($link->where(['ref'=>null, 'language_id'=>$lang['id']])->orderBy('ord','asc')->get() as $k=>$l)

                            <li>
                                <a href="{{ url($l->link) }}">
                                    <span class="hidden-link">Przejście do sekcji</span> {{ $l->title }}
                                </a>
                            </li>

                        @endforeach
                    </ul>
                </div>
                <div class="black-menu-down-btns">

                    <ul class="social-ul" id="bottomMbtns">
                        <li>
                            <a href="https://www.facebook.com/DomSpotkanzHistoria" target="_blank">
                                <span class="hidden-link">Przejście do strony DSH na facebooku</span>
                                <img src="{{ asset('images/fb-btn.svg') }}" title="ikona facebook" alt="ikona facebook">
                            </a>
                        </li>
                        <li>
                            <a href="https://www.youtube.com/user/domspotkanzhistoria" target="_blank">
                                <span class="hidden-link">Przejście do kanału DSH na Youtube</span>
                                <img src="{{ asset('images/yt-btn.svg') }}" title="ikona youtube" alt="ikona youtube">
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/dsh.waw.pl/" target="_blank">
                                <span class="hidden-link">Przejście do strony DSH na Instagram</span>
                                <img src="{{ asset('images/inst-btn.svg') }}" title="ikona instagram" alt="ikona instagram">
                            </a>
                        </li>
                        <li>
                            @if($lang['tag']=='pl')

                            <div class="language-btn btn-colect">
                            <a href="{{ url('en') }}">
                            <img src="{{ asset('images/lang-en.svg') }}">
                            </a>
                            </div>

                            @endif


                            @if($lang['tag']=='en')

                            <div class="language-btn btn-colect">
                            <a href="{{ url('pl') }}">
                            <img src="{{ asset('images/lang-pl.svg') }}">
                            </a>
                            </div>

                            @endif
                        </li>
                    </ul>

                </div>
            </div>
        </div>

        <div class="burger-trio">
            <div class="menu-mob-open">
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
                <a
                        href="#"
                        ng-click="$event.preventDefault();(show_menu=='hide-mobile')?(show_menu=''):(show_menu='hide-mobile')"
                        tabindex="0"
                        role="button"

                >
                    <span class="hidden-link">Otwarcie mobilnego menu</span>
                </a>
            </div>
        </div>

    </div>

    <div class="clear-fix"></div>

</div>


