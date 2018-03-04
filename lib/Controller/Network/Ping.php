<?php
/**
	Respond to Network Ping
*/

namespace App\lib\Controller\Network;

class Ping
{
	/**
		Just Reply with PONG
	*/

	function __invoke($REQ, $RES, $ARG) {

		$out = array();
		//"PONG\n$now\n$upd\npeer:$peer\ndomain:\n$peer_domain"

		$now = strftime('%Y-%m-%d %H:%M:%S');

		$upd = stat(sprintf('%s/var', APP_ROOT));
		$upd = strftime('%Y-%m-%d %H:%M:%S', $upd['mtime']);
		//$upt = trim(file_get_contents('/proc/uptime'));

		$out['current'] = $now;
		$out['updated'] = $upd;

		// $attr_list = $REQ->getAttributes();
		$peer = $REQ->getAttribute('peer');
		if (empty($peer)) {
			// Bad
		}
		$out['peer'] = $peer;

		$peer_domain = $REQ->getAttribute('peer_domain');
		if (empty($peer_domain)) {
			// Bad
		}
		$out['domain'] = $peer;

		// Known
		$out['known'] = false;
		$file = sprintf('%s/var/network/%s/peer.json', APP_ROOT, $peer_domain);
		if (is_file($file)) {
			$out['known'] = true;
		}

		$out['trust'] = false;
		$file = sprintf('%s/var/network/%s/secret.key', APP_ROOT, $peer_domain);
		if (is_file($file)) {
			$out['trust'] = true;
		}

		$RES = $RES->withHeader('Content-Type', 'text/plain');
		$RES = $RES->withJSON($out, 200, JSON_PRETTY_PRINT);

		return $RES;

	}
}
