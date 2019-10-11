{{--<style>--}}
    {{--.dsh-container-unstandard.unstandard-view .background-container.menu-double-cols{--}}
        {{--min-height: 1500px;--}}
    {{--}--}}
{{--</style>--}}
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

        <div class="background-container menu-double-cols" style="background: url('{{ asset('images/8.jpg') }}');background-size: cover;background-attachment: fixed;" id="scale-by-content-first">

            <div class="siren-background">


                <div class="uns-second-row color-by-template" id="content-first">

                    <div class="filter-show-in">
                        @include('front.links.unstandard.filters')
                    </div>

                    <div class="uns-section-empty col siren-cont">

                    </div>

                    <div class="uns-section-content col" style="">



                        <div class="col-double-inside head-title">
                            <h2>
                                Historia ulicy Karowej w pigułce
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
                                <p>Na początku był wąwóz. Wyjątkowo stromy w porównaniu do tych, z których wyłoniły się pobliskie ulice Bednarska i Mostowa. Spływały nim potoki odprowadzające wodę z okolic dzisiejszego pl. Piłsudskiego, strumienie ze źródeł skarpowych, a wraz z rozrastaniem się miasta – miejskie ścieki.</p>

                                <p>Początki ulicy sięgają usypania grobli łączącej skarpę z brzegiem Wisły. Wąski trakt ma ok. 4 m szerokości, w sam raz tyle, żeby pomieścić mijające się w drodze dwukołowe wózki, zwożące miejskie nieczystości do osadników nad brzegiem Wisły. Ulica staje się jelitem miasta, a wózki, zwane karami (z łac. currus– wóz), dają początek jej nazwie. Karowa pojawia się oficjalnie na planie Warszawy w 1770 r. W roku 1855 nad samą skarpą, wzdłuż Krakowskiego Przedmieścia, staje brama z wodotryskiem i posągiem syreny na szczycie – pierwszy symbol ulicy.</p>

                                <p>
                                    Wraz z budową wodociągu Marconiego miejsce karów zajmują rury tłoczące wodę do wieży ciśnień w Ogrodzie Saskim oraz odprowadzające ścieki z miasta do Wisły. Pod koniec XIX w. stację pomp Marconiego zastępuje przepompowania ścieków z Powiśla, będąca częścią systemu wodociągów i kanalizacji Lindleya.
                                </p>
                                <p>
                                    Pod Karową, na wysokości dzisiejszego hotelu Bristol, powstaje wielka komora przelewowa,
                                </p>
                            </div>
                            <div class="uns-half-content second">
                                <p>
                                    z której w czasie silnych opadów deszczu nadmiar wody odprowadzany jest kanałem burzowym do Wisły.
                                    Wielkomiejskie ambicje władz miasta i podjęte próby przekształcenia ulicy w reprezentacyjną arterię łączącą Powiśle z Krakowskim Przedmieściem położyły kres istnieniu charakterystycznej bramy z wodotryskiem i posągiem syrenki na szczycie. Pojawił się za to wiadukt w kształcie ślimaka, nowy symbol ulicy, który wraz z planowaną budową metra na Karowej miał podzielić los swojego poprzednika – co jednak nigdy nie nastąpiło.
                                </p>

                                <p>
                                    Karowa to jedna z najbardziej osobliwych ulic Śródmieścia, składająca się z trzech odrębnych części: górnej, od Krakowskiego Przedmieścia do wiaduktu, samego wiaduktu-ślimaka i dolnej, która wraz odsuwaniem się koryta rzeki od skarpy wiślanej wydłużała się, aż w XIX w. sięgnęła dzisiejszego Wybrzeża Kościuszkowskiego. To ulica nigdy do końca niezaistniała. Ulica dwóch światów – „gorszego” Powiśla i „miasta”, której ambicje nigdy nie zostały zaspokojone. Ulica duch…
                                </p>

                                <p>
                                    Więcej o historii ulicy przeczytasz w wydanym przez DSH przewodniku KAROWA dostępnym w Księgarni XX wieku.
                                </p>

                            </div>
                        </div>


                    </div>

                </div>


            </div>

        </div>

        <div class="uns-row-two-cols">

            <div class="uns-section-left col black-bg">
                Nazwa ulicy Karowej
                prawdopodobnie pochodzi
                od wozów straży ogniowej
                zwanych „karami”, które
                tędy często przejeżdzały
                wywożąc z miasta śmieci
                nad Wisłę.
            </div>

            <div class="uns-section-right col" style="background: url('{{ asset('images/14.jpg') }}');background-size: cover;background-attachment: fixed">

            </div>

        </div>

        <div class="uns-row-two-cols">

            <div class="uns-section-left col col-text-right" style="background-color: #{{ $data->color->rgb }};">

            </div>

            <div class="uns-section-right col">

                <div class="uns-dystans"></div>

                <div class="row-half-content">

                    <div class="col-double-inside head-title">
                        <h2>
                            Karowa 20 ─ historia budynku
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

                    <div class="uns-half-content first">
                        <p>
                            W miejscu, w którym znajduje się teraz Dom Spotkań z Historią początkowo miał powstać teatr. Spółka Ignacego Paderewskiego, będąca właścicielem hotelu Bristol, planowała wybudować obok niego dużą salę koncertową. Wraz z zaangażowaniem się Paderewskiego w budowę Filharmonii Warszawskiej zaniechano realizacji planu. W 1928 r. właścicielem hotelu Bristol i sąsiadujących z nim gruntów zostaje Bank Cukrownictwa SA, który w latach 1932–1933 wznosi tu kamienicę wg projektu
                            Antoniego Jawornickiego. Wcześniej w tyle działki stał budynek przeznaczony pierwotnie dla agregatów prądotwórczych hotelu, który w latach 20. XX w. został zaadaptowany na pomieszczenia gospodarcze.

                        </p>
                    </div>
                    <div class="uns-half-content second">
                        <p>Gmach Banku Cukrownictwa SA przetrwał wojnę bez zniszczeń.</p>
                        <p>
                            W okresie powojennym jego wnętrza zostały przebudowane, zachowały się
                            jedynie nieliczne elementy wystroju i wyposażenia. Obecnie suterena i dawna sala operacji bankowych z antresolą jest siedzibą Domu Spotkań z Historią. O tym, że mieścił się tu kiedyś bank, przypominają pancerne drzwi do sejfu, w którym teraz odbywają się warsztaty edukacyjne. Pozostałe piętra budynku zajmują biura i wydziały Uniwersytetu Warszawskiego.
                        </p>
                    </div>
                </div>

                <div class="uns-dystans"></div>

            </div>

        </div>

        <div class="unstandard-image-view" style="background: url('{{ asset('images/6.jpg') }}');background-size: cover;background-attachment: fixed">
        </div>

    </div>
</div>
