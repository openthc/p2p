<?php
/**
	Verify DNS of the Peer
*/

class Middleware_Verify_DNS
{
	public function __invoke($REQ, $RES, $NMW)
	{
		// X-OpenTHC-Peer Header
//		$peer = $_SERVER['HTTP_X_OPENTHC_PEER'];
//		if (empty($peer)) {
//			$peer = $_GET['peer'];
//		}
//
//		if (empty($peer)) {
//			return $RES->withJSON(array(
//				'status' => 'failure',
//				'detail' => 'MVD#015: Header "x-openthc-peer" is missing',
//			));
//		}

		$ipv4 = $ipv6 = null;
		$ipxx = $_SERVER['REMOTE_ADDR'];
		if (preg_match('/^[\d\.]+$/', $ipxx)) {
			$ipv4 = $ipxx;
		} elseif (preg_match('/^[0-9a-f:]+/', $ipxx)) {
			$ipv6 = $ipxx;
		}


		$peer = gethostbyaddr($ipv4);
		//echo "peer:$peer\n";

		$peer_domain = $this->getDomain($peer);

		if (empty($peer_domain)) {
			return $RES->withJSON(array(
				'status' => 'failure',
				'detail' => 'MVD#040: Failed to discover domain',
			), 400);
		}


		// DNS
		$res_txt = $this->getTXTRecords($peer_domain);
		if (empty($res_txt)) {
			return $RES->withJSON(array(
				'status' => 'failure',
				'detail' => 'MVD#050: Failed to locate TXT entries',
			), 400);
		}

		// DIG TXT for Keybase Reference
		$keybase = false;
		foreach ($res_txt as $txt) {
			$txt = trim($txt, '"');
			if (preg_match('/^keybase-site\-verification=(.+)$/', $txt, $m)) {
				$keybase = $m[1];
			}
		}

		// DIG TXT for OpenTHC Reference
		$openthc = null;
		foreach ($res_txt as $txt) {
			$txt = trim($txt, '"');
			if (preg_match('/^openthc\-p2p=(.+)$/', $txt, $m)) {
				$openthc = $m[1];
			}
		}

		$REQ = $REQ->withAttribute('peer', $peer);
		$REQ = $REQ->withAttribute('peer_domain', $peer_domain);
		$REQ = $REQ->withAttribute('peer_keybase', $keybase);
		$REQ = $REQ->withAttribute('peer_openthc', $openthc);

		// Reverse DNS
//		$peer_ip_list = gethostbynamel($peer);
//		foreach ($peer_ip_list as $peer)
//		print_r($peer_ip_list);

		$RES = $NMW($REQ, $RES);

		return $RES;

	}

	/**
		Discover the DOAMIN from the HOST
		@param $p Peer Hostname
		@return string Domain Name
	*/
	function getDomain($p)
	{
		//$ans = null;
		//$ext = null;
		//$res_all = dns_get_record($peer, DNS_ANY, $ans, $ext); // , DNS_ANY, $ans, $ext, true);
		//print_r($res_all);
		//print_r($ans);
		//print_r($ext);
		$cmd = sprintf('dig SOA %s +nocomments +noquestion', escapeshellarg($p));
		$buf = shell_exec($cmd);
		//var_dump($buf);
		if (preg_match('/^([\w\-\.]+)\s+\d+\s+IN\s+SOA\s+.+[\d ]+$/ms', $buf, $m)) {
			$d = $m[1];
			$d = trim($d, '.');
			return $d;
		}

	}

	function getReverseDNS()
	{

	}

	/**
		@param $d Domain
	*/
	function getTXTRecords($d)
	{
		//$res_txt = dns_get_record($d, DNS_TXT);
		//if (empty($res_txt)) {
		//	// Temp Failure
		//	return $RES->withJSON(array(
		//		'status' => 'failure',
		//		'detail' => 'MVD#029: Failed to locate DNS entries',
		//	));
		//}

		$cmd = sprintf('dig TXT %s +nocomments +noquestion', escapeshellarg($d));
		$buf = shell_exec($cmd);

		if (preg_match_all('/IN\s+TXT\s+(.+)$/m', $buf, $m)) {
			return $m[1];
		}

	}
}
