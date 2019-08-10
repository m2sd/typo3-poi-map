<?php
declare(strict_types = 1);

/**
 * Created by PhpStorm.
 * User: main
 * Date: 10/1/18
 * Time: 2:46 AM
 */

namespace M2S\PoiMap\GoogleMaps;

abstract class AbstractElement implements ElementInterface
{
    /**
     * A unique identifier for the instance
     *
     * @var string
     */
    protected $id;
    /**
     * The options for the element
     *
     * @var array
     */
    protected $options = [];

    public function __construct(string $idPrefix = 'poimap_')
    {
        $this->id = uniqid($idPrefix);
    }

    /**
     * @inheritdoc
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function hasOption(string $name): bool
    {
        return isset($this->options[$name]);
    }

    /**
     * @inheritdoc
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @inheritdoc
     */
    public function setOption(string $name, $value): void
    {
        $this->options[$name] = $value;
    }

    /**
     * @inheritdoc
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    /**
     * @inheritdoc
     */
    public function overrideOptions(array $options): void
    {
        $this->options = array_replace_recursive($this->options, $options);
    }

    /**
     * @inheritdoc
     */
    public function getJavascript(): string
    {
        $options = count($this->options) ? json_encode($this->options) : '{}';
        $class = str_replace(__NAMESPACE__ . '\\', '', get_called_class());

        return "new google.maps.$class($options);";
    }
}
