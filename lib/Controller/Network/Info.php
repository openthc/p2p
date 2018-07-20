<?php
/**
	Another Service-Node wants to Peer with Us
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

		return $RES->withJSON($info, 200, JSON_PRETTY_PRINT);
	}
}
