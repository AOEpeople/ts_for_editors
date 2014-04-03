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

class Tx_TsForEditors_Hooks_TsTemplate {

	/**
	 * add constants from the tx_tsforeditors_constants field
	 *
	 * implements the ['t3lib/class.t3lib_tstemplate.php']['includeStaticTypoScriptSourcesAtEnd'] hook
	 *
	 * @param array $params
	 * @param t3lib_TStemplate $tsTemplate
	 */
	public function includeStaticTypoScriptSourcesAtEnd($params, $tsTemplate) {
		if ($params['row']['tx_tsforeditors_constants']) {
			$tsTemplate->constants[] = $this->getOverrideTsConstants(
				$params['row']['tx_tsforeditors_constants']
			);
		}
	}

	protected function getOverrideTsConstants($xmlData) {
		/* @var $flexformService Tx_Extbase_Service_FlexFormService */
		$flexformService = t3lib_div::makeInstance('Tx_Extbase_Service_FlexFormService');
		$data = $flexformService->convertFlexFormContentToArray($xmlData);

		$constants = $this->flattenArray($data);
		if ($constants) {
			array_walk($constants, function (&$value, $key) {
				$value = sprintf('%s = %s', $key, $value);
			});
			return implode("\n", $constants);
		} else {
			return '';
		}
	}

	/**
	 * flatten the keys
	 *
	 * @param $array
	 * @param string $separator
	 * @param string $prefix
	 * @return array
	 * @throws InvalidArgumentException
	 */
	protected function flattenArray($array, $separator='.', $prefix='') {
		if(!is_array($array)) {
			throw new \InvalidArgumentException(sprintf(
				'$array is not an array but %s',
				is_object($array) ? get_class($array) : gettype($array)
			));
		}
		$flattened = array();
		foreach($array as $key=>$value) {
			if($prefix) {
				$key = $prefix . $separator . $key;
			}
			if(is_array($value)) {
				$flattened = $flattened + $this->flattenArray($value, $separator, $key);
			} else {
				$flattened[$key] = $value;
			}
		}
		return $flattened;
	}
}