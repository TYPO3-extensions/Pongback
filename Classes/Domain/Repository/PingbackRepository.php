<?php
namespace PHTH\Pongback\Domain\Repository;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Michael Blunck <michael.blunck@phth.de>, PHTH
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 *
 * @package pongback
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class PingbackRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

    public function initializeObject() {
        /** @var $querySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
        $querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
        // go for $defaultQuerySettings = $this->createQuery()->getQuerySettings(); if you want to make use of the TS persistence.storagePid with defaultQuerySettings(), see #51529 for details
        // don't add the pid constraint
        $querySettings->setRespectStoragePage(FALSE);
        // set the storagePids to respect
        //$querySettings->setStoragePageIds(array(1, 26, 989));
        // don't add fields from enablecolumns constraint
        // this function is deprecated!
        $querySettings->setRespectEnableFields(FALSE);

        // define the enablecolumn fields to be ignored
        // if nothing else is given, all enableFields are ignored
        //$querySettings->setIgnoreEnableFields(TRUE);       
        // define single fields to be ignored
        //$querySettings->setEnableFieldsToBeIgnored(array('disabled','starttime'));
        // add deleted rows to the result
        //$querySettings->setIncludeDeleted(TRUE);
        // don't add sys_language_uid constraint
        //$querySettings->setRespectSysLanguage(FALSE);
        // perform translation to dedicated language
        //$querySettings->setSysLanguageUid(42);
        $this->setDefaultQuerySettings($querySettings);
        
        
    }
    public function findVisible() {
        
        $query = $this->createQuery();
        
        // SELECT * FROM tx_ _ _ pingback WHERE ( deleted = 0 AND hidden = 0 ) ORDER BY crdate DESC;
        $query = $query->matching(
            $query->logicalAnd(
                $query->equals('deleted', '0'),
                $query->equals('hidden', '0')
            )
        )
        ->setOrderings(
            array('crdate' =>\TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING)
        );

        return $query->execute();
        
    }

}
?>