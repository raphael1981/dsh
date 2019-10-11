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

    <div class="dsh-container-unstandard unstandard-view team-view-show">

        <div class="background-container menu-double-cols" style="background: url('{{ asset('images/4.jpg') }}');background-size: cover;background-attachment: fixed;background-attachment: fixed">

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
            </div>
        </div>
        <div class="other-team-container">
            <div class="uns-second-row black-bg-row">
                <div class="uns-section-empty col team black-bg">

                    <div class="inside-members-group-name">
                        Dyrekcja
                    </div>

                </div>

                <div class="uns-section-content col persons" style="float:right">

                    <div class="uns-half-content first team">
                        <div class="inside-team-memeber">
                            dyrektor
                            <div class="person-name">Piotr Jakubowski</div><br />
                            sekretariat: <a href="mailto:dsh@dsh.waw.pl">dsh@dsh.waw.pl</a><br />
                            22 255 05 05
                        </div>
                    </div>
                    <div class="uns-half-content second team">
                        <div class="inside-team-memeber">
                            zastępca dyrektora
                            <div class="person-name">Katarzyna Puchalska</div>
                            <br />&nbsp;
                            <br />&nbsp;
                        </div>
                    </div>

                    <div class="uns-half-content first team">
                        <div class="inside-team-memeber">
                            zastępca dyrektora
                            <div class="person-name">Katarzyna Madoń-Mitzner</div><br />
                            <br />&nbsp;
                            <br />&nbsp;
                        </div>
                    </div>
                </div>

            </div>




            <div class="uns-second-row bg-color-template">

                <div class="uns-section-empty col team">
                    <div class="inside-members-group-name">
                    Administracja<br><br>

                    sekretariat:<br>
                    tel. 22 255 05 05<br> administracja:<br>
                    tel. 22 255 05 10<br>
                    </div>
                </div>
                <div class="uns-section-content col persons" style="float:right">

                    <div class="uns-half-content first team">
                        <div class="inside-team-memeber">
                            kierownik administracji
                            <div class="person-name">Michał Zadrzyński</div>
                            <a href="mailto:m.zadrzynski@dsh.waw.pl">m.zadrzynski@dsh.waw.pl</a><br />
                            22 255 05 10
                        </div>
                    </div>
                    <div class="uns-half-content second team">
                        <div class="inside-team-memeber">
                            zastępca kierownika
                            <div class="person-name">Anna Markiewicz-Piątkowska</div>
                            <a href="mailto:a.markiewicz@dsh.waw.pl">a.markiewicz@dsh.waw.pl</a><br />
                            22 255 05 10
                        </div>
                    </div>

                    <div class="uns-half-content first team">
                        <div class="inside-team-memeber">
                            specjalista ds. administracyjno-lokalowych
                            <div class="person-name">Urszula Kubiszyn</div>
                            <a href="mailto:u.kubiszyn@dsh.waw.pl">u.kubiszyn@dsh.waw.pl</a><br />
                            22 255 05 10
                        </div>
                    </div>
                    <div class="uns-half-content second team">
                        <div class="inside-team-memeber">
                            sekretariat
                            <div class="person-name">Anna Talarek</div>
                            <a href="mailto:a.talarek@dsh.waw.pl">a.talarek@dsh.waw.pl</a><br />
                            22 255 05 05
                        </div>
                    </div>

                    <div class="uns-half-content first team">
                        <div class="inside-team-memeber">
                            administrator systemów informatycznych
                            <div class="person-name">Michał Brzostowski</div>
                            <a href="mailto:administator@dsh.waw.pl">administrator@dsh.waw.pl</a><br />
                            22 255 05 12
                        </div>
                    </div>
                    <div class="uns-half-content second team">
                        <div class="inside-team-memeber">
                            konserwator, zaopatrzeniowiec
                            <div class="person-name">Romuald Trębacz</div>
                            <a href="mailto:r.trebacz@dsh.waw.pl">r.trebacz@dsh.waw.pl</a><br />
                            22 255 05 11
                        </div>
                    </div>

                    <div class="uns-half-content first team">
                        <div class="inside-team-memeber">
                            programista
                            <div class="person-name">Robert Radecki</div>
                            <a href="mailto:r.radecki@dsh.waw.pl">r.radecki@dsh.waw.pl</a><br />
                            22 255 05 13
                        </div>
                    </div>
                </div>
            </div>


            <div class="uns-second-row black-bg-row">

                <div class="uns-section-empty col team black-bg">
                    <div class="inside-members-group-name">
                    Animacja i Organizacja Spotkań<br />
                    tel. 22 255 05 30
                    </div>
                </div>


                <div class="uns-section-content col persons" style="float:right">

