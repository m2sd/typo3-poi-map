<?php
/**
 * Created by PhpStorm.
 * User: main
 * Date: 9/28/18
 * Time: 9:57 AM
 */

namespace M2S\PoiMap\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Place extends AbstractEntity
{
    /**
     * @var string
     */
    protected $name = '';
    /**
     * @var string
     */
    protected $additionalType = '';
    /**
     * @var string
     */
    protected $alternateName = '';
    /**
     * @var string
     */
    protected $description = '';
    /**
     * @var string
     */
    protected $disambiguatingDescription = '';
    /**
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    protected $image = null;
    /**
     * @var string
     */
    protected $url = '';
    /**
     * @var string
     */
    protected $geoCoordinates = '';
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\M2S\PoiMap\Domain\Model\Category>
     */
    protected $categories = null;

    /**
     * Object storage initialization
     */
    protected function initStorageObjects()
    {
        $this->categories = new ObjectStorage();
    }

    /**
     * Place constructor.
     */
    public function __construct()
    {
        $this->initStorageObjects();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getAdditionalType(): string
    {
        return $this->additionalType;
    }

    /**
     * @param string $additionalType
     *
     * @return self
     */
    public function setAdditionalType(string $additionalType): self
    {
        $this->additionalType = $additionalType;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlternateName(): string
    {
        return $this->alternateName;
    }

    /**
     * @param string $alternateName
     *
     * @return self
     */
    public function setAlternateName(string $alternateName): self
    {
        $this->alternateName = $alternateName;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDisambiguatingDescription(): string
    {
        return $this->disambiguatingDescription;
    }

    /**
     * @param string $disambiguatingDescription
     *
     * @return self
     */
    public function setDisambiguatingDescription(string $disambiguatingDescription): self
    {
        $this->disambiguatingDescription = $disambiguatingDescription;

        return $this;
    }

    /**
     * @return null|\TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    public function getImage(): ?FileReference
    {
        return $this->image;
    }

    /**
     * @param null|\TYPO3\CMS\Extbase\Domain\Model\FileReference $image
     *
     * @return self
     */
    public function setImage(?FileReference $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return self
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getGeoCoordinates(): string
    {
        return $this->geoCoordinates;
    }

    /**
     * @param string $geoCoordinates
     *
     * @return self
     */
    public function setGeoCoordinates(string $geoCoordinates): self
    {
        $this->geoCoordinates = $geoCoordinates;

        return $this;
    }

    /**
     * Get an array representation of latitude and longitude
     *
     * @return array
     */
    public function getLatLngArray(): array
    {
        if (!$this->geoCoordinates) {
            return [];
        }

        $coordinates = explode(',', $this->geoCoordinates);

        return [
            'lat' => (float)$coordinates[0],
            'lng' => (float)$coordinates[1]
        ];
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\M2S\PoiMap\Domain\Model\Category>
     */
    public function getCategories(): ObjectStorage
    {
        return $this->categories;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\M2S\PoiMap\Domain\Model\Category> $categories
     *
     * @return self
     */
    public function setCategories(ObjectStorage $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @param Category $category
     *
     * @return Place
     */
    public function addCategory(Category $category): self
    {
        $this->categories->attach($category);

        return $this;
    }

    /**
     * @param Category $category
     *
     * @return Place
     */
    public function removeCategory(Category $category): self
    {
        $this->categories->detach($category);

        return $this;
    }
}
