<?php
$extensionPath = t3lib_extMgm::extPath('pongback');

$libraryPath = $extensionPath.'Classes/Library/fXmlRpc/';

$autoloadArray = array();
/**
 * This is why we must include the fXmlRpc libary 
 * search all files and directories and include it 
 */
    $classes = \TYPO3\CMS\Core\Utility\GeneralUtility::getAllFilesAndFoldersInPath($autoloadArray, $libraryPath, 'php', $regDirs, 2);
foreach($classes as $class) {
    $alias = str_replace($libraryPath, 'fXmlRpc\\', $class);
    $alias = str_replace('/', '\\', $alias);
    $alias = str_replace('.php', '', $alias);
    $autoloadArray[$alias] = $class;
}
return $autoloadArray;


?>