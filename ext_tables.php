<?php
defined('TYPO3_MODE') || die('Access denied.');

(function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_poimap_domain_model_place');

    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

    $icons = [
        'place-default' => [],
        'place-tourist-attraction' => [],
        'place-landmarks-or-historical-buildings' => [],
        'place-accommodation' => [],
        'place-local-business' => [],
        'place-residence' => [],
        'place-landform' => [],
        'place-civic-structure' => [],
        'place-administrative-area' => []
    ];
    foreach ($icons as $identifier => $config) {
        if (is_string($config)) {
            $config = [
                'path' => $config,
                'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class
            ];
        }
        if (!isset($config['path']) || !$config['path']) {
            $config['path'] = 'EXT:poi_map/Resources/Public/Icons/Types/Default.svg';
        }
        if (!isset($config['provider']) || !$config['provider']) {
            $config['provider'] = \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class;
        }

        $iconRegistry->registerIcon(
            'tx-poimap-'.$identifier,
            $config['provider'],
            ['source' => $config['path']]
        );
    }
})();
