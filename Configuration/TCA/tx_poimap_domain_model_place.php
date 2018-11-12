<?php

$langFile = 'LLL:EXT:poi_map/Resources/Private/Language/locallang_tca.xlf';

return [
    'ctrl' => [
        'label' => 'name',
        'label_alt' => 'alternate_name,description',
        'sortby' => 'sorting',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'title' => $langFile.':tx_poimap_domain_model_place',
        'delete' => 'deleted',
        'versioningWS' => true,
        'origUid' => 't3_origuid',
        'type' => 'additional_type',
        'hideAtCopy' => true,
        'prependAtCopy' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.prependAtCopy',
        'copyAfterDuplFields' => 'sys_language_uid',
        'useColumnsForDefaultValues' => 'sys_language_uid,additional_type',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'languageField' => 'sys_language_uid',
        'translationSource' => 'l10n_source',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
            'fe_group' => 'fe_group'
        ],
        'iconfile' => 'EXT:poi_map/Resources/Public/Icons/Types/Default.svg',
        'typeicon_column' => 'additional_type',
        'typeicon_classes' => [
            'default' => 'tx-poimap-place-default',
            'TouristAttraction' => 'tx-poimap-place-tourist-attraction',
            'LandmarksOrHistoricalBuildings' => 'tx-poimap-place-landmarks-or-historical-buildings',
            'Accommodation' => 'tx-poimap-place-accommodation',
            'LocalBusiness' => 'tx-poimap-place-local-business',
            'Residence' => 'tx-poimap-place-residence',
            'Landform' => 'tx-poimap-place-landform',
            'CivicStructure' => 'tx-poimap-place-civic-structure',
            'AdministrativeArea' => 'tx-poimap-place-administrative-area',
        ],
        'searchFields' => 'name,url,alternate_name,description'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,l10n_source,additional_type,name,alternate_name,description,disambiguating_description,image,url,starttime,endtime,fe_group'
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ],
                ],
                'default' => 0,
            ]
        ],
        'l10n_parent' => [
            'exclude' => true,
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        '',
                        0
                    ]
                ],
                'foreign_table' => 'tx_poimap_domain_model_place',
                'foreign_table_where' => 'AND tx_poimap_domain_model_place.uid=###REC_FIELD_l10n_parent### AND tx_poimap_domain_model_place.sys_language_uid IN (-1,0)',
                'default' => 0
            ]
        ],
        'l10n_source' => [
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
                'default' => ''
            ]
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255
            ]
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:hidden.I.0'
                    ]
                ]
            ]
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0
            ],
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly'
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ]
            ],
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly'
        ],
        'fe_group' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.fe_group',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'maxitems' => 20,
                'items' => [
                    [
                        'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.hide_at_login',
                        -1
                    ],
                    [
                        'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.any_login',
                        -2
                    ],
                    [
                        'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.usergroups',
                        '--div--'
                    ]
                ],
                'exclusiveKeys' => '-1,-2',
                'foreign_table' => 'fe_groups',
                'foreign_table_where' => 'ORDER BY fe_groups.title',
                'enableMultiSelectFilterTextfield' => true
            ]
        ],
        'additional_type' => [
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.type',
            'exclude' => true,
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        $langFile.':tx_poimap_domain_model_place.additional_type.I.0',
                        '',
                        'tx-poimap-place-default'
                    ],
                    [
                        $langFile.':tx_poimap_domain_model_place.additional_type.I.TouristAttraction',
                        'TouristAttraction',
                        'tx-poimap-place-tourist-attraction'
                    ],
                    [
                        $langFile.':tx_poimap_domain_model_place.additional_type.I.LandmarksOrHistoricalBuildings',
                        'LandmarksOrHistoricalBuildings',
                        'tx-poimap-place-landmarks-or-historical-buildings'
                    ],
                    [
                        $langFile.':tx_poimap_domain_model_place.additional_type.I.Accommodation',
                        'Accommodation',
                        'tx-poimap-place-accommodation'
                    ],
                    [
                        $langFile.':tx_poimap_domain_model_place.additional_type.I.LocalBusiness',
                        'LocalBusiness',
                        'tx-poimap-place-local-business'
                    ],
                    [
                        $langFile.':tx_poimap_domain_model_place.additional_type.I.Residence',
                        'Residence',
                        'tx-poimap-place-residence'
                    ],
                    [
                        $langFile.':tx_poimap_domain_model_place.additional_type.I.Landform',
                        'Landform',
                        'tx-poimap-place-landform'
                    ],
                    [
                        $langFile.':tx_poimap_domain_model_place.additional_type.I.CivicStructure',
                        'CivicStructure',
                        'tx-poimap-place-civic-structure'
                    ],
                    [
                        $langFile.':tx_poimap_domain_model_place.additional_type.I.AdministrativeArea',
                        'AdministrativeArea',
                        'tx-poimap-place-administrative-area'
                    ]
                ],
                'default' => '',
                'authMode' => $GLOBALS['TYPO3_CONF_VARS']['BE']['explicitADmode'],
                'authMode_enforce' => 'strict',
            ]
        ],
        'name' => [
            'l10n_mode' => 'prefixLangTitle',
            'label' => $langFile.':tx_poimap_domain_model_place.name',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
            ],
        ],
        'alternate_name' => [
            'l10n_mode' => 'prefixLangTitle',
            'exclude' => true,
            'label' => $langFile.':tx_poimap_domain_model_place.alternate_name',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
                'softref' => 'email[subst]'
            ]
        ],
        'url' => [
            'exclude' => true,
            'label' => $langFile.':tx_poimap_domain_model_place.url',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'size' => 50,
                'max' => 1024,
                'eval' => 'trim',
                'fieldControl' => [
                    'linkPopup' => [
                        'options' => [
                            'title' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_link_formlabel',
                        ],
                    ],
                ],
                'softref' => 'typolink'
            ]
        ],
        'disambiguating_description' => [
            'exclude' => true,
            'label' => $langFile.':tx_poimap_domain_model_place.disambiguating_description',
            'config' => [
                'type' => 'text',
                'cols' => 80,
                'rows' => 15,
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
                'softref' => 'typolink_tag,images,email[subst],url'
            ]
        ],
        'description' => [
            'l10n_mode' => 'prefixLangTitle',
            'label' => $langFile.':tx_poimap_domain_model_place.description',
            'config' => [
                'type' => 'text',
                'cols' => 80,
                'rows' => 15,
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
                'softref' => 'typolink_tag,images,email[subst],url'
            ]
        ],
        'image' => [
            'label' => $langFile.':tx_poimap_domain_model_place.image',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('image', [
                'maxitems' => 1,
                'appearance' => [
                    'createNewRelationLinkTitle' => $langFile.':tx_poimap_domain_model_place.image.create_record'
                ],
                'overrideChildTca' => [
                    'types' => [
                        '0' => [
                            'showitem' => '
                                --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                            'showitem' => '
                                --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                            'showitem' => '
                                --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                            'showitem' => '
                                --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.audioOverlayPalette;audioOverlayPalette,
                                --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                            'showitem' => '
                                --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.videoOverlayPalette;videoOverlayPalette,
                                --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                            'showitem' => '
                                --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
                        ]
                    ],
                ],
            ], $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'])
        ],
        'marker_icon' => [
            'label' => $langFile . ':tx_poimap_domain_model_place.marker_icon',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('marker_icon', [
                'maxitems' => 1,
                'appearance' => [
                    'createNewRelationLinkTitle' => $langFile . ':tx_poimap_domain_model_place.marker_icon.create_record'
                ],
                'overrideChildTca' => [
                    'types' => [
                        '0' => [
                            'showitem' => '
                                --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                            'showitem' => '
                                --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                --palette--;;filePalette'
                        ]
                    ],
                ],
            ], 'gif,jpg,jpeg,tif,tiff,bmp,png,svg')
        ],
        'geo_coordinates' => [
            'label' => $langFile.':tx_poimap_domain_model_place.geo_coordinates',
            'config' => [
                'type' => 'input',
                'size' => 100,
                'max' => 50,
                'fieldWizard' => [
                    'mapsPositionPicker' => [
                        'renderType' => 'mapsPositionPicker'
                    ]
                ]
            ],
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly'
        ]
    ],
    'types' => [
        '1' => [
            'showitem' => '
				--div--;LLL:EXT:poi_map/Resources/Private/Language/locallang_tca.xlf:tab.general,
                    additional_type,
                    --palette--;LLL:EXT:poi_map/Resources/Private/Language/locallang_tca.xlf:tx_poimap_domain_model_place.palette.names;names,
                    --palette--;LLL:EXT:poi_map/Resources/Private/Language/locallang_tca.xlf:tx_poimap_domain_model_place.palette.descriptions;descriptions,
                    url,
                --div--;LLL:EXT:poi_map/Resources/Private/Language/locallang_tca.xlf:tab.images,
                    image,
                    marker_icon,
                --div--;LLL:EXT:poi_map/Resources/Private/Language/locallang_tca.xlf:tab.coordinates,
                    geo_coordinates,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;hidden,
                    --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                    categories
            '
        ]
    ],
    'palettes' => [
        'names' => [
            'showitem' => '
                name,
                --linebreak--,
                alternate_name
            ',
        ],
        'descriptions' => [
            'showitem' => '
                description,
                --linebreak--,
                disambiguating_description
            ',
        ],
        'language' => [
            'showitem' => '
                sys_language_uid;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:sys_language_uid_formlabel,
                l10n_parent
            ',
        ],
        'hidden' => [
            'showitem' => '
                hidden;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:field.default.hidden
            ',
        ],
        'access' => [
            'showitem' => '
                starttime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel,
                endtime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:endtime_formlabel,
                --linebreak--,
                fe_group;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:fe_group_formlabel,
                --linebreak--,editlock
            ',
        ]
    ]
];
