<?php
/**
	Verify the Request is coming from myself
	Sets the 'auth' attribute
*/

namespace App\Middleware\Verify;

class Myself
{
	public function __invoke($REQ, $RES, $NMW)
	{

		$auth = trim($_SERVER['HTTP_AUTHORIZATION']);
		if (!empty($auth)) {
			if (preg_match('/Bearer (.+)$/', $auth, $m)) {
				$auth = $m[1];
			}
		}

		if (empty($auth)) {
			$auth = $_GET['auth'];
		}

		if (empty($auth)) {

			$client = $_SERVER['REMOTE_ADDR'];

			if ('127.0.0.1' == $client) {
				$auth = 'localhost';
			}

			if ($_SERVER['REMOTE_ADDR'] == $_SERVER['SERVER_ADDR']) {
				$auth = 'myself';
			}

		}

		if ($_SERVER['SERVER_NAME'] == $peer['peer']) {
			$auth = 'myself';
		}

		$REQ = $REQ->withAttribute('auth', $auth);

		$RES = $NMW($REQ, $RES);

		// Mabye if it comes back w/o auth, we trash it?

		return $RES;

	}
}
