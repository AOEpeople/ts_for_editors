<?php

/**
 * cleans xml data in sys_template.tx_tsforeditors_constants
 *
 * You should run this every time you change or update the flexform
 * to ensure that there are no deprecated values in the xml data and the
 * sorting of the values is correct.
 */
class Tx_TsForEditors_Lowlevel_CleanXmlData extends tx_lowlevel_cleaner_core {

	/**
	 * @var t3lib_DB
	 */
	protected $db;

	/**
	 * @var t3lib_flexformtools
	 */
	protected $flexformTools;

	protected $tableName = 'sys_template';
	protected $fieldName = 'tx_tsforeditors_constants';

	function __construct()	{
		parent::__construct();

		$this->cli_help['name'] = 'tsforeditors_clean_xml_data -- clean xml data in database on ts_for_editors';
		$this->cli_help['description'] =
			'You should run this every time you change or update the flexform to ensure that there are no deprecated values in the xml data and the sorting of the values is correct.'
		;

		$this->cli_help['examples'] = '';

		$this->db = $GLOBALS['TYPO3_DB'];
		$this->flexformTools = t3lib_div::makeInstance('t3lib_flexformtools');
	}

	function main() {
		$res = $this->db->exec_SELECTquery(
			'uid,' . $this->fieldName,
			$this->tableName,
			'1=1 ' . t3lib_BEfunc::deleteClause($this->tableName)
		);

		while($row = $this->db->sql_fetch_assoc($res)) {
			if(empty($row[$this->fieldName])) {
				continue;
			}

			$cleanedXml = $this->flexformTools->cleanFlexFormXML(
				$this->tableName,
				$this->fieldName,
				$row
			);

			$this->db->exec_UPDATEquery(
				$this->tableName,
				'uid  = ' . intval($row['uid']),
				array(
					$this->fieldName => $cleanedXml
				)
			);
		}
	}

}

?>