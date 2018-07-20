<?php
/**
	Verify the Request is coming from localhost
*/

namespace App\Middleware\Verify;

class Localhost
{
	public function __invoke($REQ, $RES, $NMW)
	{
		$good = false;

		$client = $_SERVER['REMOTE_ADDR'];

		if ('127.0.0.1' == $client) {
			$good = true;
		}

		if ($_SERVER['REMOTE_ADDR'] == $_SERVER['SERVER_ADDR']) {
			$good = true;
		}

		if (!$good) {
			return $RES->withStatus(403);
		}

		$REQ = $REQ->withAttribute('is-local', true);

		$RES = $NMW($REQ, $RES);

		return $RES;

	}
}
