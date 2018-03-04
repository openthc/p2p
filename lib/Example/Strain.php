<?php
/**
	An Example to Return Product Details

	In BioTrack is Aggrigated / Grouped Inventory
	In METRC it's Items
	In MJ Freeway/LeafData it's Inventory Types

	Naturally, this is a dummy implementation

*/
namespace App\lib\Example;

class Example_Strain extends Example_Base
{
	function __invoke($REQ, $RES, $ARG)
	{

		$license = $ARG['license'];
		$guid = $ARG['guid'];

		$x = $this->_check_license_and_guid($RES, $license, $guid);
		if (!empty($x)) {
			return $x;
		}

		return $RES->withJSON(array(
			'status' => 'success',
			'result' => array(
				'opk' => '1000',
				'guid' => $guid,
				'name' => 'Example Strain Name',
			),
		), 200, JSON_PRETTY_PRINT);

	}
}
