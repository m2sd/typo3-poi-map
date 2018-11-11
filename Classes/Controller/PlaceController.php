<?php

namespace M2S\PoiMap\Controller;

use M2S\PoiMap\Domain\Repository\CategoryRepository;
use M2S\PoiMap\Domain\Repository\PlaceRepository;
use TYPO3\CMS\Core\Database\QueryGenerator;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

class PlaceController extends ActionController
{
    /**
     * @var PlaceRepository
     */
    private $placeRepository;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * Inject place repository
     *
     * @param PlaceRepository $placeRepository
     */
    public function injectPlaceRepository(PlaceRepository $placeRepository)
    {
        $this->placeRepository = $placeRepository;
    }

    /**
     * Inject category repository
     *
     * @param CategoryRepository $categoryRepository
     */
    public function injectCategoryRepository(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Controller action for template Place/List
     *
     * @throws InvalidQueryException
     */
    public function listAction()
    {
        $places = $this->getFilteredPlaces();

        $this->view->assignMultiple([
            'places' => $places,
            'mapStyles' => $this->getMapStyles(),
            'mapType' => ($this->settings['appearance']['type'] ?: $this->settings['default_type'])
        ]);
    }

    /**
     * Controller action for template Place/Map
     *
     * @throws InvalidQueryException
     */
    public function mapAction()
    {
        $places = $this->getFilteredPlaces();

        $this->view->assignMultiple([
            'places' => $places,
            'mapStyles' => $this->getMapStyles(),
            'mapType' => ($this->settings['appearance']['type'] ?: $this->settings['default_type']),
            'showInfo' => $this->settings['appearance']['showInfo'],
            'showInfoSingle' => $this->settings['appearance']['showInfoSingle'],
            'infoOptions' => $this->getInfoOptions() ?: [],
            'center' => $this->settings['appearance']['center'],
            'zoom' => $this->settings['appearance']['zoom']
        ]);
    }

    /**
     * Gets the default map styles from plugin settings or constants
     *
     * @return null|array
     */
    protected function getMapStyles(): ?array
    {
        if (!$style = $this->settings['appearance']['style']) {
            $style = $this->settings['default_style'];
        }
        return json_decode($style, true);
    }

    /**
     * Gets the info window options from plugin settings or constants
     *
     * @return null|array
     */
    protected function getInfoOptions(): ?array
    {
        $style = json_decode($this->settings['default_info_options'], true) ?: [];
        ArrayUtility::mergeRecursiveWithOverrule(
            $style,
            json_decode($this->settings['appearance']['infoOptions'], true) ?: []
        );

        return $style;
    }

    /**
     * Get places filtered according to the plugin settings
     *
     * @return \Traversable|QueryResultInterface|ObjectStorage
     *
     * @throws InvalidQueryException
     */
    protected function getFilteredPlaces()
    {
        $cids = [];
        if ($this->settings['filters']['categories']) {
            $cids = GeneralUtility::intExplode(',', $this->settings['filters']['categories'], true);

            if ($this->settings['filters']['subcategories']) {
                $cids = $this->categoryRepository->extendUidArray($cids);
            }
        }

        $pids = [];
        if ($this->settings['filters']['pages']) {
            $pids = GeneralUtility::intExplode(',', $this->settings['filters']['pages'], true);

            if ($this->settings['filters']['subpages']) {
                $roots = $pids;
                $generator = $this->objectManager->get(QueryGenerator::class);
                foreach ($roots as $pid) {
                    $subUidList = $generator->getTreeList($pid, 999999, 1, '1=1');

                    $pids = array_merge($pids, GeneralUtility::intExplode(',', $subUidList, true));
                }

                $pids = array_unique($pids);
            }
        }

        return $this->placeRepository->filterByCidsAndPids($cids, $pids);
    }
}