{{--                    <div class="uns-half-content first team">
                        <div class="inside-team-memeber">
                            kierownik
                            <div class="person-name">Katarzyna Puchalska</div>
                            <a href="mailto:k.puchalska@dsh.waw.pl">k.puchalska@dsh.waw.pl</a><br />
                            22 255 05 30
                        </div>
                    </div>--}}

                    <div class="uns-half-content first team">
                        <div class="inside-team-memeber">
                            kierownik
                            <div class="person-name">Weronika Komorowska</div>
                            <a href="mailto:w.komorowska@dsh.waw.pl">w.komorowska@dsh.waw.pl</a><br />
                            22 255 05 23
                        </div>

                    </div>

                    <div class="uns-half-content second team">
                        <div class="inside-team-memeber">
                            młodsza specjalistka ds. organizacji spotkań
                            <div class="person-name">Agata Kucharska</div>
                            <a href="mailto:a.kucharska@dsh.waw.pl">a.kucharska@dsh.waw.pl</a><br />
                        </div>
                    </div>

                </div>

            </div>


            <div class="uns-second-row bg-color-template">

                <div class="uns-section-empty col team">

                    <div class="inside-members-group-name">
                    Archiwum Historii Mówionej<br />
                    tel. 22 255 05 40
                    </div>

                </div>

                <div class="uns-section-content col persons" style="float:right">

                    <div class="uns-half-content first team">
                        <div class="inside-team-memeber">
                            kierownik
                            <div class="person-name">Katarzyna Madoń-Mitzner</div>
                            <a href="mailto:k.mitzner@dsh.waw.pl">k.mitzner@dsh.waw.pl</a><br />
                            22 255 05 41
                        </div>
                    </div>
                    <div class="uns-half-content second team">
                        <div class="inside-team-memeber">
                            zastępca kierownika ds. zasobów dźwiękowych
                            <div class="person-name">Magda Szymańska</div>
                            <a href="mailto:m.szymanska@dsh.waw.pl">m.szymanska@dsh.waw.pl</a><br />
                            22 255 05 42
                        </div>
                    </div>

                    <div class="uns-half-content first team">
                        <div class="inside-team-memeber">
                            specjalista ds. AHM
                            <div class="person-name">Maria Buko</div>
                            <a href="mailto:m.buko@dsh.waw.pl">m.buko@dsh.waw.pl</a><br />
                            22 255 05 40
                        </div>
                    </div>
                    <div class="uns-half-content second team">
                        <div class="inside-team-memeber">
                            sekretarz ds. merytorycznych
                            <div class="person-name">dr Jarosław Pałka</div>
                            <a href="mailto:j.palka@dsh.waw.pl">j.palka@dsh.waw.pl</a><br />
                            22 255 05 43
                        </div>
                    </div>

{{--                    <div class="uns-half-content first team">
                        <div class="inside-team-memeber">
                            fonoteka
                            <div class="person-name">Maciej Kamiński</div>
                            <a href="mailto:m.kaminski@dsh.waw.pl">m.kaminski@dsh.waw.pl</a><br />
                        </div>
                    </div>--}}
                    <div class="uns-half-content first team">
                        <div class="inside-team-memeber">
                            specjalista ds. AHM
                            <div class="person-name">Iwona Makowska</div>
                            <a href="mailto:i.makowska@dsh.waw.pl">i.makowska@dsh.waw.pl</a><br />
                            22 255 05 42
                        </div>
                    </div>

                    <div class="uns-half-content second team">
                        <div class="inside-team-memeber">
                            specjalista ds. AHM
                            <div class="person-name">Joanna Rączka</div>
                            <a href="mailto:j.raczka@dsh.waw.pl">j.raczka@dsh.waw.pl</a><br />
                            22 255 05 42
                        </div>
                    </div>

                </div>

            </div>


            <div class="uns-second-row black-bg-row">

                    <div class="uns-section-empty col team black-bg">

                        <div class="inside-members-group-name">
                        Edukacja<br />
                        tel. 22 255 05 25
                        </div>

                    </div>

                    <div class="uns-section-content col persons" style="float:right">

                        <div class="uns-half-content first team">
                            <div class="inside-team-memeber">
                                kierownik
                                <div class="person-name">Anna Ziarkowska</div>
                                <a href="mailto:a.ziarkowska@dsh.waw.pl">a.ziarkowska@dsh.waw.pl</a><br />
                                22 255 05 26
                            </div>
                        </div>
                        <div class="uns-half-content second team">
                            <div class="inside-team-memeber">
                                specjalista ds. edukacji
                                <div class="person-name">Justyna Urban</div>
                                <a href="mailto:j.urban@dsh.waw.pl">j.urban@dsh.waw.pl</a><br />
                                22 255 05 25
                            </div>
                        </div>
                        <div class="uns-half-content first team">
                            <div class="inside-team-memeber">
                                specjalistka ds. edukacji
                                <div class="person-name">Małgorzata Fałkowska-Warska</div>
                                <a href="mailto:m.falkowska@dsh.waw.pl">m.falkowska@dsh.waw.pl</a><br />
                                22 255 05 25
                               </div>
                        </div>
                    </div>

            </div>

            <div class="uns-second-row bg-color-template">

                    <div class="uns-section-empty col team">

                        <div class="inside-members-group-name">
                            Fotoedycja<br />
                        </div>


                    </div>

                    <div class="uns-section-content col persons" style="float:right">

                        <div class="uns-half-content first team">
                            <div class="inside-team-memeber">
                                główny fotoedytor
                                <div class="person-name">Anna Brzezińska</div>
                                <a href="mailto:a.brzezinska@dsh.waw.pl">a.brzezinska@dsh.waw.pl</a><br />

                            </div>
                        </div>

