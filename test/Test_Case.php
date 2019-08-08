<?php
/**
 * Base Test Case
 */

namespace Test;

class OpenTHC_Test_Case extends \PHPUnit\Framework\TestCase
{
	protected $ghc; // API Guzzle Client

	protected $_pid;
	protected $_tmp_file = '/tmp/test-data-pass.json';

	public function __construct($name = null, array $data = [], $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
		$this->_pid = getmypid();
	}

	protected function setUp() : void
	{
		$this->ghc = $this->_api();
	}


	/**
	 * Intends to become an assert wrapper for a bunch of common response checks
	 * @param $res, Response Object
	 * @return void
	 */
	function assertValidResponse($res, $code=200, $dump=null)
	{
		$this->raw = $res->getBody()->getContents();

		$hrc = $res->getStatusCode();

		switch ($hrc) {
		case 500:
			$dump = '500 Error Response';
			break;
		}

		if (!empty($dump)) {
			echo "\n<<< $dump <<< $hrc <<<\n{$this->raw}\n###\n";
		}

		$ret = \json_decode($this->raw, true);

		$this->assertEquals($code, $res->getStatusCode());
		$this->assertEquals('application/json', $res->getHeaderLine('content-type'));
		$this->assertIsArray($ret);

		// $this->assertIsArray($ret['meta']);
		// $this->assertIsArray($ret['data']);

		return $ret;

	}

	/**
	*/
	protected function _api()
	{
		// create our http client (Guzzle)
		$c = new \GuzzleHttp\Client(array(
			'debug' => $_ENV['debug-http'],
			'base_uri' => $_ENV['api-uri'],
			'allow_redirects' => false,
			'cookies' => true,
			'http_errors' => false,
			'request.options' => array(
				'exceptions' => false,
			),
		));

		return $c;
	}


	/**
	*/
	protected function _post($u, $a)
	{
		$res = $this->ghc->post($u, [ 'form_params' => $a ]);
		return $res;
	}


	/**
	*/
	protected function _post_json($u, $a)
	{
		$res = $this->ghc->post($u, [ 'json' => $a ]);
		return $res;
	}


	/**
	*/
	protected function _data_stash_get()
	{
		$x = file_get_contents($this->_tmp_file);
		$x = json_decode($x, true);
		return $x;
	}


	/**
	*/
	protected function _data_stash_put($d)
	{
		file_put_contents($this->_tmp_file, json_encode($d));
	}

}
