<?php
/**
	Search All Peers
*/

namespace App\lib\Controller;

use App\lib\Network;

class Search
{
	function __invoke($REQ, $RES, $ARG)
	{
		$lic_code = $_GET['license'];
		$obj_type = $_GET['object'];
		$obj_guid = $_GET['guid'];

		$auth = $REQ->getAttribute('auth');
		if (empty($auth)) {
			return $RES->withJSON(array(
				'status' => 'failure',
				'detail' => 'Not Authorized',
			), 403);
		}

		if (empty($lic_code)) {
			return $RES->withJSON(array(
				'status' => 'failure',
				'detail' => 'License Owner Missing',
			), 400);
		}

		if (empty($obj_type)) {
			return $RES->withJSON(array(
				'status' => 'failure',
				'detail' => 'Object Type Missing',
			), 400);
		}

		if (empty($obj_guid)) {
			return $RES->withJSON(array(
				'status' => 'failure',
				'detail' => 'Object GUID Missing',
			), 400);
		}

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

		$url_list = $this->_make_search_list($obj_type, $lic_code, $obj_guid);

		foreach ($url_list as $peer => $url) {

			$ch = _curl_init($url);

			$res = curl_exec($ch);
			$inf = curl_getinfo($ch);

			if (200 == $inf['http_code']) {
				$ret_list[ $peer ] = json_decode($res, true);
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

		$url_list = $this->_make_search_list($obj_type, $lic_code, $obj_guid);

		foreach ($url_list as $peer => $url) {

			$req_list[ $peer ] = _curl_init($url);
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

	/**
		Get the List of Peers to Search
	*/
	private function _make_search_list($obj_type, $lic_code, $obj_guid)
	{
		$url_list = array();

		$peer_list = Network::listPeers();
		foreach ($peer_list as $peer) {

			$info = Network::loadPeer($peer);

			$url = sprintf('https://%s/object/%s/%s/%s', $info['peer'], $obj_type, $lic_code, $obj_guid);

			$url_list[$peer] = $url;

		}

		return $url_list;
	}

}
