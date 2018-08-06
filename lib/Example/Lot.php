<?php
/**
	An Example to Return Product Details

	In BioTrack is Aggrigated / Grouped Inventory
	In METRC it's Items
	In MJ Freeway/LeafData it's Inventory Types

	Naturally, this is a dummy implementation

*/

namespace App\Example;

class Lot extends Base
{
	function __invoke($REQ, $RES, $ARG) {

		$license = $ARG['license'];
		$guid = $ARG['guid'];

		$x = $this->_check_license_and_guid($RES, $license, $guid);
		if (!empty($x)) {
			return $x;
		}

		$p = $this->generateProduct($license, $guid);

		return $RES->withJSON(array(
			'status' => 'success',
			'result' => $p,
		), 200, JSON_PRETTY_PRINT);
	}

	function returnLiquidEdible()
	{
		$s = $this->generateStrain($license, $guid);

		return $RES->withJSON(array(
			'status' => 'success',
			'result' => array(
				'guid' => $guid,
				'base' => 'Fire Water',
				'name' => 'Fire Water - Blackberry 12oz',
				'strain' => array(
					'name' => 'Fire Water',
				),
				'dose' => array(
					'size' => 25,
					'unit' => 'mg',
				),
				'package' => array(
					'type' => 'each',
					'size' => 12,
					'unit' => 'oz',
				),
			)
		));

	}
}