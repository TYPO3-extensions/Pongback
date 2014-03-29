<?php

namespace PHTH\Pongback\Hook;

class Tcemain {

    public function processDatamap_postProcessFieldArray($status, $table, $id, $fieldArray, $ref) {

        // @todo: make tables configurable
        /**
         * To search content after saving about Hyperlinks 
         */
        if ($table == 'tt_content' && is_array($fieldArray)) {
            $links = array();

            foreach ($fieldArray as $field) {
                preg_match_all("/((http|https):\/\/[^\s]*)/", $field, $matches);
                foreach ($matches[0] as $match) {

                    $links[$match] = $match;
                }
            }

            if (count($links) > 0) {

                /**
                 * @todo respect the enablefields!
                 * to provide to set the enablefields  
                 * Takes the full tt_content-row when is not hidden
                 * we dont need to send pingbacks for hidden elements 
                 * 
                 */
                $row = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord($table, $id, '*', ' AND hidden = 0 AND (fe_group = \'\' OR fe_group = 0) ');

                if (is_array($row)) {

                    // @todo respect the enablefields!
                    /**
                     * take the page that is not hidden and get the unique pid to set the URL 
                     */
                    $page = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord('pages', $row['pid'], '*', ' AND hidden = 0 AND (fe_group = \'\' OR fe_group = 0) ');

                    if (is_array($page)) {

                         
                        $permalinkParameters = array();
                        /**
                         * We need to instanciate the TSFE(TypoScript Frontend) to build a typolink via a content object($cObj)
                         * 
                         */
                        $this->buildTSFE($ref);
                        $cObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('tslib_cObj');
                        $cObj->start(array(), '');

                        $pingbackClient = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('PHTH\Pongback\Service\PingbackClient');
                      
                        /**
                         * 
                         */
                        foreach ($links as $link) {
                            $typolinkConf = array(
                                'additionalParams' => $permalinkParameters,
                                'parameter' => $row['pid'],
                                'useCacheHash' => true,
                                'returnLast' => 'url',
                                'forceAbsoluteUrl' => true
                            );

                            $permaLink = $cObj->typoLink('', $typolinkConf);


                            try {
                                $response = $pingbackClient->send($link, $permaLink);
                                $o_flashMessage = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
                                        '\TYPO3\CMS\Core\Messaging\FlashMessage', 
                                        \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tcemain.pingback.ping.accepted", 'pongback', array($link)),
                                        \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tcemain.pingback.ping.accepted_title", 'pongback'), 
                                        \TYPO3\CMS\Core\Messaging\FlashMessage::OK 
                                );
                                \TYPO3\CMS\Core\Messaging\FlashMessageQueue::addMessage($o_flashMessage);
                            } catch (\fXmlRpc\Exception\ResponseException $ex) {
                                $o_flashMessage = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
                                        '\TYPO3\CMS\Core\Messaging\FlashMessage', 
                                        \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tcemain.pingback.ping.refused", 'pongback', array($link,$ex->getFaultString())),
                                        \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tcemain.pingback.ping.refused_title", 'pongback'), 
                                        \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING 
                                );
                                \TYPO3\CMS\Core\Messaging\FlashMessageQueue::addMessage($o_flashMessage);
                            }
                        }
                    } else { 
                        $o_flashMessage = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
                                '\TYPO3\CMS\Core\Messaging\FlashMessage', 
                                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tcemain.pingback.ping.not_sent", 'pongback'), 
                                \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tcemain.pingback.ping.not_sent_title", 'pongback'),
                                \TYPO3\CMS\Core\Messaging\FlashMessage::NOTICE
                        );
                        \TYPO3\CMS\Core\Messaging\FlashMessageQueue::addMessage($o_flashMessage);
                    }
                }
            }
        }
    }
/** 
 * 
 * @param type $ref
 */
    
    public function buildTSFE($ref) {
        $TSFEclassName = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('tslib_fe');

        if (!is_object($GLOBALS['TT'])) {
            $GLOBALS['TT'] = new \TYPO3\CMS\Core\TimeTracker\TimeTracker;
            $GLOBALS['TT']->start();
        }

        // Create the TSFE class.
        $GLOBALS['TSFE'] = new $TSFEclassName($GLOBALS['TYPO3_CONF_VARS'], $ref->pid, '0', 1, '', '', '', '');
        $GLOBALS['TSFE']->initFEuser();
        $GLOBALS['TSFE']->fetch_the_id();
        $GLOBALS['TSFE']->getPageAndRootline();
        $GLOBALS['TSFE']->initTemplate();
        $GLOBALS['TSFE']->tmpl->getFileName_backPath = PATH_site;
        $GLOBALS['TSFE']->forceTemplateParsing = 1;
        $GLOBALS['TSFE']->getConfigArray();
    } 
    
}

?>
