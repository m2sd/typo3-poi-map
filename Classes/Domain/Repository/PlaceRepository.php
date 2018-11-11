<?php
/**
 * Created by PhpStorm.
 * User: main
 * Date: 9/28/18
 * Time: 10:09 AM
 */

namespace M2S\PoiMap\Domain\Repository;

use M2S\PoiMap\Domain\Model\Category;
use M2S\PoiMap\Domain\Model\Place;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Frontend\Category\Collection\CategoryCollection;

class PlaceRepository extends Repository
{
    /**
     * @param array $cids
     *
     * @return ObjectStorage
     */
    public function filterByCids(array $cids): ObjectStorage
    {
        $result = new ObjectStorage();

        if ($records = $this->getCategorizedRecordsByCids($cids)) {
            $records = $this->objectManager->get(DataMapper::class)->map(Place::class, $records);
            foreach ($records as $record) {
                $result->attach($record);
            }
        }

        return $result;
    }

    /**
     * @todo: make consistent with typoscript overlay behaviour (will be fixed in 9-lts, in the meantime find a way to circumvent)
     *
     * @param array $pids
     *
     * @return QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function filterByPids(array $pids): QueryResultInterface
    {
        $query = $this->createQuery();

        return $query->matching(
            $query->in('pid', $pids)
        )->execute();
    }

    /**
     * @todo: use extbase query to retrieve entities when dropping typo3 v8-lts support, at the moment we use array operations to keep consistency with typo3 language handling (will be fixed in version 9-lts)
     *
     * @param array $cids
     * @param array $pids
     *
     * @return \Traversable
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function filterByCidsAndPids(array $cids, array $pids): \Traversable
    {
        if (!count($cids) && !count($pids)) {
            return $this->findAll();
        } elseif (!count($cids)) {
            return $this->filterByPids($pids);
        } elseif (!count($pids)) {
            return $this->filterByCids($cids);
        }

        if (count($records = $this->getCategorizedRecordsByCids($cids))) {
            $objects = $this->objectManager->get(DataMapper::class)->map(
                Place::class,
                array_filter($records, function ($record) use ($pids) {
                    return in_array($record['pid'], $pids);
                })
            );

            $result = new ObjectStorage();
            foreach ($objects as $object) {
                $result->attach($object);
            }

            return $result;
        };

        return new ObjectStorage();
    }

    /**
     * @param array $cids
     *
     * @return array
     */
    protected function getCategorizedRecordsByCids(array $cids): array
    {
        $result = [];

        foreach ($cids as $cid) {
            $records = CategoryCollection::load(
                $cid,
                true,
                'tx_poimap_domain_model_place',
                'categories'
            );

            foreach ($records as $record) {
                if (!isset($result[$record['uid']])) {
                    $result[$record['uid']] = $record;
                }
            }
        }

        return $result;
    }
}
