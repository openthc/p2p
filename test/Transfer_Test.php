<?php
/**
 * Notes about the Auth module
 */

namespace Test;

class Transfer extends \Test\OpenTHC_Test_Case
{
	public function test_no_auth()
	{
		$res = $this->ghc->get('/transfer/WAJ123456.ABCDEFG');
		$res = $this->assertValidResponse($res, 403);
	}

	public function test_auth_header()
	{
		$res = $this->ghc->get('/transfer/WAG123456.TTABCDE?source=G123456&target=J234567', [
			'headers' => [
				'Authorization' => sprintf('bearer %s', $_ENV['api-test-secret-key'])
			]
		]);
		$res = $this->assertValidResponse($res, 200);
		$this->assertIsArray($res);
		$this->assertCount(2, $res);
		$this->assertIsArray($res['meta']);
		$this->assertIsArray($res['data']);

		$T = $res['data'];
		$this->assertNotEmpty($T['id']);
		// $this->assertNotEmpty($T['source']);
		// $this->assertNotEmpty($T['target']);
		$this->assertIsArray($T['item_list']);

	}

	public function test_auth_g2p()
	{
		$res = $this->ghc->get('/transfer/WAG123456.TTABCDE?source=G123456&target=J234567', [
			'headers' => [
				'Authorization' => sprintf('bearer %s', $_ENV['api-test-secret-key'])
			]
		]);
		$res = $this->assertValidResponse($res, 200);
		$this->assertIsArray($res);
		$this->assertCount(2, $res);
		$this->assertIsArray($res['meta']);
		$this->assertIsArray($res['data']);

		$T = $res['data'];
		$this->assertNotEmpty($T['id']);
		// $this->assertNotEmpty($T['source']);
		// $this->assertNotEmpty($T['target']);
		$this->assertIsArray($T['item_list']);

	}

	public function test_auth_p2r()
	{
		$res = $this->ghc->get('/transfer/WAJ234567.TTFGHIJ?source=J234567&target=R456789', [
			'headers' => [
				'Authorization' => sprintf('bearer %s', $_ENV['api-test-secret-key'])
			]
		]);
		$res = $this->assertValidResponse($res, 200);
		$this->assertIsArray($res);
		$this->assertCount(2, $res);
		$this->assertIsArray($res['meta']);
		$this->assertIsArray($res['data']);

		$T = $res['data'];
		$this->assertNotEmpty($T['id']);
		// $this->assertNotEmpty($T['source']);
		// $this->assertNotEmpty($T['target']);
		$this->assertIsArray($T['item_list']);

	}

}
