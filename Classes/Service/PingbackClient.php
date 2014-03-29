<?php
namespace PHTH\Pongback\Service;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RequestPingback
 *
 * @author Michael
           */
   
class PingbackClient {
   
    /**
     *
     * @var string $targetLink
     */
    protected $targetLink;
    
    public function getTargetLink() {
        return $this->targetLink;
    }

    public function setTargetLink($targetLink) {
        $this->targetLink = $targetLink;
    }
    


 
    public function mailPingbackArrived(&$params){
      
        $sourceLink = $params['params'][1];

        /**
         * An Hook from PingbackController
         * 
         * require  [defaultMailFromAddress] and [defaultMailFromName]
         */
        $pongbackConf = (array) unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['pongback']);
        if(\TYPO3\CMS\Core\Utility\GeneralUtility::validEmail($pongbackConf['notificationAddress'])){
            $mailer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Mail\\MailMessage');

            $subject = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_pongback_domain_model_pingback.pingback_arrived_alert_mail_subject", 'pongback');
            $body = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate("tx_pongback_domain_model_pingback.pingback_arrived_alert_mail", 'pongback') . " $sourceLink ";

            $from = \TYPO3\CMS\Core\Utility\MailUtility::getSystemFrom();

            $mailer->setFrom($from);
            $mailer->setTo(array($pongbackConf['notificationAddress'] => 'mail'))
                    ->setSubject($subject)
                    ->setBody($body);


            $mailer->send();
        }
    }
          
      /**
  * 
  * @param type $targetUri
  * @param type $sourceUri 
  */
        
    public function send($targetUri,$sourceUri){
        
        $this->autoDiscovery($targetUri);
        
        $client = new \fXmlRpc\Client($this->getTargetLink()); 
        $response = $client->call("pingback.ping" ,array($sourceUri,$targetUri)); 
      
    
    }  
      /**
     *   Pingback send Autodiscovery 
     *  @return string URL + xmlrpc.php 
     */
     public function autoDiscovery($targetLink){
            $searchPattern = '/X-Pingback/'; 
            $proofLink = $this->sendRequest($targetLink);
            
            preg_match($searchPattern ,substr($proofLink,3),$success, PREG_OFFSET_CAPTURE,3); 
            if($success === "Pingback" | "pingback"){
                preg_match_all("/( (http|https):\/\/[^\s]*)/",$proofLink,$output); 
                $this->setTargetLink($output); 
                
                        
                }  elseif($this->htmlHeader($targetLink)) {
                   
                    $this->setTargetLink($this->htmlHeader($targetLink));  
                    
                    
                    
                }else{
                    $o_flashMessage = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
                             't3lib_FlashMessage', ' Kein Pingback vorhanden ', \TYPO3\CMS\Core\Messaging\FlashMessage::OK
                            );
                            \TYPO3\CMS\Core\Messaging\FlashMessageQueue::addMessage($o_flashMessage);
                }            
}

         /**
         * 
         * @param type $sourceLink
         * @param type $targetLink
         */
        
         public function sendRequest($targetLink) {
           
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $targetLink); 
            curl_setopt($ch, CURLOPT_HEADERFUNCTION, "callback");
            $page = curl_exec($ch);    
        }
        
        
        /**
         * 
         * @param type $website
         * @return string
          */
            
          public function htmlHeader($website) {
         
            $response= file_get_contents($website, TRUE, NULL, 0, 5000); 
            preg_match_all("/(link[^>].*pingback.*href=\")(.*)(\".*>)/iU",$response,$treffer); 
                   
            if(!isset($treffer[1][0])){
                $treffer[1][0] =""; 
            }else { 
                
                return $treffer[2][0]; 
            }
        }
        
        
}
?>