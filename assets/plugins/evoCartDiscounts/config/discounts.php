<?php

    return [
        'title' => 'Groups',

//        'show_in_templates' => [ 3 ],

//        'show_in_docs' => [ 2 ],

//        'hide_in_docs' => [ 10, 63 ],

//        'order' => 2,

//        'container' => ['samplecontainer', 'default'],

        'templates' => [
            'owner' => '
                <div>Общая:<br> [+text+]</div>
                <div>На категорию:<br> [+images+]</div>
                <div>На бренд:<br> [+brands+]</div>
            ',

            'brands' => '
                [+brand+]
                [+brand_sale+]
            ',

            'files' => '<a href="[+file+]">[+text+], ([+checkbox+])</a> ',

            'checkbox' => '[+title+] ',
        ],

        'fields' => [
            'main' => [
                'caption' => 'Общая скидка',
                'type'    => 'text',
            ],

            'rules' => [
                'caption' => 'Комбо скидка (укажите один или оба варианта применения скидки)',
                'type'    => 'group',
                'fields'  => [
                    'brand' => [
                        'caption'  => 'Бренд',
                        'type'     => 'dropdown',
                        'elements' => '@SELECT `value` FROM [+PREFIX+]site_tmplvar_contentvalues WHERE `tmplvarid` = 15 GROUP BY `value` ORDER BY `value` ASC',
                        'default'  => '',
                    ],
                    'cat' => [
                        'caption'  => 'Категория',
                        'type'     => 'dropdown',
                        'elements' => '@SELECT pagetitle, id FROM [+PREFIX+]site_content WHERE template = 5 ORDER BY pagetitle',
                        'default'  => '',
                    ],
                    'sale' => [
                        'caption' => 'Скидка',
                        'type'    => 'text',
                    ],
                ]
            ],

        ],
    ];