{{--                        <div class="uns-half-content second team">
                            <div class="inside-team-memeber">
                                starszy specjalista ds. filmowych
                                <div class="person-name">Dariusz Krajewski</div>
                                <a href="mailto:d.krajewski@dsh.waw.pl">d.krajewski@dsh.waw.pl</a><br />
                                22 255 05 46
                            </div>
                        </div>--}}

                    </div>

            </div>

            <div class="uns-second-row black-bg-row">

                    <div class="uns-section-empty col team black-bg">

                        <div class="inside-members-group-name">
                        Komunikacja<br>
                        tel. 22 255 05 21<br>
                        tel. 22 255 05 22
                        </div>

                    </div>

                    <div class="uns-section-content col persons" style="float:right">

                        <div class="uns-half-content first team">
                            <div class="inside-team-memeber">
                                kierownik
                                <div class="person-name">Kinga Sochacka</div>
                                <a href="mailto:k.sochacka@dsh.waw.pl">k.sochacka@dsh.waw.pl</a><br />
                                22 255 05 21
                            </div>
                        </div>
                        <div class="uns-half-content second team">
                            <div class="inside-team-memeber">
                                specjalista ds. PR
                                <div class="person-name">Milena Ryćkowska</div>
                                <a href="mailto:m.ryckowska@dsh.waw.pl">m.ryckowska@dsh.waw.pl</a><br />
                                22 255 05 22
                            </div>
                        </div>
                        <div class="uns-half-content first team">
                            <div class="inside-team-memeber">
                                &nbsp;
                                <div class="person-name">Agata Krajewska</div>
                                <a href="mailto:a.krajewska@dsh.waw.pl">a.krajewska@dsh.waw.pl</a><br />
                                22 255 05 22
                            </div>
                        </div>

                    </div>

            </div>
<!---------------------------------------------------------->
            <div class="uns-second-row bg-color-template">

                    <div class="uns-section-empty col team">

                        <div class="inside-members-group-name">
                        Księgarnia<br>
                        tel. 22 255 05 02
                        </div>

                    </div>

                    <div class="uns-section-content col persons" style="float:right">
                        <div class="uns-half-content first team">
                            <div class="inside-team-memeber">
                                kierownik księgarni
                                <div class="person-name">Mariusz Matusiak</div>
                                <a href="mailto:m.matusiak@dsh.waw.pl">m.matusiak@dsh.waw.pl</a><br />
                                22 255 05 02
                            </div>
                        </div>
                    </div>

            </div>

<!---------------------------------------------------------->
            <div class="uns-second-row black-bg-row">

                <div class="uns-section-empty col team black-bg">

                    <div class="inside-members-group-name">
                        Księgowość<br>
                        tel. 22 255 05 35
                    </div>

                </div>

                <div class="uns-section-content col persons" style="float:right">

