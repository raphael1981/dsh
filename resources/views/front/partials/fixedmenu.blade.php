@inject('link', 'App\Entities\Link')
<div class="white-full-hd-cnt">
    <div
            class="dsh-container-menu"
            id="topMenuFixed"
            ng-class="menufixed"
            ng-controller="MenuController"
    >

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