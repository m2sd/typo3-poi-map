<?php
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
     * Determines whether all other info windows should be closed if one is opened
     *
     * @var bool
     */
    protected $singleWindow = false;
    /**
     * Markers to be displayed on the map
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\M2S\PoiMap\Domain\Model\Place>
     */
    protected $markers;
    /**
     * Determines if info windows are attached to markers
     *
     * @var bool
     */
    protected $infoWindows = false;

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
    public function enableInfoWindows(bool $enable = true, bool $single = false): self
    {
        $this->infoWindows = $enable;
        $this->singleWindow = $single;

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
            if ($coordinates = $marker->getPosition()) {
                $id = $marker->getId();
                $markerPositions[] = $coordinates;

                $markerJs = "var $id={$marker->getJavascript()}";
                $markerJs .= "$id.setMap({$this->id});";

                if ($this->infoWindows && $info = $marker->getInfoWindow()) {
                    $markerJs .= "$id.addListener('click',function(){";
                    $markerJs .= "if(!this.__iw){this.__iw = new google.maps.InfoWindow({content: '$info'});}";
                    $markerJs .= 'if(!this.__iw.getMap()){this.__iw.open(this.getMap(), this);}else{this.__iw.close();}';
                    if ($this->singleWindow) {
                        $markerJs .= 'if(this.__iw.getMap()){var s=this;this.__iw.getMap().__mrk.forEach(function(m){if(m !== s && m.__iw){m.__iw.close();}});}';
                    }
                    $markerJs .= '});';
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
