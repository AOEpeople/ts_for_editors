<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2014 Christian Zenker
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

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