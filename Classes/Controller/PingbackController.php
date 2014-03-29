<?php

namespace PHTH\Pongback\Controller;

/* * *************************************************************
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
 * ************************************************************* */

/**
 *
 *
 * @package pongback
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class PingbackController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

    
    /**
     * pingbackRepository
     *
     * @var \PHTH\Pongback\Domain\Repository\PingbackRepository
     * @inject
     */
    protected $pingbackRepository;

    /* Deaktiviert FlashMessage 
    * @see Tx_Extbase_MVC_Controller_ActionController::getErrorFlashMessage()
    */
    protected function getErrorFlashMessage() {
        return FALSE;
    }

    /**
     * action list
     *
     * @return void
     */
    public function administerAction() {
       
        $pingbacks = $this->pingbackRepository->findByDeleted('0');
        
        $this->view->assign('pingbacks', $pingbacks);
        
        
    }
      /**
     * action list
     *
     * @return void
     */
    public function listAction() {
       
        $pingbacks = $this->pingbackRepository->findVisible();
                
        $this->view->assign('pingbacks', $pingbacks);
        
        
    }
    
    

    /**
     * action show
     *
     * @param \PHTH\Pongback\Domain\Model\Pingback $pingback
     * @return void
     */
    public function showAction(\PHTH\Pongback\Domain\Model\Pingback $pingback) {
          
        $this->view->assign('pingback', $pingback);
       
         $this->getErrorFlashMessage(); 
       
    }
      /**
     * action publish
     *
     * @param string $pingback
     * @return void
     */
    public function unpublishAction($pingback) {
       $pingback = $this->pingbackRepository->findByUid((int)$pingback);
        $pingback->setHidden(1); 
        $this->pingbackRepository->update($pingback); 
       $this->redirect('administer'); 
    } 
    
         /**
     * action unpublish
     *
     * @param string $pingback
     * @return void
     */
    public function publishAction($pingback) {
        $pingback = $this->pingbackRepository->findByUid((int)$pingback);
        $pingback->setHidden(0); 
        $this->pingbackRepository->update($pingback); 
        $this->redirect('administer'); 
    } 
    
    
    /**
     * action delete
     *
     * @param string $pingback
     * @return void
     */
    public function deleteAction($pingback) {
        $pingback = $this->pingbackRepository->findByUid((int)$pingback);
        $this->pingbackRepository->remove($pingback); 
        //$this->persistenceManager->persist();
        
        $this->redirect('administer'); 
    }
    /**
     * edit delete
     *
     * @param \PHTH\Pongback\Domain\Model\Pingback $pingback
     * @return void
     */
    public function editAction(\PHTH\Pongback\Domain\Model\Pingback $pingback) {
        $this->view->assign('pingback', $pingback);
    }
 
    
    /**
     * server
     *
     * @return string $response
     */
    public function serverAction() {

       
        $fp = fopen('php://input', 'rb');
        stream_filter_append($fp, 'dechunk', STREAM_FILTER_READ);
        $HTTP_RAW_POST_DATA = stream_get_contents($fp);

        $xmlrpcServerHandler = xmlrpc_server_create();
        xmlrpc_server_register_method($xmlrpcServerHandler, "pingback.ping", array($this, "pingback"));
        $response = xmlrpc_server_call_method($xmlrpcServerHandler, $HTTP_RAW_POST_DATA, null);
        return $response;
    }
    /**
     * 
     * @param string $method_name
     * @param array $xmlRpcParams
     * @param type $app_data
     */
    public function pingback($method_name, $xmlRpcParams, $app_data) {
        
        
        /**
         * We must implement some Hooks to validate some important content, the URL, the content from other page an  send an email to the owner of the blog
         */
        $pingback = $this->objectManager->get('PHTH\Pongback\Domain\Model\Pingback');
       
            if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['pongback']['validatePingback'])) {
                foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['pongback']['validatePingback'] as $userFunc) {
                    if (count($pingback->getValidationErrors()) < 1) {
                        $params = array(
                            'pingback' => $pingback,
                            'params' => $xmlRpcParams
                        );
                       
                        \TYPO3\CMS\Core\Utility\GeneralUtility::callUserFunction($userFunc, $params, $this);
                    } else {
                        break;
                    }
                }
            }
                                 
        if(count($pingback->getValidationErrors()) > 0) {
                $errors = $pingback->getValidationErrors();
                
                return $errors[0]; 
            } else {
                /**
                 * saved the incoming Pongback to the Database 
                 */
                $pongbackConf = (array)@unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['pongback']);
                if (!$pongbackConf['enableAutoPublishing']) {
                    $pingback->setHidden(1);
                }
                $this->pingbackRepository->add($pingback);
               
                return \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("xmlrpc.pingback.ping.success", 'pongback');      
            } 
            
            
    }
    
   /**
    * Generates an XML formatted Fault Code
    * 
    * @param string $errno
    * @param string $errstr
    * @param string $errfile
    * @param string $errline
    * @param string $errcontext
    */
   public function return_xmlrpc_error($errno, $errstr, $errfile=NULL, $errline=NULL, $errcontext=NULL){
       
       header("Content-type: text/xml; charset=UTF-8"); 
       print(xmlrpc_encode_request(NULL, array(
             'faultCode'=>$errno
               ,'faultString'=>"Remote XMLRPC Error from ".$_SERVER['SERVER_NAME'].": $errstr in $errfile:$errline"
       ))); 
       die(); 
   }  
  // set_error_handler('return_xmlrpc_error'); 
    
  
}

?>