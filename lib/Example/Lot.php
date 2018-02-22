<?php
/**
	An Example to Return Product Details

	In BioTrack is Aggrigated / Grouped Inventory
	In METRC it's Items
	In MJ Freeway/LeafData it's Inventory Types

	Naturally, this is a dummy implementation

*/
namespace App\lib\Example;

class Example_Lot
{
	function __invoke($REQ, $RES, $ARG) {

		$license = $ARG['license'];
		$guid = $ARG['guid'];

		return $RES->withJSON(array(
			'status' => 'success',
			'result' => array(
				'guid' => $guid,
				'base' => 'GobStopper',
				'name' => 'GobStopper 7g',
				'strain' => array(
					'name' => 'GobStopper',
				),
				'package' => array(
					'type' => 'each',
					'size' => 7,
					'unit' => 'g',
				),
			)
		), 200, JSON_PRETTY_PRINT);
	}

	function returnLiquidEdible()
	{

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