<?php
declare(strict_types=1);

namespace M2S\PoiMap\Utility;

use M2S\PoiMap\GoogleMaps\Map;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Class ConfigurationUtility
 */
class ConfigurationUtility implements SingletonInterface
{
    /**
     * @var array
     */
    protected $configuration;
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\M2S\PoiMap\GoogleMaps\Map>
     */
    protected $maps = null;
    /**
     * @var array
     */
    protected $cache;
    /**
     * The default options for the map
     *
     * @var array
     */
    protected $mapDefaults = [
        'zoom' => 1,
        'center' => [
            'lat' => 0.0,
            'lng' => 0.0
        ],
        'mapTypeId' => 'roadmap'
    ];

    /**
     * ConfigurationUtility constructor.
     */
    public function __construct()
    {
        $configuration = [];
        if (ConfigurationUtility::isTypo3OlderThen9()) {
            $extensionConfigurations = (array)$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'];
            $possibleConfig = unserialize((string)$extensionConfigurations['poi_map']);
            if (!empty($possibleConfig) && is_array($possibleConfig)) {
                $configuration = $possibleConfig;
            }
        } else {
            // @codeCoverageIgnoreStart
            $configuration = GeneralUtility::makeInstance('TYPO3\CMS\Core\Configuration\ExtensionConfiguration')->get('poi_map');
            // @codeCoverageIgnoreEnd
        }

        $this->configuration = $configuration;
        if ($defaults = $this->maps_defaultOptions) {
            if (!($defaults = json_decode($defaults, true))) {
                throw new \InvalidArgumentException('Malformed json in \'maps.default_options\' extension setting.', 1539969078);
            }
            $this->setMapDefaults($defaults);
        }
        if ($type = $this->maps_defaultType) {
            $this->overrideMapDefaults(['mapTypeId' => $type]);
        }
        if ($style = $this->maps_defaultStyle) {
            if (!($style = json_decode($style, true))) {
                throw new \InvalidArgumentException('Malformed json in \'maps.default_style\' extension setting.', 1539969078);
            }
            $this->overrideMapDefaults(['styles' => $style]);
        }
        $this->maps = new ObjectStorage();
    }

    /**
     * Get full extension configuration
     *
     * @return array
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    /**
     * Replace map defaults
     *
     * @param array $options
     *
     * @return ConfigurationUtility
     */
    public function setMapDefaults(array $options): self
    {
        $this->mapDefaults = $options;

        return $this;
    }

    /**
     * Override map defaults
     *
     * @param array $options
     *
     * @return ConfigurationUtility
     */
    public function overrideMapDefaults(array $options): self
    {
        $this->mapDefaults = array_replace_recursive($this->mapDefaults, $options);

        return $this;
    }

    /**
     * Get current map defaults
     *
     * @return array
     */
    public function getMapDefaults(): array
    {
        return $this->mapDefaults;
    }

    /**
     * Stage a map for post processing
     *
     * @param Map $map
     *
     * @return ConfigurationUtility
     */
    public function addMap(Map $map): self
    {
        $defaults = $this->mapDefaults;
        if ($map->hasMarkers() && isset($defaults['center'])) {
            unset($defaults['center']);
        }

        $options = $map->getOptions();
        if (is_array($options['styles'])) {
            $defaults['styles'] = $options['styles'];
            unset($options['styles']);
        }

        $map->setOptions(array_replace_recursive($defaults, $options));
        $this->maps->attach($map);

        return $this;
    }

    /**
     * Remove a map from staged maps
     *
     * @param Map $map
     *
     * @return ConfigurationUtility
     */
    public function removeMap(Map $map): self
    {
        $this->maps->detach($map);

        return $this;
    }

    /**
     * Get all maps currently staged for post processing
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\M2S\PoiMap\Transient\Map>
     */
    public function getMaps(): ObjectStorage
    {
        return $this->maps;
    }

    /**
     * Will be called by page renderer before it processes assets
     *
     * @param array $assets
     */
    public function preRenderJsHook(array $assets)
    {
        if (TYPO3_MODE === 'FE' && count($this->maps)) {
            $assets['jsLibs'][] = [
                'file' => 'EXT:poi_map/Resources/Public/JavaScript/PoiMapInit.min.js',
                'section' => PageRenderer::PART_FOOTER,
                'type' => 'text/javascript',
                'async' => true
            ];

            $signature = '';
            $code = "var c=[];";
            /** @var Map $map */
            foreach ($this->maps as $map) {
                $signature .= $map->getId().';';
                $code .= "c.push(function(){{$map->getJavaScript()}return {$map->getId()};});";
            }

            $code .= 'var i=function() {';
            $code .=    'w.M2S.instances=(w.M2S.instances||[]);';
            $code .=    'w.M2S.instances.push(new w.M2S.PoiMap({';
            $code .=        "apiKey:'{$this->maps_apiKey}',";
            /** @todo: uncomment on next major version */
            //$code .=      "excludeCss:{$this->maps_excludeCss},";
            if ($this->maps_useTyposcriptLanguage) {
                $code .=    "language:'{$this->getFrontendController()->sys_language_isocode}',";
            }
            $code .=        'callbacks:c';
            $code .=    '}));';
            $code .=    "d.dispatchEvent(new Event('m2s:poi-map-initialized'));";
            $code .= '};';

            $code .= "(w.M2S && w.M2S.PoiMap && i())||d.addEventListener('m2s:poi-map-loaded',i);";
            $code = "'use strict';(function(w,d) {{$code}})(window,document);";

            $assets['jsInline'][$signature] = [
                'code' => GeneralUtility::minifyJavaScript($code),
                'section' => PageRenderer::PART_FOOTER
            ];
        }
    }

    /**
     * Magic getter method for extracting specific arguments from extension configuration
     *
     * @param string $name
     *
     * @return string|array|mixed
     * @throws \Error
     */
    public function __get(string $name)
    {
        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        $parts = explode('_', $name);
        $last = GeneralUtility::camelCaseToLowerCaseUnderscored(array_pop($parts));

        $configuration = $this->configuration;
        foreach ($parts as $part) {
            $part = GeneralUtility::camelCaseToLowerCaseUnderscored($part).'.';
            if (isset($configuration[$part]) && is_array($configuration[$part])) {
                $configuration = $configuration[$part];
            }
        }
        if (isset($configuration[$last])) {
            $this->cache[$name] = $configuration[$last];
        } elseif (isset($configuration[$last.'.'])) {
            $this->cache[$name] = $configuration[$last.'.'];
        }

        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        if (isset($this->configuration['strict']) && $this->configuration['strict']) {
            throw new \Error('Undefined property ' . ConfigurationUtility::class . '::' . $name . '()', 1538254877);
        }

        return null;
    }

    /**
     * Decide if TYPO3 8.7 is used or newer
     *
     * @return bool
     */
    public static function isTypo3OlderThen9(): bool
    {
        return VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) < 9000000;
    }

    /**
     * Wrapper for the $GLOBALS['TSFE'] variable
     *
     * @return TypoScriptFrontendController
     */
    private function getFrontendController()
    {
        return $GLOBALS['TSFE'];
    }
}
