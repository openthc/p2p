<?php
/**
	Search All Peers
*/
namespace App\lib;

class Search
{
	function __invoke($REQ, $RES, $ARG)
	{
		$lic_code = $_GET['license'];
		$obj_type = $_GET['object'];
		$obj_guid = $_GET['guid'];

		$res = $this->_search_parallel($lic_code, $obj_type, $obj_guid);

		return $RES->withJSON(array(
			'status' => 'success',
			'result' => $res,
		));
	}

	/**
		Execute the Search in Serial
	*/
	function _search_serial($obj_type, $lic_code, $obj_guid)
	{
		$ret_list = array();

		$peer_list = Network::listPeers();

		foreach ($peer_list as $peer) {

			$peer_info = Network::loadPeer($peer);

			$url = sprintf('https://%s/object/%s/%s/%s', $peer_info['peer'], $obj_type, $lic_code, $obj_guid);
			//echo "Ping: $url\n";
			$ch = $this->_curl_init($url);
			//$add = curl_multi_add_handle($cmx, $req_list[ $peer ]);
			//echo "Add: $add\n";
			$res = curl_exec($ch);
			$inf = curl_getinfo($ch);
			if (200 == $inf['http_code']) {
				$ret_list[] = json_decode($res, true);
			}
		}

		return $ret_list;

	}

	/**
		Execute the search in parallel
	*/
	function _search_parallel($obj_type, $lic_code, $obj_guid)
	{

		$ret_list = array();
		$req_list = array();

		$cmx = curl_multi_init();

		$peer_list = Network::listPeers();
		foreach ($peer_list as $peer) {

			$peer_info = Network::loadPeer($peer);

			$url = sprintf('https://%s/object/%s/%s/%s', $peer_info['peer'], $obj_type, $lic_code, $obj_guid);

			// echo "url=$url\n";
			$req_list[ $peer ] = $this->_curl_init($url);
			$add = curl_multi_add_handle($cmx, $req_list[ $peer ]);

		}

		// Execute
		$run = null;
		do {
			$mrc = curl_multi_exec($cmx, $run);
		} while ($run > 0); //  ($mrc === CURLM_CALL_MULTI_PERFORM || $run);

		//while ($run && $mrc == CURLM_OK) {
		//	echo "run=$run; mrc=$mrc\n";
		//	if (curl_multi_select($cmx) != -1) {
		//		do {
		//			$mrc = curl_multi_exec($cmx, $run);
		//			echo "mrc=$mrc\n";
		//		} while ($mrc == CURLM_CALL_MULTI_PERFORM);
		//	}
		//}

		foreach ($req_list as $host => $ch) {

			$inf = curl_getinfo($ch);

			if (200 == $inf['http_code']) {
				$res = curl_multi_getcontent($ch);
				$ret_list[] = json_decode($res, true);
			}

			curl_multi_remove_handle($cmx, $ch);
		}

		//while ($info = curl_multi_info_read($cmx, $que)) {
		//	var_dump($info);
		//	//$body = curl_multi_getcontent($info['handle']);
		//	//var_dump(curl_getinfo($info['handle']));
		//	//var_dump($body);
		//}

		curl_multi_close($cmx);

		return $ret_list;
	}

	private function _curl_init($url)
	{
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

		// Booleans
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($ch, CURLOPT_COOKIESESSION, false);
		curl_setopt($ch, CURLOPT_CRLF, false);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_FILETIME, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		//curl_setopt($ch, CURLOPT_FORBID_REUSE, false);
		//curl_setopt($ch, CURLOPT_FRESH_CONNECT, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_NETRC, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLINFO_HEADER_OUT,true);

		// curl_setopt($ch, CURLOPT_BUFFERSIZE, 16384);

		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		// curl_setopt($ch, CURLOPT_SSLVERSION, 3); // 2, 3 or GnuTLS

		curl_setopt($ch, CURLOPT_MAXREDIRS, 0);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        curl_setopt($ch, CURLOPT_USERAGENT, 'OpenTHC/P2P v420.17.248');

        return $ch;

	}
}
