<?php
if (!defined('TYPO3_MODE')){
	die('Access denied.');
}

// make sys_template editable for editors, but make all current columns excludeable
t3lib_div::loadTCA('sys_template');
foreach($GLOBALS['TCA']['sys_template']['columns'] as $columnName=>&$columnConfig) {
	if(!array_key_exists('exclude', $columnConfig)) {
		$columnConfig['exclude'] = '1';
	}
}
$GLOBALS['TCA']['sys_template']['ctrl']['adminOnly'] = '0';

// load default configuration
if(!$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['flexForm']) {
	$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['flexForm'] = 'EXT:ts_for_editors/Configuration/FlexForm/sys_template.tx_tsforeditors_constants.xml';
}

// add flexform column
$tempColumns = array (
	'tx_tsforeditors_constants' => array (
		'exclude' => 1,
		'label' => 'Constants',
		'config' => array (
			'type' => 'flex',
			'ds' => array (
				'default' => 'FILE:' . $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['flexForm'],
			),
		)
	),
);

t3lib_extMgm::addTCAcolumns('sys_template',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('sys_template','--div--;LLL:EXT:ts_for_editors\Resources\Private\Language\locallang_db.xml:sys_template.tx_tsforeditors_constants.tab, tx_tsforeditors_constants', '', 'after:description');