<ul class="nav navbar-nav">
    <li role="presentation"><a href="{{ url($lang['tag'].'/administrator/homepage') }}">Strona główna</a></li>
    <li role="presentation"><a href="{{ url($lang['tag'].'/administrator/links') }}">Linki</a></li>
    <li role="presentation"><a href="{{ url($lang['tag'].'/administrator/contents') }}">Artykuły</a></li>
    <li role="presentation" class="dropdown">
        <a href="#" ng-click="$event.preventDefault()" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            Wydarzenia <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li>
                <a href="{{ url($lang['tag'].'/administrator/agendas') }}">
                    Wydarzenia
                </a>
            </li>
            <li>
                <a href="{{ url($lang['tag'].'/administrator/groups') }}">
                    Grupy
                </a>
            </li>
            <li>
                <a href="{{ url($lang['tag'].'/administrator/members') }}">
                    Uczestnicy
                </a>
            </li>
        </ul>
    </li>
    <li role="presentation"><a href="{{ url($lang['tag'].'/administrator/pdfprogram') }}">Program w pdf</a></li>
    <li>
        <a href="{{ url($lang['tag'].'/administrator/publications') }}">
            Ogłoszenia
        </a>
    </li>
    <li>
        <a href="{{ url($lang['tag'].'/administrator/categories') }}">
            Kategorie
        </a>
    </li>
    <li role="presentation" class="dropdown">
        <a href="#" ng-click="$event.preventDefault()" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            Media <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li>
                <a href="{{ url($lang['tag'].'/administrator/media') }}">
                    Media
                </a>
            </li>
            <li>
                <a href="{{ url($lang['tag'].'/administrator/youtube') }}">
                    Youtube
                </a>
            </li>
        </ul>
    </li>

    <li role="presentation"><a href="{{ url($lang['tag'].'/administrator/galleries') }}">Galerie</a></li>
    <li role="presentation"><a href="{{ url($lang['tag'].'/administrator/pictures') }}">Obrazki</a></li>
    <li role="presentation"><a href="{{ url($lang['tag'].'/administrator/slides') }}">Slider</a></li>
    <li role="presentation"><a href="{{ url($lang['tag'].'/administrator/newsletter') }}">Newsletter</a></li>
    <li role="presentation"><a href="{{ url($lang['tag'].'/administrator/viewprofiles') }}">Profile widoków</a></li>
    <li role="presentation"><a href="{{ url($lang['tag'].'/administrator/logotypes') }}">Logotypy</a></li>
    <li role="presentation"><a href="{{ url('/administrator/oldbase') }}">Test</a></li>
</ul>