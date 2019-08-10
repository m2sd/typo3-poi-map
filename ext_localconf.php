<?php
defined('TYPO3_MODE') || die('Access denied.');

(function () {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'M2S.PoiMap',
        'Places',
        [
            'Place' => 'list',
        ]
    );

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][] = [
        'nodeName' => 'mapsPositionPicker',
        'priority' => 30,
        'class' => \M2S\PoiMap\FormEngine\FieldWizard\MapsPositionPicker::class,
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][] =
        \M2S\PoiMap\Utility\ConfigurationUtility::class . '->preRenderJsHook';
})();
