<?php
/**
	An Example to Return Product Details

	In BioTrack is Aggrigated / Grouped Inventory
	In METRC it's Items
	In MJ Freeway/LeafData it's Inventory Types

	Naturally, this is a dummy implementation

*/

namespace App\lib\Example;

class Product extends Base
{
	function __invoke($REQ, $RES, $ARG)
	{

		$license = $ARG['license'];
		$guid = $ARG['guid'];

		$x = $this->_check_license_and_guid($RES, $license, $guid);
		if (!empty($x)) {
			return $x;
		}

		$p = $this->generateProduct($license, $guid);

		return $RES->withJSON(array(
			'status' => 'success',
			'result' => $p
		), 200, JSON_PRETTY_PRINT);

	}
}