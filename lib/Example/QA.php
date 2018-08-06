<?php
/**
	An Example to Return Product Details

	In BioTrack is Aggrigated / Grouped Inventory
	In METRC it's Items
	In MJ Freeway/LeafData it's Inventory Types

	Naturally, this is a dummy implementation

*/

namespace App\Example;

class QA extends Base
{
	function __invoke($REQ, $RES, $ARG)
	{

		$license = $ARG['license'];
		$guid = $ARG['guid'];

		$x = $this->_check_license_and_guid($RES, $license, $guid);
		if (!empty($x)) {
			return $x;
		}

		$s = $this->generateStrain($license, $guid);

		$res = array();
		$res['status'] = 'pass|fail|void';
		$res['metric'] = array();
		$res['metric']['general'] = array();
		$res['metric']['microbe'] = array();
		$res['metric']['potency'] = array();
		$res['metric']['solvent'] = array();
		$res['metric']['terpene'] = array();

		return $RES->withJSON(array(
			'status' => 'success',
			'result' => $res,
		), 200, JSON_PRETTY_PRINT);

	}
}