<?php
/**
	Verify the Request is coming from localhost
*/

namespace App\lib\Middleware\Verify;

class Localhost
{
	public function __invoke($REQ, $RES, $NMW)
	{
		$is_local = false;

		$client = $_SERVER['REMOTE_ADDR'];

		if ('127.0.0.1' == $client) {
			$is_local = true;
		}

		if ($_SERVER['REMOTE_ADDR'] == $_SERVER['SERVER_ADDR']) {
			$is_local = true;
		}

		$REQ = $REQ->withAttribute('is-local', $auth);

		$RES = $NMW($REQ, $RES);

		return $RES;

	}
}
