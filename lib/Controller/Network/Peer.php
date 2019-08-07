<?php
/**
	Another Service-Node wants to Peer with Us
*/

namespace App\Controller\Network;

class Peer
{
	function __invoke($REQ, $RES, $ARG)
	{

		$peer = $REQ->getAttribute('peer');
		if (empty($peer)) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Peer Not Identified, check DNS A, AAAA, PTR and TXT [CNP#016]' ],
				'data' => [],
			], 400, JSON_PRETTY_PRINT);
		}
		//var_dump($peer);

		$peer_domain = $REQ->getAttribute('peer_domain');
		if (empty($peer_domain)) {
			return $RES->withJSON([
				'meta' => [ 'detail' => 'Peer Not Identified, check DNS A, AAAA, PTR and TXT [CNP#025]' ],
				'data' => [],
			], 400, JSON_PRETTY_PRINT);
		}
		//var_dump($peer_domain);

		// Verify via Keybase
		$p0 = $REQ->getAttribute('peer');
		$p1 = $REQ->getAttribute('peer_openthc');

		if ($p0 != $p1) {
			return $RES->withJSON([
				'meta' => [
					'detail' => 'PTR and TXT records do not match [CNP#046]',
				],
				'data' => [
					'peer' => $peer,
					'domain' => $peer_domain,
					//'dns-keybase' => $
					'dns-openthc' => $p1,
				]
			], 400, JSON_PRETTY_PRINT);
		}

		$file = sprintf('%s/var/network/%s/public.json', APP_ROOT, $peer_domain);
		$data = json_encode(array(
			'peer' => $peer,
			'domain' => $peer_domain,
			'secret' => $REQ->getAttribute('peer_secret'),
			'keybase' => $REQ->getAttribute('peer_keybase'),
			'openthc' => $REQ->getAttribute('peer_openthc'),
		));

		// Make Directory?
		$path = dirname($file);
		if (!is_dir($path)) {
			mkdir($path, 0755, true);
		}

		file_put_contents($file, $data);

		return $RES->withJSON([
			'meta' => [ 'detail' => 'Peer Registered' ],
			'data' => $REQ->getAttribute('peer_secret')
		]);

	}

}
