<?php
/**
	An Example to Return Product Details

	In BioTrack is Aggrigated / Grouped Inventory
	In METRC it's Items
	In MJ Freeway/LeafData it's Inventory Types

	Naturally, this is a dummy implementation

*/

namespace App\Example;

class Transfer
{
	function __invoke($REQ, $RES, $ARG)
	{
		$x = $_SERVER['HTTP_AUTHORIZATION'];
		if (empty($x)) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Need an Authorization header [LET#022]' ],
				'data' => [],
			], 403);
		}
		if (!preg_match('/^bearer (.+)$/i', $x, $m)) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Authorization header should have Bearer [LET#028]' ],
				'data' => [],
			], 403);
		}

		// Valid Peer
		$good = false;
		$auth = $m[1];

		// Find in Peers
		$peer_data = [];
		$peer_list = \App\Network::listPeers();
		foreach ($peer_list as $peer) {
			$peer_data[$peer] = \App\Network::loadPeer($peer);
		}

		foreach ($peer_data as $peer) {
			if ($auth == $peer['secret']) {
				$good = true;
			}
		}
		if (!$good) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Send a Valid Peer Secret [LET#050]' ],
				'data' => [],
			], 403);
		}

		$pk = $ARG['id'];
		// $lic = strtok($pk, '.');

		switch ($pk) {
		case 'WAG123456.TTABCDE':
			return $this->_WAG123456($RES, $ARG);
		case 'WAJ234567.TTFGHIJ':
			return $this->_WAJ234567($RES, $ARG);
		// case 'WAM345678':
		// 	return $this->_WAM345678($RES, $ARG);
		// case 'WAR456789':
		// 	return $this->_WAR456789($RES, $ARG);
		}

		return $RES->withJSON([
			'meta' => [ 'detail' => 'Example Only Knows a few License IDs [LET#070]' ],
			'data' => [],
		], 404);

	}

	/**
	 * Fake Supply->Supply Type Sale
	 * @param [type] $RES [description]
	 * @param [type] $ARG [description]
	 * @return [type] [description]
	 */
	function _WAG123456($RES, $ARG)
	{
		$lic = 'WAG123456';

		if ('G123456' != $_GET['source']) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Invalid Source [LET#089' ],
				'data' => [],
			], 400);
		}

		if ('J234567' != $_GET['target']) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Invalid Target [LET#096' ],
				'data' => [],
			], 400);
		}

		return $RES->withJSON([
			'meta' => [ ],
			'data' => [
				'id' => sprintf('%s.TTABCDE', $lic),
				'created' => date(\DateTime::RFC3339, time() - 86400),
				'updated' => date(\DateTime::RFC3339),
				'status' => 'open',
				'source' => $_GET['source'],
				'target' => $_GET['target'],
				'item_list' => [
					[
						'id' => sprintf('%s.INABCDE', $lic),
						'qty' => 1000,
						'product' => [
							'name' => 'Example One - Bulk Flower  - 1000 g',
						],
						'package' => [
							'type' => 'bulk',
							'uom' => 'g',
						],
						'variety' => [
							'name' => 'Alpha Cow',
						],
					],
					[
						'id' => sprintf('%s.INFGHIJ', $lic),
						'qty' => 100,
						'product' => [
							'name' => 'Example Two - Packaged Flower - 100 3.5 g Bags',
						],
						'package' => [
							'type' => 'each',
							'qom' => 3.5,
							'uom' => 'q',
						],
						'variety' => [
							'name' => 'Betazoid',
						],
					],
					[
						'id' => sprintf('%s.INKLMNO', $lic),
						'qty' => 1.2345,
						'product' => [
							'name' => 'Example Three - Bulk Oil - 1.2L',
						],
						'package' => [
							'type' => 'bulk',
							'uom' => 'l',
						],
						'variety' => [
							'name' => 'Alpha Cow',
						],
					],
				]
			]
		], 200, JSON_PRETTY_PRINT);

	}

	/**
	 * Fake Supply->Retail Transaction
	 * @param [type] $RES [description]
	 * @param [type] $ARG [description]
	 * @return [type] [description]
	 */
	function _WAJ234567($RES, $ARG)
	{
		$lic = 'WAJ234567';

		if ('J234567' != $_GET['source']) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Invalid Source [LET#089' ],
				'data' => [],
			], 400);
		}

		if ('R456789' != $_GET['target']) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Invalid Target [LET#096' ],
				'data' => [],
			], 400);
		}

		return $RES->withJSON([
			'meta' => [ ],
			'data' => [
				'id' => sprintf('%s.TTFGHIJ', $lic),
				'created' => date(\DateTime::RFC3339, time() - 86400),
				'updated' => date(\DateTime::RFC3339),
				'status' => 'open',
				'source' => $_GET['source'],
				'target' => $_GET['target'],
				'item_list' => [
					[
						'id' => sprintf('%s.INFGHIJ', $lic),
						'qty' => 100,
						'product' => [
							'name' => 'Example Two - Packaged Flower - 100 3.5 g Bags',
						],
						'package' => [
							'type' => 'each',
							'qom' => 3.5,
							'uom' => 'q',
						],
						'variety' => [
							'name' => 'Betazoid',
						],
					],
					[
						'id' => sprintf('%s.INKLMNO', $lic),
						'qty' => 50,
						'product' => [
							'name' => 'Example Three - Packaged Edible - 50 of (10 x 1g Chews)',
						],
						'package' => [
							'type' => 'pack',
							'qom' => 10,
							'uom' => 'ea',
						],
						'package_unit' => [
							'_note' => 'some case for this being called package_each ?',
							'type' => 'each',
							'qom' => 1,
							'uom' => 'g',
						],
						'variety' => [
							'name' => 'Cosmic Charlie',
						],
					],
				]
			]
		], 200, JSON_PRETTY_PRINT);

	}

	function _WAM345678($RES, $ARG)
	{
		$lic = 'WAM345678';

		return $RES->withJSON([
			'meta' => [
				'created' => date(\DateTime::RFC3339, time() - 86400),
				'updated' => date(\DateTime::RFC3339),
			],
			'data' => [
				'_incomplete' => true,
			]
		]);

	}

	function _WAR456789($RES, $ARG)
	{
		$lic = 'WAR456789';

		return $RES->withJSON([
			'meta' => [
				'created' => date(\DateTime::RFC3339, time() - 86400),
				'updated' => date(\DateTime::RFC3339),
			],
			'data' => [
				'_incomplete' => true,
			]
		]);

	}
}
