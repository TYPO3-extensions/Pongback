<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'PHTH.' . $_EXTKEY,
	'Pongbackfrontend',
	               
        array(
		'Pingback' => 'list, show',
		
	),
	// non-cacheable actions
	array(
		'Pingback' => '',
		
	)
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'PHTH.' . $_EXTKEY,
	'Pongbackbackend',
	array(
		'Pingback' => 'list, show, delete, edit, publish, unpublish',
		
	),
	// non-cacheable actions
	array(
		'Pingback' => '',
		
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'PHTH.' . $_EXTKEY,
	'server',
	array(
		'Pingback' => 'server',
		
	),
	// non-cacheable actions
	array(
		'Pingback' => 'server',
		
	)
);

$TYPO3_CONF_VARS['SYS']['displayErrors'] = '1';  
$TYPO3_CONF_VARS['MAIL']['substituteOldMailAPI'] = '0';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['pongback']['validatePingback'][] = 'EXT:pongback/Classes/Domain/Validator/PingbackValidator.php:PHTH\Pongback\Domain\Validator\PingbackValidator->validateTargetUri';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['pongback']['validatePingback'][] = 'EXT:pongback/Classes/Domain/Validator/PingbackValidator.php:PHTH\Pongback\Domain\Validator\PingbackValidator->getInformationFromOtherWebsite';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['pongback']['validatePingback'][] = 'EXT:pongback/Classes/Service/PingbackClient.php:PHTH\Pongback\Service\PingbackClient->mailPingbackArrived';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:pongback/Classes/Hook/Tcemain.php:PHTH\Pongback\Hook\Tcemain';

?>