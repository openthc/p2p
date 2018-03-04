<?php
/**
	Network Peer Data Loader
	You may wish to over-ride this with a database hander or something
	The file-system method was implemented to make it easier to get started.
*/
namespace App\lib;

class Network
{
	public static function listPeers()
	{
		$host_list = array();

		$host_file_list = glob(sprintf('%s/var/network/*/peer.json', APP_ROOT));
		foreach ($host_file_list as $host_file) {
			$path = dirname($host_file);
			$host = basename($path); // , '.json');
			$host_list[] = $host;
		}

		return $host_list;

	}

	public static function loadPeer($p)
	{
		$p = basename($p);

		$host_file = sprintf('%s/var/network/%s/peer.json', APP_ROOT, $p);
		if (!is_file($host_file)) {
			throw new \Exception('ALN#028: Peer Not Found');
			return null;
		}

		$host_data = file_get_contents($host_file);
		$host = json_decode($host_data, true);

		return $host;

	}

}
