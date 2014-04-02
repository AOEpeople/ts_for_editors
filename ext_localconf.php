<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tstemplate.php']['includeStaticTypoScriptSourcesAtEnd'][] =
	'EXT:' . $_EXTKEY . '/Classes/Hooks/TsTemplate.php:&Tx_TsForEditors_Hooks_TsTemplate->includeStaticTypoScriptSourcesAtEnd'
;