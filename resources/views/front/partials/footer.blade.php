@inject('link', 'App\Entities\Link')
<div class="dsh-container footer-cont">
    <div class="gird-row transparent-gird-row">

        <div class="gird-col gird-single no-margin first footer">

            @if(!is_null($footmenu))
            <div class="primary-root-link">
                <div class="point-cont">
                    <img src="{{ asset('images/point.svg') }}" class="img-responsive" title="czerwona kropka" alt="czerwona kropka">
                </div>
                <div class="link-cont">
                    <a href="{{ url($footmenu->root_link->link) }}" title="Przejście do sekcji {{ $footmenu->root_link->title }}">
                        <span class="hidden-link">Przejście do sekcji</span> {{ $footmenu->root_link->title }}
                    </a>
                </div>

            </div>

            <div class="rec-menu-cont">
                <ul class="rec-menu">
                    @foreach($footmenu->lcoll as $k=>$dl)

                        <li>
                            <a href="{{ url($dl->link) }}" title="Przejście do sekcji {{ $dl->title }}">
                                <span class="hidden-link">Przejście do sekcji</span> {{ $dl->title }}
                            </a>
                        </li>

                    @endforeach
                </ul>
            </div>
            @endif


        </div>

        <div class="gird-col gird-single second footer">

            <ul class="footer-root-menu">
                @foreach($link->where(['ref'=>null, 'language_id'=>$lang['id']])->get() as $k=>$l)

                    <li>

                        <div class="point">
                            <img src="{{ asset('images/point.svg') }}" class="img-responsive" title="czerwona kropka" alt="czerwona kropka">
                        </div>

                        <div class="link">
                            <a href="{{ url($l->link) }}" title="Przejście do sekcji {{ $dl->title }}">
                                <span class="hidden-link">Przejście do sekcji</span> {{ $l->title }}
                            </a>
                        </div>

                    </li>


                @endforeach
            </ul>

        </div>

        <div class="gird-col gird-single third footer">

            <div class="social-foot">

                {{--<div class="search-btn btn-colect">--}}
                    {{--<img src="{{ asset('images/search-btn.svg') }}">--}}
                {{--</div>--}}

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
                    </ul>
                </div>

                <div class="language-btn btn-colect">
                    <a href="{{ route('dosearch') }}" title="Przejście do sekcji wyszukiwania na stronie">
                        <span class="hidden-link">Przejście do sekcji wyszukiwania na stronie</span>
                        <img src="{{ asset('images/search-btn.svg') }}" title="ikona lupki" alt="ikona lupki">
                    </a>
                </div>

            </div>

            <div class="clear-fix beam-beetween"></div>

            <div class="short-info-foot" style="height:144px">
                <div class="text-row">
                    {{ __('translations.institution_address') }}
                    {{--Karowa 20, 00-324 Warszawa--}}
                </div>
                <div class="text-row">
                    +48 22 255 05 00 | <a href="mailto:dsh@dsh.waw.pl">dsh@dsh.waw.pl</a>
                </div>
                <div class="text-row" style="margin-top:8px">
                    <a href="{{ url(__('translations.privacy_policy_url')) }}" target="_blank">{{ __('translations.privacy_policy') }}</a>
                </div>

            </div>

            <div class="clear-fix beam-beetween"></div>

            <div class="logo-foot">
                <img src="{{ asset('images/bottom-logo.svg') }}" class="img-responsive" title="obrazek pokazują logo DSH z graficznym elementem Istytucja miasta stołecznego Warszawa" alt="obrazek pokazują logo DSH z graficznym elementem Istytucja miasta stołecznego Warszawa">
            </div>


        </div>

    </div>

    <div class="foot-mobile-bottom">
        <img src="{{ asset('images/bottom-logo.svg') }}" class="img-responsive" title="obrazek pokazują logo DSH z graficznym elementem Istytucja miasta stołecznego Warszawa" alt="obrazek pokazują logo DSH z graficznym elementem Istytucja miasta stołecznego Warszawa">
    </div>

</div>