<?php

require_once dirname(__FILE__) . '/Classes/PHPExcel/IOFactory.php';

$excelFile = "repair_rates.xlsx";

$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objReader->load($excelFile);

$sheetData = $objPHPExcel->getActiveSheet();

$maxCell = $sheetData->getHighestRowAndColumn();
$data = $sheetData->rangeToArray('A1:' . $maxCell['column'] . $maxCell['row'], null, false, true, true);
$ori = $data;
$baseArray['Building Typology'] = [];
$newarray = [];
$counter = 0;
foreach ($ori as $value) {
	foreach ($value as $key => $value) {
		if ($counter == 1) {
			break 2;
		}
		if ($value !== 'Building Typology') {
			$baseArray['Building Typology'][$value] = 0;
		}
	}
	$counter++;
}

unset($ori[1]);
$temp = [];
foreach ($ori as $value) {
	static $i = 0;
	$temp[$i] = $baseArray;
	$newkey = '';
	foreach ($value as $key => $value) {
		if (array_key_exists('Building Typology', $temp[$i])) {
			$newkey = $value;
			$temp[$i][$newkey] = $temp[$i]['Building Typology'];
			unset($temp[$i]['Building Typology']);
			continue;
		}

		$keys = array_keys($temp[$i][$newkey]);
		$val = $temp[$i][$newkey][$keys[0]];
		unset($temp[$i][$newkey][$keys[0]]);
		if ($value === 0.0) {
			$temp[$i][$newkey][$keys[0]] = 'null';

		} else {
			$temp[$i][$newkey][$keys[0]] = number_format((float) $value, 2, '.', '');
		}

	}
	$i++;

}
echo "<pre>";
var_export($temp);
echo "</pre>";
?>
