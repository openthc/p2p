<?php
/**
	An Example to Return Product Details

	In BioTrack is Aggrigated / Grouped Inventory
	In METRC it's Items
	In MJ Freeway/LeafData it's Inventory Types

	Naturally, this is a dummy implementation

*/
namespace App\lib\Example;

class Example_Product
{
	function __invoke($REQ, $RES, $ARG)
	{

		$license = $ARG['license'];
		$product = $ARG['product'];

		// Return a Plant
		$prod[] = array(
			'guid' => 'PL111111',
			'name' => 'GobStopper 7g',
			'strain' => array(
				'name' => 'Free Text',
			),
			'package' => array(
				'type' => 'each',
				'size' => 1,
				'unit' => 'ea',
			),
		);

		// Return a Bulk of Weed
		$prod[] = array(
			'guid' => 'IN222222',
			'name' => 'GobStopper Bulk Package',
			'type' => 'Trim',
			'strain' => array(
				'name' => 'Free Text',
			),
			'package' => array(
				'type' => 'bulk',
				'size' => 2500,
				'unit' => 'g',
			),
		);

		// Return a Bag of Weed
		$prod[] = array(
			'guid' => 'IN333333',
			'strain' => array(
				'name' => 'Free Text',
			),
			'package' => array(
				'type' => 'each',
				'size' => 3.5,
				'unit' => 'g',
			),
		);

		// Return a Bag of Weed
		$prod[] = array(
			'guid' => 'IN444444',
			'strain' => array(
				'name' => 'Free Text',
			),
			'package' => array(
				'type' => 'pack',
				'pack' => 6,
				'size' => 12,
				'unit' => 'oz',
			),
		);

		$i = array_rand($prod);
		$prod = $prod[$i];

		return $RES->withJSON(array(
			'status' => 'success',
			'result' => $prod
		), 200, JSON_PRETTY_PRINT);

	}
}