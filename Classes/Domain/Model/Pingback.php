<?php
namespace PHTH\Pongback\Domain\Model;

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
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, 
 * version 3 or later
 *
 */

class Pingback extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

    /**
     * sourceLink
     *
     * @var \string
     * @validate NotEmpty
     */
    protected $sourceLink;

    /**
     * pingClient
     *
     * @var \string
     */
    protected $pingClient;

    /**
     * pingRessource
     *
     * @var \string
     */
    protected $pingRessource;

    /**
     * targetLink
     *
     * @var \string
     * @validate NotEmpty
     */
    protected $targetLink;

    /**
     * serializedInformation
     *
     * @var \string
     * @validate NotEmpty
     */
    protected $serializedInformation;

    /**
     * validation error
     *
     * @var \array
     * 
     */
    protected $validationError;

    /**
     * Returns the sourceLink
     *
     * @return \string $sourceLink
     */
    public function getSourceLink() {
        
        return $this->sourceLink;
    }

    /**
     * Sets the sourceLink
     *
     * @param \string $sourceLink
     * @return void
     */
    public function setSourceLink($sourceLink) {
       
        $this->sourceLink = $sourceLink;
    }

    /**
     * @var boolean
     */
    protected $hidden;

    /**
     * @return boolean $hidden
     */
    public function getHidden() {
        return $this->hidden;
    }

    /**
     * @return boolean $hidden
     */
    public function isHidden() {
        return $this->getHidden();
    }

    /**
     * @param boolean $hidden
     * @return void
     */
    public function setHidden($hidden) {
        $this->hidden = $hidden;
    }

       /**
     * Returns the pingClient
     *
     * @return \string $pingClient
     */
    public function getPingClient() {
        return $this->pingClient;
    }

    /**
     * Sets the pingClient
     *
     * @param \string $pingClient
     * @return void
     */
    public function setPingClient($pingClient) {
        $this->pingClient = $pingClient;
    }

    /**
     * Returns the pingRessource
     *
     * @return \string $pingRessource
     */
    public function getPingRessource() {
        return $this->pingRessource;
    }

    /**
     * Sets the pingRessource
     *
     * @param \string $pingRessource
     * @return void
     */
    public function setPingRessource($pingRessource) {
        $this->pingRessource = $pingRessource;
    }

    /**
     * Adds the validationError
     *
     * @return \string $validationError
     */
    public function addValidationError($validationError) {
        $this->validationErrors[] = $validationError;
    }

    /**
     * Sets the validationErrors
     *
     * @return \array $validationErrors
     */
    public function setValidationErrors($validationErrors) {
        $this->validationErrors = $validationErrors;
    }

    /**
     * Returns the validationErrors
     *
     * @return \array $validationErrors
     */
    public function getValidationErrors() {
        return $this->validationErrors;
    }

    /**
     * Returns the targetLink
     *
     * @return \string $targetLink
     */
    public function getTargetLink() {
        return $this->targetLink;
    }

    /**
     * Sets the targetLink
     *
     * @param \string $targetLink
     * @return void
     */
    public function setTargetLink($targetLink) {
        $this->targetLink = $targetLink;
    }

    /**
     * Returns the serializedInformation
     *
     * @return \string $serializedInformation
     */
    public function getSerializedInformation() {

        return $this->serializedInformation;
    }

    /**
     * Sets the serializedInformation
     *
     * @param \string $serializedInformation
     * @return void
     */
    public function setSerializedInformation($serializedInformation) {

        $this->serializedInformation = $serializedInformation;
                
            
    }


    /**
     * information changed in object
     * @param type $param
     */
    public function seralizeInformation($param) {

        $this->setSerializedInformation(serialize($param));
    }
    
}
            
?>