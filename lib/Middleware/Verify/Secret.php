<?php
/**
	Verify the Secret of the HTTP Request
*/
namespace App\Lib\Middleware\Verify;

class Middleware_Verify_Secret
{
	public function __invoke($REQ, $RES, $NMW)
	{
		$auth = $REQ->getHeaderLine('Authorization');
		$auth = trim($auth);
		if (emptY($auth)) {
			return $RES->withJSON(array(
				'status' => 'failure',
				'detail' => 'Authorization header is requrired',
			), 400);
		}

		if (!preg_match('/^Bearer (.+)$/', $auth, $m)) {
			return $RES->withJSON(array(
				'status' => 'failure',
				'detail' => 'Authorization Bearer is requrired',
			), 400);
		}

		$secret = $m[1];

		// Shit Lookup, Maybe Sqlite or Redis?
		$peer = $this->_find_peer_by_secret($secret);
		if (empty($peer)) {
			return $RES->withJSON(array(
				'status' => 'failure',
				'detail' => 'Forbidden',
			), 403);
		}

		$REQ = $REQ->withAttribute('peer', $peer);

		$RES = $NMW($REQ, $RES);

		return $RES;

	}

	/**
		Lookup a Secret Key in the FileSystem
	*/
	private function _find_peer_by_secret($secret)
	{
		$file_glob = sprintf('%s/var/network/*/secret', APP_ROOT);
		$file_list = glob($file_glob);
		foreach ($file_list as $file) {
			$chk = file_get_contents($file);
			$chk = trim($chk);
			if ($secret == $chk) {
				$path = dirname($file);
				$json_file = sprintf('%s/peer.json', $path);
				$json_data = file_get_contents($json_file);
				$peer_data = json_decode($json_data, true);
				//var_dump($peer_data);
				return $peer_data;
			}
		}

		return null;

	}
}