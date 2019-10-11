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

        <div class="background-container menu-double-cols siren" style="background: url('{{ asset('images/8.jpg') }}');background-size: cover;background-attachment: fixed" id="scale-by-content-first">

            <div class="siren-background">



            <div class="uns-second-row color-by-template" id="content-first">

                <div class="filter-show-in">
                    @include('front.links.unstandard.filters')
                </div>

                <div class="uns-section-empty col">

                </div>

                <div class="uns-section-content col">

                    <div class="col-double-inside head-title">
                        <h2>
                            Dom Spotkań z Historią – samorządowa instytucja
                            kultury miasta stołecznego Warszawy
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
                            Dom Spotkań z Historią (DSH) powstał w 2005 roku z inicjatywy Ośrodka KARTA. Od marca 2006 działa jako instytucja kultury miasta st. Warszawy. Zajmujemy się historią Polski i Europy Środkowo-Wschodniej w XX wieku ze szczególnym naciskiem na skutki nazizmu i komunizmu. Z sukcesem popularyzujemy również historię stolicy i jej mieszkańców. Przedstawiając historię, wykorzystujemy różnorodne formy przekazu. Skupiamy się na historii społecznej, ciekawych biografiach, niezwykłych losach zwykłych ludzi. Jesteśmy przekonani, że pamięć o XX stuleciu buduje naszą tożsamość, zarówno w wymiarze jednostkowym, jak i zbiorowym. DSH organizuje wystawy, pokazy filmów dokumentalnych i fabularnych, dyskusje, konferencje, warsztaty edukacyjne, spacery i wycieczki rowerowe po Warszawie, instalacje artystyczne, happeningi, gry miejskie i wydarzenia parateatralne.
                        </div>
                        <div class="uns-half-content second">
                            DSH upowszechnia historię poprzez źródła i świadectwa indywidualne. Wspólnie z Ośrodkiem KARTA prowadzi Archiwum Historii Mówionej (AHM), największy w Polsce zbiór relacji świadków historii XX wieku, które gromadzi, opracowuje i udostępnia relacje świadków historii mówionej, digitalizuje zdjęcia, dokumenty i filmy. Na wszystkie spotkania, wydarzenia i wystawy wstęp jest wolny.
<br><br>
                            Rada Programowa Domu Spotkań z Historią: Zbigniew Gluza, Danuta Przywara, Agnieszka Rudzińska, Maciej Drygas, Jerzy Kochanowski, Jacek Leociak, Dariusz Stola.
                        </div>
                    </div>


                </div>

            </div>

            </div>

        </div>

        <div class="uns-row-two-cols">

            <div class="uns-section-left col black-bg">
                DSH realizuje wiele różnorodnych
                projektów edukacyjnych
                (w tym międzynarodowych)
                oraz organizuje cykle spotkań,
                pokazy filmów, promocje wydawnicze,
                dyskusje, seminaria i konferencje.
            </div>

            <div class="uns-section-right col" style="background: url('{{ asset('images/14.jpg') }}');background-size: cover;background-attachment: fixed">

            </div>

        </div>

        <div class="uns-row-two-cols last-row">

            <div class="uns-section-left col" style="background-color: #{{ $data->color->rgb }};">

            </div>

            <div class="uns-section-right col">

                <div class="uns-dystans"></div>

                <div class="row-half-content">

                    <div class="col-double-inside head-title">
                        <h2>
                            Główne obszary działań
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
                        W dwóch galeriach wewnętrznych oraz na pobliskim skwerze im. Ks. Jana Twardowskiego organizujemy wystawy historyczne. Nasze ekspozycje opowiadają o historii poprzez źródła: fotografie, relacje, dokumenty, nagrania audio i wideo. Wystawom towarzyszą spotkania, pokazy filmowe, warsztaty i zajęcia edukacyjne.<br><br>
                        Wystawy najczęściej mają charakter fotograficzny, na przykład: "1947 BARWY RUIN. Warszawa i Polska w odbudowie na zdjęciach Henry'ego N. Cobba", "Karol Beyer – pierwsze fotografie Krakowskiego Przedmieścia", "Chodząc po ziemi. 50 lat fotografii prasowej Aleksandra Jałosińskiego", "Budujemy nowy dom. Odbudowa Warszawy w latach 1945-1952", "Architektoniczna spuścizna socrealizmu w Warszawie i Berlinie. MDM / KMA", "Cztery pory Gierka. Polska lat 1970-1980 w fotografiach z Agencji FORUM", "W obiektywie wroga. Niemieccy fotoreporterzy w okupowanej Warszawie (1939–1945)", "Miasto na szklanych negatywach. Warszawa 1916 w fotografiach Willy’ego Römera",
                    </div>
                    <div class="uns-half-content second">
                        <p>
                            "Warszawa z wysoka. Niemieckie zdjęcia lotnicze 1940–1945 z National Archives w College Park", "60 lat temu w Warszawie. Fotografie PAP 1947–48", "Zdjęcia osobiste i zakazane. Życie codzienne w Rumunii w czasach Nicolae Ceauşescu". Wiele ekspozycji ma charakter multimedialny, m.in. "Oblicza totalitaryzmu", "Ocaleni z Mauthausen", "Amerykanin w Warszawie. Stolica w obiektywie Juliena Bryana 1936–1974"
                        </p>
                        DSH wydaje książki dotyczące historii XX wieku, w tym albumy historyczne, varsavianistyczne, wspomnienia. Część albumów towarzyszy wystawom, m.in.: "1947 BARWY RUIN", "Budujemy nowy dom", "Karol Beyer 1818–1877", "Polacy z wyboru". Wiele pozycji ma charakter varsavianistyczny – poza wymienionymi wyżej – to m.in.: "Ostańce. Kamienice warszawskie i ich mieszkańcy", "Kapliczki warszawskie". W budynku DSH znajduje się księgarnia z publikacjami dotyczącymi historii Europy Środkowo-Wschodniej. Księgarnia organizuje promocje książek i spotkania z autorami
                    </div>
                </div>

                <div class="uns-dystans"></div>

            </div>

        </div>

        <div class="unstandard-image-view" style="background: url('{{ asset('images/13.jpg') }}');background-size: cover;background-attachment: fixed">
        </div>

    </div>
</div>