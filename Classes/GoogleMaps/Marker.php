<?php
/**
 * Created by PhpStorm.
 * User: main
 * Date: 9/30/18
 * Time: 11:03 PM
 */
namespace M2S\PoiMap\GoogleMaps;

class Marker extends AbstractElement
{
    /**
     * The html for the info window
     *
     * @var string
     */
    protected $infoWindow = '';

    /**
     * Marker constructor.
     *
     * @param string|array $position
     */
    public function __construct($position)
    {
        if (is_string($position)) {
            $this->setPositionFromString($position);
        } else {
            $this->setPosition($position);
        }

        parent::__construct('poimarker_');
    }

    /**
     * @TODO: remove once proper coordinate handling is implemented
     *
     * Set the position from a coordinates string
     *
     * @param string $position
     */
    public function setPositionFromString(string $position)
    {
        $position = explode(',', $position);

        $this->setPosition($position);
    }

    /**
     * Set the position of the marker
     *
     * @param array $position
     *
     * @return Marker
     */
    public function setPosition(array $position): self
    {
        if (count($position) === 2) {
            $this->options['position'] = [
                'lat' => (float)(isset($position['lat']) ? $position['lat'] : $position[0]),
                'lng' => (float)(isset($position['lng']) ? $position['lng'] : $position[1]),
            ];

            return $this;
        }

        throw new \InvalidArgumentException('Invalid position: ' . json_encode($position), 1538353234);
    }

    /**
     * Get the position of the marker
     *
     * @return array
     */
    public function getPosition(): array
    {
        return $this->options['position'];
    }

    /**
     * Set the HTML for the info window
     * Use empty string to unset
     *
     * @param string $infoWindow
     */
    public function setInfoWindow(string $infoWindow): void
    {
        $this->infoWindow = preg_replace('/([\'])/', '\\\$1', $infoWindow);
    }

    /**
     * Get the HTML for the info window
     *
     * @return string
     */
    public function getInfoWindow(): string
    {
        return $this->infoWindow;
    }

    /**
     * Set a specific option for the marker
     * Option 'position' is ignored, use @see setPosition to change it
     *
     * @param string $name
     * @param mixed  $value
     */
    public function setOption(string $name, $value): void
    {
        if ($name !== 'position') {
            $this->options[$name] = $value;
        }
    }

    /**
     * Set the options for the marker.
     * Option 'position' will be reset to it's original value, use @see setPosition to change it
     *
     * @param array $options
     */
    public function setOptions(array $options): void
    {
        $position = $this->options['position'];
        $this->options = $options;
        $this->options['position'] = $position;
    }
}
