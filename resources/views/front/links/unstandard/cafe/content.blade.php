<div
        class="controller-neutral"
        ng-controller="UnstandardController"
        ng-model="lid"
        ng-model="lang_tag"
        ng-init="
            lang_tag='{{ $language['tag'] }}';
            lid={{ $data->link->id  }};
            initData()
        "
>

    <div class="dsh-container-unstandard unstandard-view mission-view-show top_one">

        <div class="background-container menu-double-cols"
             style="background: url('{{ asset('images/karowa_49.jpg') }}');background-size: cover;background-attachment: fixed;background-attachment: fixed"
        >

            <div class="shadow-background">

                <div class="uns-first-row unstandard-desktop-links">

                    <div class="uns-section-name col">
                        <div class="head-of-link">
                            <h2>
                                {{ $data->link->title }}
                            </h2>
                        </div>
                    </div>

                    <div class="uns-section-menu col">
                        <ul class="filter-labels">
                            @foreach($data->other_links as $k=>$l)
                                <li class="element">
                                    @if($l->active)
                                        <a href="{{ url($l->link) }}" class="active">
                                            {{ $l->title }}
                                        </a>
                                    @else
                                        <a href="{{ url($l->link) }}">
                                            {{ $l->title }}
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>

                <div class="head-mobile-filter-unstadard">
                    <div class="row-mobile-filter-inside">

                        <div class="name-of-section">
                            <h2>
                                {{ $data->link->title }}
                            </h2>
                        </div>

                        <div class="button-filter-open">
                            <a href="#" ng-click="$event.preventDefault();(filter_mobile_open=='hidden')?(filter_mobile_open=''):(filter_mobile_open='hidden')">
                                filtruj
                            </a>
                        </div>

                    </div>
                </div>

                <div class="filters-mobiles-rel-cont" ng-class="filter_mobile_open" ng-init="filter_mobile_open='hidden'">
                    <div class="filters-mobiles">

                        <ul class="cat-labels-mobile">
                            @foreach($data->other_links as $k=>$l)
                                <li class="element">
                                    @if($l->active)
                                        <a href="{{ url($l->link) }}" class="filter active">
                                            {{ $l->title }}
                                        </a>
                                    @else
                                        <a href="{{ url($l->link) }}" class="filter">
                                            {{ $l->title }}
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                    </div>
                </div>

                <div class="uns-second-row color-by-template">

                    <div class="uns-section-empty col">

                    </div>

                    <div class="uns-section-content col">

                        <div class="col-double-inside head-title">
                            <h2>
                                Kawiarnia Karowa 20 &nbsp; &nbsp;<span style="font-size:.8em">Kawa z historią</span>
                            </h2>
                        </div>

                        <div class="border-half-row">
                            <div class="border-left">

                            </div>
                            <div class="border-right">

                            </div>
                        </div>

                        <div class="border-full-row">
                            <div class="border-line">

                            </div>
                        </div>

                        <div class="row-half-content">
                            <div class="uns-half-content first">
                                <p>
                                <h3>Kawa</h3>
                                Jeśli jesteś miłośnikiem kawy, nie mogłeś trafić lepiej! U nas znajdziesz karmelowe espresso, aksamitne kawy mleczne oraz bogactwo innych smaków i aromatów. Do kaw parzonych metodami przelewowymi sam wybierasz ziarna. Masz ochotę na nuty czekoladowe, jagodowe czy cytrynowe?
                                </p><p>
                                <h3>Napary ziołowe</h3>
                                W naszych herbatach pływają liście pokrzywy, kwiaty nagietka i owoce dzikiej róży. Stawiamy na naturalne składniki oraz lokalnych dostawców.
                                </p><p>
                                <h3>Małe jedzenie</h3>
                                Zasmakuj w naszych zupach. Codziennie inny smak: od szczawiowej do kukurydzianej. Serwujemy zieloną superżywność: koktajl ze świeżej pokrzywy oraz kanapki z pastą z jarmużu, natki pietruszki czy liści rzodkiewki. Przyjdź i spróbuj lodów tworzonych z odwiecznym rytmem natury! Podajemy lody naturalne Kroczek.
                            </div>
                            <div class="uns-half-content second">
                                <p>
                                <h3>Z własnym kubkiem taniej</h3>
                                Rozsądnie wybieramy naszych dostawców i cenimy osoby dbające o środowisko. Kto przyjdzie do nas z własnym kubkiem, otrzyma napój w cenie niższej o 1 zł.
                                </p><p>
                                <h3>Atmosfera</h3>
                                Stali bywalcy, zaangażowani właściciele i pracownicy tworzą przyjazną, rodzinną atmosferę, która sprzyja zarówno pracy, jak i wypoczynkowi. Przyjdź do nas ze znajomymi, z dziećmi, z czworonożnym towarzyszem – czuj się swobodnie.
                                </p>
                                Godziny otwarcia: wtorek-piątek 10.00-20.00,<br />
                                sobota-niedziela 12.00-20.00

                            </div>
                        </div>


                    </div>

                </div>

            </div>

        </div>

        <div class="uns-row-two-cols" style="background: url('{{ asset('images/karowa_8.jpg') }}');background-size: cover;background-attachment: fixed">

            <div class="uns-section-left col black-bg">


                <div class="visit-caffe">
                    <div class="round-white-cafe-logo">
                        <img src="{{ asset('images/caffe-sign_white.png') }}" class="img-responsive" style="margin:auto">
                    </div>
                    <div style="text-align:center">
                        <a href="https://www.facebook.com/Karowa20/" style="color:#ffffff" target="_blank">facebook.com/Karowa20</a>
                    </div>
{{--                    <div class="r-icon">
                        <img src="{{ asset('images/icon-file.svg') }}" class="img-responsive">
                    </div>--}}

                    {{--<a href="/pdf/ulotka_karowa_5_02.pdf" target="_blank"></a>--}}
                </div>

            </div>

            <div class="uns-section-right col">

            </div>

        </div>

        {{--<div class="uns-row-two-cols">--}}

            {{--<div class="uns-section-left col" style="background-color: #{{ $data->color->rgb }};height:1050px">--}}
                {{--<div class="round-white-cafe-logo">--}}
                    {{--<img src="{{ asset('images/caffe-sign.png') }}" class="img-responsive">--}}
                {{--</div>--}}

            {{--</div>--}}

            {{--<div class="uns-section-right col">--}}

                {{--<div class="uns-dystans"></div>--}}

                {{--<div class="row-half-content">--}}
                    {{--<div class="uns-half-content first">--}}
                        {{--<div style="padding-top:10px;">--}}
                            {{--<time style="font-weight:bold">5.03.2017, godz. 16:00</time>--}}
                        {{--<div class="h4">ZIOŁA DLA PIĘKNEJ KOBIETY</div>--}}
                        {{--Czy wiesz, że wszystkie luksusowe kosmetyki możesz przyrządzić samodzielnie z naturalnych składników w swojej kuchni? Karolina Kumor – autorka bloga Slow Mindful Beauty opowie jak z pomocą natury zadbać o swoje piękno.--}}
                        {{--</div>--}}
                        {{--<div style="padding-top:10px;">--}}
                            {{--<time style="font-weight:bold">17.03.2017, godz. 18:00</time>--}}
                        {{--<div class="h4">WIECZÓR GIER PLANSZOWYCH:GRAMY W KARTOGRAFIĘ</div>--}}
                        {{--Czy wiesz, jak dużo symboli kartograficznych zawierają mapy? Zasady gry są łatwe, wystarczy dostrzec dwa takie same znaki. Zapraszamy wszystkich do sprawdzenia swojej spostrzegawczości. Gwarantujemy świetną zabawę, a zwycięzcom nagrody!</div>--}}

                        {{--<div style="padding-top:10px;">--}}
                            {{--<time style="font-weight:bold">29.03.2017, godz. 17:30</time>--}}
                        {{--<div class="h4">KRYZYS MIGRACYJNY I CO DALEJ?</div>--}}
                        {{--Masowe przybywanie uchodźców i imigrantów z Bliskiego Wschodu i Afryki do Europy rozpoczęło się w 2014 roku i trwa do dnia dzisiejszego. O genezie, konsekwencjach i przyszłości tego złożonego historycznie, ekonomicznie i politycznie zjawiska opowie Paweł Cywiński - orientalista, kulturoznawca, geograf i współtwórca portali uchodzcy.--}}
                        {{--info oraz post-turysta.pl.--}}
                        {{--</div>--}}


                    {{--</div>--}}
                    {{--<div class="uns-half-content second">--}}

                        {{--<div style="padding-top:10px;">--}}
                            {{--<time style="font-weight:bold">2.04.2017, godz. 16:00</time>--}}
                        {{--<div class="h4">ALE MEKSYK!</div>--}}
                        {{--Jak przejść 450 km w 18 dni przez jeden z najniebezpieczniejszych rejonów Meksyku? Krzysztof Noworyta niestrudzony piechur, opowie o swojej pielgrzymce do Sanktuarium Matki Bożej Guadalupe. Będzie gorąco!</div>--}}

                        {{--<div style="padding-top:10px;">--}}
                            {{--<time style="font-weight:bold">12.04, godz.17.30</time>--}}
                        {{--<div class="h4">JAPONIA – MIĘDZY TRADYCJĄ A NOWOCZESNOŚCIĄ</div>--}}
                        {{--W 2011 roku fala tsunami wywołana trzęsieniem ziemi doprowadziło do śmierci blisko 20 000 osób w Japonii.  Jak zmieniły się gospodarka, kultura, a przede wszystkim społeczeństwo Japonii w ciągu ostatnich lat opowie  pracownik UW Maciej Kałaska. Partner: Polskie Towarzystwo Geograficzne.--}}
                    {{--</div></div>--}}
                {{--</div>--}}

                {{--<div class="uns-dystans"></div>--}}

            {{--</div>--}}

        {{--</div>--}}

        <div class="unstandard-image-view" style="background: url('{{ asset('images/karowa_49.jpg') }}');background-size: cover;background-attachment: fixed">
        </div>

    </div>
</div>
