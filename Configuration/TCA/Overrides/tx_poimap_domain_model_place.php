<?php
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable(
    'poi_map',
    'tx_poimap_domain_model_place',
    'categories',
    [
        'position' => 'replace:categories',
        'l10n_mode' => 'exclude',
        'l10n_display' => 'defaultAsReadonly'
    ]
);
