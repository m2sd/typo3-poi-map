<?php
/**
 * Created by PhpStorm.
 * User: main
 * Date: 9/29/18
 * Time: 9:56 PM
 */

namespace M2S\PoiMap\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use \TYPO3\CMS\Extbase\Domain\Repository\CategoryRepository as BaseRepository;

class CategoryRepository extends BaseRepository
{
    /**
     * Gets actual hydrated entities for a uid array
     *
     * @param array $uids
     * @param bool  $includeChildren
     *
     * @return ObjectStorage
     */
    public function findByUidArray(array $uids, bool $includeChildren = false): ObjectStorage
    {
        if ($includeChildren) {
            $uids = $this->extendUidArray($uids);
        }

        $result = new ObjectStorage();
        foreach ($uids as $uid) {
            if ($category = $this->findByUid($uid)) {
                $result->attach($category);
            }
        }

        return $result;
    }

    /**
     * Extends a uid array to include all of the respective children
     *
     * @param array $uids
     *
     * @return array
     */
    public function extendUidArray(array $uids): array
    {
        $uidList = implode(',', $uids);
        $queryStatement = 'SELECT uid,pid FROM sys_category WHERE parent IN (?) AND sys_language_uid IN (0,-1);';

        $query = $this->createQuery();
        $query->getQuerySettings()->setLanguageUid(0);
        while ($records = $query->statement($queryStatement, [$uidList])->execute(true)) {
            $uidList = array_map(function ($row) {
                return $row['uid'];
            }, $records);
            $uids = array_merge($uids, $uidList);
            $uidList = implode(',', $uidList);
        }

        return $uids;
    }
}
