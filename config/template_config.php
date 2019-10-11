<?php

return [
    'template_config_data'=>[
        'is_many'=>[
            [
                'name'=>'Jedna kolumna',
                'show'=>'one'
            ],
            [
                'name'=>'Dwie kolumny',
                'show'=>'double'
            ],
            [
                'name'=>'Trzy kolumny',
                'show'=>'trio'
            ]
        ],
        'is_filteredleaf'=>[
            [
                'name'=>'Jedna kolumna',
                'show'=>'one'
            ],
            [
                'name'=>'Dwie kolumny',
                'show'=>'double'
            ],
            [
                'name'=>'Trzy kolumny',
                'show'=>'trio'
            ]
        ],
        'is_unstandard'=>[
            [
                'name'=>'Misja',
                'blade'=>'unstandard.mission',
                'is_entity_data'=>false,
            ],
            [
                'name'=>'Zespół',
                'blade'=>'unstandard.team',
                'is_entity_data'=>false,
            ],
            [
                'name'=>'Kawiarnia Karowa 20',
                'blade'=>'unstandard.cafe',
                'is_entity_data'=>false,
            ],
            [
                'name'=>'Historia ulicy',
                'blade'=>'unstandard.history',
                'is_entity_data'=>false,
            ],
            [
                'name'=>'Księgarania',
                'blade'=>'unstandard.books',
                'is_entity_data'=>false,
            ],
            [
                'name'=>'Dla mediów',
                'blade'=>'unstandard.media',
                'is_entity_data'=>false,
            ],
            [
                'name'=>'Ogłoszenia',
                'blade'=>'unstandard.classifieds',
                'is_entity_data'=>true,
                'entity'=>'App\Entities\Publication'
            ],

            [
                'Wirtualny spacer',
                'blade'=>'unstandard.marcher',
                'is_entity_data'=>false
            ],

            [
                'Kontakt',
                'blade'=>'unstandard.contact',
                'is_entity_data'=>false
            ]

        ]
    ]
];