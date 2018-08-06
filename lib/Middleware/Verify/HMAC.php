<?php
/**
	Verify the HMAC of the Request

	https://web-payments.org/specs/ED/http-signatures/2014-05-08/
	http://docs.aws.amazon.com/AmazonS3/latest/API/sigv4-auth-using-authorization-header.html
	https://docs.aws.amazon.com/AmazonS3/latest/dev/RESTAuthentication.html#RESTAuthenticationExamples

*/

namespace App\Middleware\Verify;

class HMAC
{
	public function __invoke($REQ, $RES, $NMW)
	{
		$x = trim($_SERVER['HTTP_AUTHORIZATION']);
		if (empty($x)) {
			return $RES->withStatus(403);
		}

		if (!preg_match('/OpenTHC (.+):(.+)$/', $x, $m)) {
			return $RES->withStatus(403);
		}

		$public = $m[1];
		$signed = $m[2];

		$H = new HMAC();
		$H->data['VERB'] = $_SERVER['REQUEST_VERB'];
		$H->



		$RES = $NMW($REQ, $RES);

		return $RES;

	}
}