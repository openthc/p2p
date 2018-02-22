<?php
/**
	An Example to Return Product Details

	In BioTrack is Aggrigated / Grouped Inventory
	In METRC it's Items
	In MJ Freeway/LeafData it's Inventory Types

	Naturally, this is a dummy implementation

*/
namespace App\lib\Example;

class Example_Strain
{
	function __invoke($REQ, $RES, $ARG) {

		$license = $ARG['license'];
		$product = $ARG['product'];

		// Connect to your DB
		// $dbc = new PDO();

		// Query your DB
		

		// Return 0
		return $RES->withJSON(array(
			'status' => 'failure',
			'result' => null,
		), 404);

		// Return 1
		$prod[] = array(
			'ocpc' => '1234ABCE',
			'base' => 'GobStopper',
			'name' => 'GobStopper 7g',
			'strain' => array(
				'name' => 'Free Text',
			),
			'package' => array(
				'type' => 'each',
				'size' => 7,
				'unit' => 'g',
			),
		);

		return $RES->withJSON(array(
			'status' => 'success',
			'result' => $prod
		));

	}
}