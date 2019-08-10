<?php
declare(strict_types = 1);

/**
 * Created by PhpStorm.
 * User: main
 * Date: 9/30/18
 * Time: 3:33 PM
 */
namespace M2S\PoiMap\GoogleMaps;

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Map extends AbstractElement
{
    /**
     * Markers to be displayed on the map
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\M2S\PoiMap\GoogleMaps\Marker>
     */
    protected $markers;
    /**
     * Determines if info windows are attached to markers
     *
     * @var bool
     */
    protected $infoWindows = false;
    /**
     * Determines whether all other info windows should be closed if one is opened
     *
     * @var string
     */
    protected $infoWindowsSingle = 'false';
    /**
     * Additional configuration options for SnazzyInfoWindow instances
     *
     * @var array
     */
    protected $infoWindowsOptions = [];

    /**
     * Map constructor.
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage|array $markers
     */
    public function __construct($markers = [])
    {
        $this->markers = new ObjectStorage();
        if (is_array($markers)) {
            foreach ($markers as $marker) {
                $this->markers->attach($marker);
            }
        } elseif ($markers instanceof ObjectStorage) {
            $this->markers->addAll($markers);
        } else {
            throw new \InvalidArgumentException('Map instantiation expects either an array or an object of class ' . ObjectStorage::class . ' as first argument, got ' . (is_object($markers) ? ' an object of class ' . get_class($markers) : gettype($markers)), 1538354658);
        }

        parent::__construct();
    }

    /**
     * Check if the map contains places
     *
     * @return bool
     */
    public function hasMarkers(): bool
    {
        return $this->markers->count() > 0;
    }

    /**
     * Add a marker to the map
     *
     * @param Marker $marker
     *
     * @return Map
     */
    public function addMarker(Marker $marker): self
    {
        if (!$this->markers->contains($marker)) {
            $this->markers->attach($marker);
        }

        return $this;
    }

    /**
     * Removes a marker from the map
     *
     * @param Marker $marker
     *
     * @return Map
     */
    public function removeMarker(Marker $marker): self
    {
        if ($this->markers->contains($marker)) {
            $this->markers->detach($marker);
        }

        return $this;
    }

    /**
     * Enables/Disables infoWindows for this map
     *
     * @param bool $enable
     * @param bool $single
     *
     * @return Map
     */
    public function enableInfoWindows(bool $enable = true, bool $single = false, array $options = []): self
    {
        $this->infoWindows = $enable;
        $this->infoWindowsSingle = $single ? 'true' : 'false';
        $this->infoWindowsOptions = $options;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getJavaScript(): string
    {
        $markerPositions = [];
        $postJs = [
            "{$this->id}.__mrk = [];",
        ];
        /** @var Marker $marker */
        foreach ($this->markers as $marker) {
            $coordinates = $marker->getPosition();
            if ($coordinates) {
                $id = $marker->getId();
                $markerPositions[] = $coordinates;

                $markerJs = "var $id={$marker->getJavascript()}";
                $markerJs .= "$id.setMap({$this->id});";

                // phpcs:disable SlevomatCodingStandard.ControlStructures.AssignmentInCondition.AssignmentInCondition
                if ($this->infoWindows && $info = $marker->getInfoWindow()) {
                // phpcs:enable SlevomatCodingStandard.ControlStructures.AssignmentInCondition.AssignmentInCondition
                    $windowOptions = count($this->infoWindowsOptions) ? json_encode($this->infoWindowsOptions) : '{}';
                    $markerJs .= "$id.__iw=new SnazzyInfoWindow(Object.assign($windowOptions, {";
                    $markerJs .=    "marker:$id,";
                    $markerJs .=    "content:'$info',";
                    $markerJs .=    "closeWhenOthersOpen:{$this->infoWindowsSingle}";
                    $markerJs .= '}));';
                }
                $markerJs .= "{$this->id}.__mrk.push($id);";

                $postJs[] = $markerJs;
            }
        }

        if ($this->hasMarkers() && !$this->hasOption('center')) {
            $center = [];
            foreach ($markerPositions as $position) {
                if (!isset($center['lat'])) {
                    $center['lat'] = $position['lat'];
                } else {
                    $max = max($center['lat'], $position['lat']);
                    $min = min($center['lat'], $position['lat']);
                    $center['lat'] = $min + (($max - $min) / 2);
                }
                if (!isset($center['lng'])) {
                    $center['lng'] = $position['lng'];
                } else {
                    $max = max($center['lng'], $position['lng']);
                    $min = min($center['lng'], $position['lng']);
                    $center['lng'] = $min + (($max - $min) / 2);
                }
            }

            $this->options['center'] = $center;
        }
        $options = count($this->options) ? json_encode($this->options) : '{}';

        return "var {$this->id}=new google.maps.Map(document.querySelector('#{$this->id}'),$options);" . implode('', $postJs);
    }
}
