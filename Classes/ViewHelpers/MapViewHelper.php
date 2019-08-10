<?php
declare(strict_types = 1);

/**
 * Created by PhpStorm.
 * User: main
 * Date: 9/30/18
 * Time: 1:13 PM
 */
namespace M2S\PoiMap\ViewHelpers;

use M2S\PoiMap\GoogleMaps\Map;
use M2S\PoiMap\GoogleMaps\Marker;
use M2S\PoiMap\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class MapViewHelper extends AbstractViewHelper
{
    /**
     * Specific tag attributes
     * Will be excluded from additionalAttributes
     *
     * @var array
     */
    private static $tagAttributes = [
        'class',
        'dir',
        'lang',
        'style',
        'title',
        'accesskey',
        'tabindex',
    ];

    /**
     * Disabled escaping of output
     *
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * initialize acceptable arguments
     */
    public function initializeArguments()
    {
        $this->registerArgument(
            'places',
            'array',
            'A set of places to be placed on the map as markers',
            false,
            []
        );
        $this->registerArgument(
            'as',
            'string',
            'The name for the iteration variable in the info window template',
            false,
            'place'
        );
        $this->registerArgument(
            'options',
            'array',
            'Options to override the default map settings.'
        );
        $this->registerArgument(
            'mapStyles',
            'array',
            'Options to override the styles option of the map.'
        );
        $this->registerArgument(
            'type',
            'string',
            'The type of map that should be displayed.'
        );
        $this->registerArgument(
            'zoom',
            'integer',
            'The zoom of the map'
        );
        $this->registerArgument(
            'center',
            'string',
            'The center of the map'
        );
        /** @todo: rename options for info windows on next major release */
        $this->registerArgument(
            'enableInfo',
            'bool',
            'Set to false to disable on click info windows.',
            false,
            true
        );
        $this->registerArgument(
            'enableInfoSingle',
            'bool',
            'Set to false to allow multiple info windows to be displayed at the same time.',
            false,
            true
        );
        $this->registerArgument(
            'infoOptions',
            'array',
            'Additional configuration for SnazzyInfoWindow instances',
            false,
            []
        );
        $this->registerArgument(
            'markerIcon',
            'string',
            'Set a custom marker icon for all markers on this map'
        );
        $this->registerArgument(
            'class',
            'string',
            'CSS class(es) for this element'
        );
        $this->registerArgument(
            'dir',
            'string',
            'Text direction for this HTML element. Allowed strings: "ltr" (left to right), "rtl" (right to left)'
        );
        $this->registerArgument(
            'lang',
            'string',
            'Language for this element. Use short names specified in RFC 1766'
        );
        $this->registerArgument(
            'style',
            'string',
            'Individual CSS styles for this element'
        );
        $this->registerArgument(
            'title',
            'string',
            'Tooltip text of element'
        );
        $this->registerArgument(
            'accesskey',
            'string',
            'Keyboard shortcut to access this element'
        );
        $this->registerArgument(
            'tabindex',
            'integer',
            'Specifies the tab order of this element'
        );
        $this->registerArgument(
            'onclick',
            'string',
            'JavaScript evaluated for the onclick event'
        );
        $this->registerArgument(
            'additionalAttributes',
            'array',
            'Additional attributes for the maps container'
        );
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return self::renderStatic(
            $this->arguments,
            $this->buildRenderChildrenClosure(),
            $this->renderingContext
        );
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return mixed|string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $templateVariableContainer = $renderingContext->getVariableProvider();
        /** @var ConfigurationUtility $configuration */
        $configuration = GeneralUtility::makeInstance(ConfigurationUtility::class);

        $map = new Map();
        /** @todo: extract into separate entity */
        $map->enableInfoWindows($arguments['enableInfo'], $arguments['enableInfoSingle'], $arguments['infoOptions']);
        /** @var Place $place */
        foreach ($arguments['places'] as $place) {
            $position = $place->getLatLngArray();
            if (count($position)) {
                $marker = new Marker($position);

                if ($arguments['enableInfo']) {
                    $templateVariableContainer->add($arguments['as'], $place);
                    $info = trim(preg_replace('/\\>\\s+\\</', '><', $renderChildrenClosure()));
                    if ($info) {
                        $marker->setInfoWindow($info);
                    } else {
                        $marker->setInfoWindow("<div class=\"tx-poimap-info\"><h3>{$place->getName()}</h3></div>");
                    }
                    $templateVariableContainer->remove($arguments['as']);
                }

                $customIcon = $place->getMarkerIcon();
                if ($customIcon) {
                    $customIcon = GeneralUtility::makeInstance(ImageService::class)->getImageUri($customIcon->getOriginalResource());
                    $marker->setOption('icon', $customIcon);
                } elseif (GeneralUtility::validPathStr($arguments['markerIcon'])) {
                    $marker->setOption('icon', $arguments['markerIcon']);
                }

                $map->addMarker($marker);
            }
        }

        if ($arguments['options']) {
            $map->setOptions($arguments['options']);
        }
        if ($arguments['type']) {
            $map->setOption('mapTypeId', $arguments['type']);
        }
        if (isset($arguments['mapStyles'])) {
            $map->setOption('styles', $arguments['mapStyles']);
        }
        if ($arguments['zoom']) {
            $map->setOption('zoom', (int)$arguments['zoom']);
        }
        if ($arguments['center']) {
            $center = explode(',', $arguments['center']);
            $map->setOption('center', ['lat' => (float)$center[0], 'lng' => (float)$center[1]]);
        }

        $configuration->addMap($map);

        $tag = "<div id=\"{$map->getId()}\"";
        $attributeKeys = array_intersect(array_keys($arguments), self::$tagAttributes);
        foreach ($attributeKeys as $attributeKey) {
            $tag .= " $attributeKey=\"{$arguments[$attributeKey]}\"";
        }
        if ($arguments['additionalAttributes']) {
            foreach ($arguments['additionalAttributes'] as $attributeKey => $attributeValue) {
                if (!in_array($attributeKey, self::$tagAttributes)) {
                    $tag .= " $attributeKey=\"{$attributeValue}\"";
                }
            }
        }
        $tag .= '></div>';

        return $tag;
    }
}
