<?php
declare(strict_types = 1);

/**
 * Created by PhpStorm.
 * User: main
 * Date: 10/1/18
 * Time: 2:48 AM
 */

namespace M2S\PoiMap\GoogleMaps;

interface ElementInterface
{
    /**
     * Returns a unique identifier for the element instance
     * Is used in generated JS to identify instances
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Checks for the existence of a specific option
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasOption(string $name): bool;

    /**
     * Returns the full configuration
     *
     * @return array
     */
    public function getOptions(): array;

    /**
     * Set a specific option for the element
     *
     * @param string $name
     * @param mixed  $value
     */
    public function setOption(string $name, $value): void;

    /**
     * Set the full configuration
     *
     * @param array $options
     */
    public function setOptions(array $options): void;

    /**
     * Override default options with provided configuration
     *
     * @param array $options
     */
    public function overrideOptions(array $options): void;

    /**
     * Get javascript to instantiate the element
     *
     * @return string
     */
    public function getJavascript(): string;
}
