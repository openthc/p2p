<?php
/**
 * Notes about the Auth module
 * The "program-key" cooresponds to a code that is a company object identifier
 * The "license-key" cooresponds to a code that is a license object identifier
 *
 * Licenses can belong to a company in a 1:M way
 * Companies can have different permissions to act on a license's object
 *
 */

namespace Test;

class Network extends \Test\OpenTHC_Test_Case
{
	public function test_alpha()
	{
		$res = $this->ghc->get('/network');
		$res = $this->assertValidResponse($res, 404);

		$res = $this->ghc->get('/network/info');
		$res = $this->assertValidResponse($res);

		$res = $this->ghc->get('/network/ping');
		$res = $this->assertValidResponse($res);

	}

	public function test_network_info()
	{
		$res = $this->ghc->get('/network/info');
		$res = $this->assertValidResponse($res);
	}

	public function test_network_ping()
	{
		$res = $this->ghc->get('/network/ping');
		$res = $this->assertValidResponse($res);
	}

	public function test_sign_request()
	{
		$client_public = 'test.openthc.dev';
		$client_secret = 'a90a317e8ed94b2dbe6d9ee5b5f62540befd0691eb74466a445623b057ea3976';

		$url = '/network/auth';
		$arg = [
			'client' => $client_public,
			'timestamp' => date(\DateTime::RFC3339)
		];
		ksort($arg);

		$sig = [];
		$sig[] = 'GET';
		$sig[] = $url;
		$sig[] = http_build_query($arg);
		$sig[] = 'accept: application/json';
		$sig[] = 'content-type: application/json';
		$sig[] = 'host: p2p.openthc.dev';

		$req_hash = hash('sha256', implode("\n", $sig));

		$sig_data = [];
		$sig_data[] = 'openthc-hmac-sha256';
		$sig_data[] = $arg['timestamp'];
		$sig_data[] = $req_hash;
		$sig_text = implode("\n", $sig_data);
		$sig_hash = hash('sha256', $sig_text);

		$sig_hmac = hash_hmac('sha256', $sig_text, $client_secret);

		// $req_hash = hash('sha256', $req_hash0 . $req_hash1 . $req_hash2);
		$arg['signature'] = $sig_hmac;

		$url = $url . '?' . http_build_query($arg);
		// Headers?
		$res = $this->ghc->get($url, [
			'headers' => [
				'openthc-signature' => $sig_hmac,
			]
		]);
		$res = $this->assertValidResponse($res);

		echo "url: $url\n";

	}

}
