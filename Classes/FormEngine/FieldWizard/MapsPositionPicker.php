<?php
declare(strict_types = 1);

namespace M2S\PoiMap\FormEngine\FieldWizard;

use M2S\PoiMap\Utility\ConfigurationUtility;
use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Lang\LanguageService;

class MapsPositionPicker extends AbstractNode
{
    /**
     * Render a google map which can be used to pick a position via click
     *
     * @return array
     */
    public function render(): array
    {
        $result = $this->initializeResultArray();
        /** @var ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $configuration = $objectManager->get(ConfigurationUtility::class);
        $apiKey = $configuration->wizard_useFrontendApi
            ? $configuration->maps_apiKey
            : $configuration->wizard_apiKey;

        $result['html'] = <<<TEMPLATE
<div
	class="t3js-poimap-position-picker"
	data-view="#t3js-poimap-map-{$this->data['databaseRow']['uid']}-{$this->data['fieldName']}"
	data-output-name="{$this->data['parameterArray']['itemFormElName']}"
	data-api-key="$apiKey"
	data-center="{$configuration->wizard_center}"
	data-zoom="{$configuration->wizard_zoom}"
>
	<div id="t3js-poimap-map-{$this->data['databaseRow']['uid']}-{$this->data['fieldName']}" class="t3js-poimap-map"></div>
</div>
TEMPLATE;

        $result['requireJsModules'] = [
            'TYPO3/CMS/PoiMap/PositionPicker',
        ];
        $result['stylesheetFiles'] = [
            'EXT:poi_map/Resources/Public/Css/PositionPicker.css',
        ];

        return $result;
    }

    /**
     * Wrapper for the $GLOBALS['LANG'] variable
     *
     * @return LanguageService
     */
    public function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
