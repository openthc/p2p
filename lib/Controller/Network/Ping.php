<?php
/**
	Respond to Network Ping
*/

namespace App\Controller\Network;

class Ping
{
	/**
		Just Reply with PONG
	*/

	function __invoke($REQ, $RES, $ARG)
	{

		//"PONG\n$now\n$upd\npeer:$peer\ndomain:\n$peer_domain"
		$out = [
			'meta' => [],
			'data' => []
		];

		$now = date(\DateTime::RFC3339); // strftime('%Y-%m-%d %H:%M:%S');

		$upd = stat(sprintf('%s/var', APP_ROOT));
		$upd = date(\DateTime::RFC3339, $upd['mtime']);
		//$upt = trim(file_get_contents('/proc/uptime'));

		$out['meta']['current'] = $now;
		$out['meta']['updated'] = $upd;

		// $attr_list = $REQ->getAttributes();
		$peer = $REQ->getAttribute('peer');
		if (empty($peer)) {
			// Bad
		}
		$out['meta']['peer'] = $peer;

		$peer_domain = $REQ->getAttribute('peer_domain');
		if (empty($peer_domain)) {
			// Bad
		}
		// $out['meta']['domain'] = $peer;

		// Known
		$out['meta']['peer']['known'] = false;
		$file = sprintf('%s/var/network/%s/peer.json', APP_ROOT, $peer_domain);
		if (is_file($file)) {
			$out['meta']['peer']['known'] = true;
		}

		$out['meta']['peer']['trust'] = false;
		$file = sprintf('%s/var/network/%s/secret.key', APP_ROOT, $peer_domain);
		if (is_file($file)) {
			$out['meta']['peer']['trust'] = true;
		}

		$RES = $RES->withHeader('content-type', 'text/plain');
		$RES = $RES->withJSON($out, 200, JSON_PRETTY_PRINT);

		return $RES;

	}
}
