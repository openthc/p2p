<?php
/**
	Another Service-Node wants to Peer with Us
*/

namespace App\lib\Controller\Network;

class Peer
{
	function __invoke($REQ, $RES, $ARG)
	{

		$peer = $REQ->getAttribute('peer');
		if (empty($peer)) {
			return $RES->withJSON(array(
				'status' => 'failure',
				'detail' => 'Peer Not Identified, check DNS A, AAAA, PTR and TXT',
			), 400, JSON_PRETTY_PRINT);
		}
		//var_dump($peer);

		$peer_domain = $REQ->getAttribute('peer_domain');
		if (empty($peer_domain)) {
			return $RES->withJSON(array(
				'status' => 'failure',
				'detail' => 'Peer Not Identified, check DNS A, AAAA, PTR and TXT',
			), 400, JSON_PRETTY_PRINT);
		}
		//var_dump($peer_domain);

		// Verify via Keybase
		$p0 = $REQ->getAttribute('peer');
		$p1 = $REQ->getAttribute('peer_openthc');

		if ($p0 != $p1) {
			return $RES->withJSON(array(
				'status' => 'failure',
				'detail' => 'CNP#046: PTR and TXT records do not match',
				'result' => array(
					'peer' => $peer,
					'domain' => $peer_domain,
					//'dns-keybase' => $
					'dns-openthc' => $p1,
				)
			), 400, JSON_PRETTY_PRINT);
		}

		$file = sprintf('%s/var/network/%s/peer.json', APP_ROOT, $peer_domain);
		$data = json_encode(array(
			'peer' => $peer,
			'domain' => $peer_domain,
			'keybase' => $REQ->getAttribute('peer_keybase'),
			'openthc' => $REQ->getAttribute('peer_openthc'),
		));

		file_put_contents($file, $data);

		return $RES->withJSON(array(
			'status' => 'success',
			'detail' => 'Peer Registered',
		));

	}

}