{{--                    <div class="uns-half-content first team">
                        <div class="inside-team-memeber">
                            główna księgowa
                            <div class="person-name">Elżbieta Durzyńska</div>
                            <a href="mailto:e.durzynska@dsh.waw.pl">e.durzynska@dsh.waw.pl</a><br />
                            22 255 05 35
                        </div>
                    </div>--}}
                    <div class="uns-half-content first team">
                        <div class="inside-team-memeber">
                            główna księgowa
                            <div class="person-name">Anna Kula</div>
                            <a href="mailto:a.kula@dsh.waw.pl">a.kula@dsh.waw.pl</a><br />
                            22 255 05 35
                        </div>
                    </div>
                    <div class="uns-half-content second team">
                        <div class="inside-team-memeber">
                            zastępca głównej księgowej/specjalista ds. księgowości i płac
                            <div class="person-name">Magdalena Błaszczyk</div>
                            <a href="mailto:m.blaszczyk@dsh.waw.pl">m.blaszczyk@dsh.waw.pl</a><br />
                            22 255 05 35
                        </div>
                    </div>

                    <div class="uns-half-content first team">
                        <div class="inside-team-memeber">
                            specjalista ds. księgowości
                            <div class="person-name">Alicja Jasińska</div>
                            <a href="mailto:a.jasinska@dsh.waw.pl">a.jasinska@dsh.waw.pl</a><br />
                            22 255 05 35
                        </div>
                    </div>

                    <div class="uns-half-content second team">
                        <div class="inside-team-memeber">
                            specjalista ds. księgowości
                            <div class="person-name">Ewa Sygowska</div>
                            <a href="mailto:e.sygowska@dsh.waw.pl">e.sygowska@dsh.waw.pl</a><br />
                            22 255 05 35
                        </div>
                    </div>


                </div>

            </div>
<!---------------------------------------------------------->
<!--
            <div class="uns-second-row bg-color-template">

                <div class="uns-section-empty col team">

                    <div class="inside-members-group-name">
                        Organizacja Spotkań<br>
                        tel. 22 255 05 31
                    </div>

                </div>

                <div class="uns-section-content col persons" style="float:right">
                    <div class="uns-half-content first team">
                        <div class="inside-team-memeber">
                            koordynator spotkań
                            <div class="person-name">Marta Łukawska</div>
                            <a href="mailto:m.lukawska@dsh.waw.pl">m.lukawska@dsh.waw.pl</a><br />
                            22 255 05 31
                        </div>
                    </div>
                </div>

            </div>

            <div class="uns-second-row bg-color-template">

                <div class="uns-section-empty col team black-bg">

                    <div class="inside-members-group-name">
                        Pracownia DTP<br>
                        {{--tel. 22 255 05 23--}}
                    </div>

                </div>

                <div class="uns-section-content col persons" style="float:right">
                    <div class="uns-half-content first team">
                        <div class="inside-team-memeber">
                            fotografia, dtp
                            <div class="person-name">Tomasz Kubaczyk</div>
                            <a href="mailto:t.kubaczyk@dsh.waw.pl">t.kubaczyk@dsh.waw.pl</a><br />
                            {{--22 255 05 23--}}
                        </div>
                    </div>
                </div>

            </div>

            -->
            <!---------------------------------------------------------->

<!---------------------------------------------------------->
                <div class="uns-second-row bg-color-template">

                <div class="uns-section-empty col team black-bg">

                    <div class="inside-members-group-name">
                        Publikacje<br>
                        tel. 22 255 05 55
                    </div>

                </div>

                <div class="uns-section-content col persons" style="float:right">


                    <div class="uns-half-content first team">
                        <div class="inside-team-memeber">
                            kierownik
                            <div class="person-name">Małgorzata Purzyńska</div>
                            <a href="mailto:m.purzynska@dsh.waw.pl">m.purzynska@dsh.waw.pl</a><br />
                            22 255 05 20
                        </div>
                    </div>

                    <div class="uns-half-content second team">
                        <div class="inside-team-memeber">
                            specjalista ds. publikacji
                            <div class="person-name">Marta Rogowska-Stradomska</div>
                            <a href="mailto:m.rogowska@dsh.waw.pl">m.rogowska@dsh.waw.pl</a><br />
                            22 255 05 56
                        </div>
                    </div>

                    <div class="uns-half-content first team">
                        <div class="inside-team-memeber">
                            specjalista ds. publikacji
                            <div class="person-name">Anna Piłat</div>
                            <a href="mailto:m.purzynska@dsh.waw.pl">a.pilat@dsh.waw.pl</a><br />
                            22 255 05 24
                        </div>
                    </div>

                    <div class="uns-half-content second team">
                        <div class="inside-team-memeber">
                            &nbsp;
                            <div class="person-name">Magdalena Czerwiec-Pichlińska</div>
                            urlop wychowawczy
                        </div>
                    </div>

                </div>

            </div>
