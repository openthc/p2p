<?php
/**
	Respond to Network Ping
*/
namespace App\lib\Network;

class Network_Ping
{
	/**
		Just Reply with PONG
	*/

	function __invoke($REQ, $RES, $ARG) {

		$now = strftime('%Y-%m-%d %H:%M:%S');

		$upd = stat(sprintf('%s/var', APP_ROOT));
		$upd = strftime('%Y-%m-%d %H:%M:%S', $upd['mtime']);
		$upt = trim(file_get_contents('/proc/uptime'));

		$RES = $RES->withHeader('Content-Type', 'text/plain');

		$RES = $RES->write("PONG\n$now\n$upd\n");

		return $RES;

	}
}
