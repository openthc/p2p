<?php
/**
 * Spew Information about my Network
 */

namespace App\Controller\Network;

class Info
{
	function __invoke($REQ, $RES, $ARG)
	{
		$info = array();

		$peer_list = \App\Network::listPeers();
		foreach ($peer_list as $peer) {
			$info[$peer] = \App\Network::loadPeer($peer);
		}

		return $RES->withJSON([
			'meta' => [],
			'data' => $info,
		], 200);

	}
}
