<?php
/**
	Log Request and Response to File

	The file is time-stamped
	Under heavy load there could be multiple writers leaving a jumbled file

*/

namespace App\Middleware\Log;

class File
{
	public function __invoke($REQ, $RES, $NMW)
	{
		$f = sprintf('%s/var/request-log-%d.log', APP_ROOT, $_SERVER['REQUEST_TIME']);
		$fh = fopen($f, 'a');

		// Log Request
		$req = json_encode(array(
			'_GET' => $_GET,
			'_POST' => $_POST,
			'_COOKIE' => $_COOKIE,
			'_SESSION' => $_SESSION,
			'_SERVER' => $_SERVER,
		));
		fwrite($fh, ">>>>\n$req\n");


		// Next Middleware
		$RES = $NMW($REQ, $RES);

		// Log Response
		$res = json_encode(array(
			'_HEAD' => $RES->getHeaders(),
			'_BODY' => $RES->getBody()->__toString(),
		));
		fwrite($fh, "<<<<\n$req\n###\n");

		fclose($fh);

		return $RES;

	}
}
