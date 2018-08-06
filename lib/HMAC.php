<?php
/**
	Generate the HMAC for Signing P2P Requests

	@see https://docs.aws.amazon.com/AmazonS3/latest/dev/RESTAuthentication.html
*/

namespace App;

class HMAC
{
	public $_data;

	function __construct($key)
	{
		$this->_key = $key;
	}

	function CanonicalizeQueryString()
	{
		$a = $this->_data['ARGS'];
		if (!is_array($a)) {
			parse_str($a, $x);
			$a = $x;
		}

		ksort($a);

		return http_build_query($a);
	}

	function CanonicalizeRequest()
	{
		$this->_data['ARGS_SORTED'] = $this->CanonicalizeQueryString();

		$r = array();
		$r[] = $this->_data['VERB'];
		$r[] = $this->_data['PATH'];
		$r[] = $this->_data['ARGS_SORTED'];
		$r = implode("\n", $r);
		$r = trim($r);

		return $r;

	}

	function sign()
	{
		$r = $this->canonicalize_request();
		$s = hash_hmac('sha256', $r, $this->_key);
		//HASH=hash_hmac('sha256', $data, $secret);
		return $s;
	}

	private function _generate_http_head()
	{
	}

}
