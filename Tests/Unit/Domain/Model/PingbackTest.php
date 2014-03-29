<?php

namespace PHTH\Pongback\Tests;
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
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Test case for class \PHTH\Pongback\Domain\Model\Pingback.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage Pongback
 *
 * @author Michael Blunck <michael.blunck@phth.de>
 */
class PingbackTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \PHTH\Pongback\Domain\Model\Pingback
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \PHTH\Pongback\Domain\Model\Pingback();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function getSourceLinkReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setSourceLinkForStringSetsSourceLink() { 
		$this->fixture->setSourceLink('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getSourceLink()
		);
	}
	
	/**
	 * @test
	 */
	public function getPingClientReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setPingClientForStringSetsPingClient() { 
		$this->fixture->setPingClient('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getPingClient()
		);
	}
	
	/**
	 * @test
	 */
	public function getPingRessourceReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setPingRessourceForStringSetsPingRessource() { 
		$this->fixture->setPingRessource('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getPingRessource()
		);
	}
	
	/**
	 * @test
	 */
	public function getTargetLinkReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setTargetLinkForStringSetsTargetLink() { 
		$this->fixture->setTargetLink('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getTargetLink()
		);
	}
	
	/**
	 * @test
	 */
	public function getSerializedInformationReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setSerializedInformationForStringSetsSerializedInformation() { 
		$this->fixture->setSerializedInformation('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getSerializedInformation()
		);
	}
        /**
        * @var PHTH\Pongback\Classes\Domain\Model\RequestPingback
        * 
        */
       protected $checkURL; 
       
       /**
        * @test
        */
       public function validURLForTheTargetLink() {
                $url = "http://de1.php.net/manual-lookup.php?pattern=ping&scope=quickref"; 
               $url2 =  "http://de1.php.net/manual-lookup.php?pattern=ping&scope=quickref" ; 
               
              $this->assertSame("URL",$this->checkURL->requestPingback($url, $url2)); 
        }  
        
  
	
}
?>