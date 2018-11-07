<?php

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'M2S.PoiMap',
    'Places',
    'Points of interest',
    'EXT:poi_map/Resources/Public/Icons/Extension.png'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['poimap_places'] = 'pi_flexform';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'poimap_places',
    'FILE:EXT:poi_map/Configuration/FlexForms/PoiMapPlaces.xml'
);
