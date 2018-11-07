<?php
$EM_CONF[$_EXTKEY] = [
    'title'            => 'Points of interest',
    'description'      => 'Categorized points of interest with google maps integration',
    'category'         => 'plugin',
    'author'           => 'Michael Marcenich',
    'author_email'     => 'info@m-squared-solutions.it',
    'author_company'   => 'M² Solutions',
    'version'          => '1.0.0',
    'state'            => 'stable',
    'uploadfolder'     => 0,
    'createDirs'       => '',
    'clearCacheOnLoad' => 1,
    'constraints'      => [
        'depends'   => [
            'php'   => '7.0.0-7.2.99',
            'typo3' => '8.7.0-8.7.99',
        ],
        'conflicts' => [],
        'suggests'  => [],
    ],
    'autoload'         => [
        'psr-4' => [
            'M2S\\PoiMap\\' => 'Classes'
        ]
    ],
];
