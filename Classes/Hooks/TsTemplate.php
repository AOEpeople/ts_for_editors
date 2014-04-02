<?php

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
			/* @var $flexformService Tx_Extbase_Service_FlexFormService */
			$flexformService = t3lib_div::makeInstance('Tx_Extbase_Service_FlexFormService');
			$data = $flexformService->convertFlexFormContentToArray($params['row']['tx_tsforeditors_constants']);

			$constants = $this->flattenArray($data);
			if($constants) {
				array_walk($constants, function(&$value, $key) { $value = sprintf('%s = %s', $key, $value); });
				$tsTemplate->constants[] = implode("\n", $constants);
			}
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