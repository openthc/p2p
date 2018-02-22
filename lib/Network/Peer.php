<?php
/**
	Another Service-Node wants to Peer with Us
*/
namespace App\Lib\Network;

class Network_Peer
{
	function __invoke($REQ, $RES, $ARG) {

		header('Content-Type: text/plain');
		echo "\n";
		print_r($_POST);
		//echo "\nConnected\n";

		$peer_id = $_SERVER['HTTP_X_PEER'];
		if (empty($peer_id)) {
			return $RES->withJSON(array(
				'status' => 'failure',
				'detail' => 'Provide the X-Peer header',
			));
		}
		if (!preg_match('/^\w[\w\-\.]+\.\w+$/', $peer_id)) {
			return $RES->withJSON(array(
				'status' => 'failure',
				'detail' => 'Invalid Peer ID',
			));
		}

		$peer_ip = $_SERVER['REMOTE_ADDR'];

		// Lookup Peer IP from
		$peer_ip_list = gethostbynamel($peer_id);
		print_r($peer_ip_list);

		// Verify Host

		// Inspect their KeyBase DNS?

		// Add to Peer List
		//$redis->lpush($host);

	}
}
