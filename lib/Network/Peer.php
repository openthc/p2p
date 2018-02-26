<?php
/**
	Another Service-Node wants to Peer with Us
*/

class Network_Peer
{
	function __invoke($REQ, $RES, $ARG)
	{

		$peer = $REQ->getAttribute('peer');
		if (empty($peer)) {
			return $RES->withJSON(array(
				'status' => 'failure',
				'detail' => 'Peer Not Identified, check DNS A, AAAA, PTR and TXT',
			), 400);
		}
		//var_dump($peer);

		$peer_domain = $REQ->getAttribute('peer_domain');
		if (empty($peer_domain)) {
			return $RES->withJSON(array(
				'status' => 'failure',
				'detail' => 'Peer Not Identified, check DNS A, AAAA, PTR and TXT',
			), 400);
		}
		//var_dump($peer_domain);

		$file = sprintf('%s/var/network/%s/peer.json', APP_ROOT, $peer_domain);
		$data = json_encode(array(
			'peer' => $peer,
			'domain' => $peer_domain,
			'keybase' => $REQ->getAttribute('peer_keybase'),
			'openthc' => $REQ->getAttribute('peer_openthc'),
		));

		file_put_contents($file, $data);

		// Verify via Keybase
		$p0 = $REQ->getAttribute('peer');
		$p1 = $REQ->getAttribute('peer_openthc');

		if ($p0 != $p1) {
			return $RES->withJSON(array(
				'status' => 'success',
				'detail' => 'CNP#046: PTR and TXT records do not match',
			));
		}

		return $RES->withJSON(array(
			'status' => 'success',
			'detail' => 'Peer Registered',
		));

	}

}