<!---------------------------------------------------------->
                <div class="uns-second-row black-bg-row">

                <div class="uns-section-empty col team black-bg">

                    <div class="inside-members-group-name">
                        Warszawska Inicjatywa Kresowa<br>
                        tel. 22 255 05 50
                    </div>

                </div>

                <div class="uns-section-content col persons" style="float:right">
                    <div class="uns-half-content first team">
                        <div class="inside-team-memeber">
                            koordynator programu WIK
                            <div class="person-name">Tomasz Kuba Kozłowski</div>
                            <a href="mailto:t.kozlowski@dsh.waw.pl">t.kozlowski@dsh.waw.pl</a><br />
                            22 255 05 50
                        </div>
                    </div>
                </div>

            </div>


            <div class="uns-second-row bg-color-template">

                <div class="uns-section-empty col team black-bg">

                    <div class="inside-members-group-name">
                        Warszawska Pracownia Multimedialna<br />
                        tel. 22 255 05 45
                    </div>


                </div>

<div class="uns-section-content col persons" style="float:right">

                    <div class="uns-half-content first team">
                        <div class="inside-team-memeber">
                            kierownik
                            <div class="person-name">Agnieszka Tomasińska</div>
                            <a href="mailto:a.tomasinska@dsh.waw.pl">a.tomasinska@dsh.waw.pl</a><br />
                            22 255 05 45
                        </div>
                    </div>
                    <div class="uns-half-content second team">
                        <div class="inside-team-memeber">
                            starszy specjalista ds. filmowych
                            <div class="person-name">Dariusz Krajewski</div>
                            <a href="mailto:d.krajewski@dsh.waw.pl">d.krajewski@dsh.waw.pl</a><br />
                            22 255 05 46
                        </div>
                    </div>
    <div class="uns-half-content first team">
        <div class="inside-team-memeber">
            archiwista
            <div class="person-name">Maciej Kamiński</div>
            <a href="mailto:m.kaminski@dsh.waw.pl">m.kaminski@dsh.waw.pl</a><br />
        </div>
    </div>

                </div>

            </div>

<!---------------------------------------------------------->

                <div class="uns-second-row black-bg-row">
                <div class="uns-section-empty col team black-bg">

                    <div class="inside-members-group-name">
                        Wystawy<br>
                        tel. 22 255 05 15
                    </div>

                </div>

                <div class="uns-section-content col persons" style="float:right">

                    <div class="uns-half-content first team">
                        <div class="inside-team-memeber" style="line-height:150%">
                            pełnomocnik dyrektora ds. popularyzacji<br />
                            kierownik działu wystaw
                            <div class="person-name">Monika Kapa-Cichocka</div>
                            <a href="mailto:m.cichocka@dsh.waw.pl">m.cichocka@dsh.waw.pl</a><br /><br />
                            22 255 05 15
                        </div>
                    </div>

                    <div class="uns-half-content second team">
                        <div class="inside-team-memeber">
                            zastępczyni kierownika
                            <div class="person-name">Zofia Zakrzewska</div>
                            <a href="mailto:z.zakrzewska@dsh.waw.pl">z.zakrzewska@dsh.waw.pl</a><br />
                            22 255 05 55
                        </div>
                    </div>

{{--                    <div class="uns-half-content first team">
                        <div class="inside-team-memeber">
                            sekretarz działu
                            <div class="person-name">Anna Mech</div>
                            <a href="mailto:a.mech@dsh.waw.pl">a.mech@dsh.waw.pl</a><br />
                            22 255 05 15
                        </div>
                    </div>--}}
                    <div class="uns-half-content second team">
                        <div class="inside-team-memeber">
                            koordynator produkcji wystaw
                            <div class="person-name">Barbara Roszuk-Galus</div>
                            urlop macierzyński
{{--                            <a href="mailto:b.galus@dsh.waw.pl">b.galus@dsh.waw.pl</a><br />
                            22 255 05 16--}}
                        </div>
                    </div>
                    <div class="uns-half-content second team">
                        <div class="inside-team-memeber">
                            specjalista ds. projektów międzynarodowych
                            <div class="person-name">Julia Libera</div>
                            <a href="mailto:j.libera@dsh.waw.pl">j.libera@dsh.waw.pl</a><br />
                            22 255 05 15
                        </div>
                    </div>

                    <div class="uns-half-content first team">
                        <div class="inside-team-memeber">
                            producentka wystaw / impresariat
                            <div class="person-name">Anna Adamczyk</div>
                            <a href="mailto:a.adamczyk@dsh.waw.pl">a.adamczyk@dsh.waw.pl</a><br />
                            22 255 05 16
                        </div>
                </div>

            </div>
<!---------------------------------------------------------->


            </div>

        </div>


</div>