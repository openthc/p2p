<?php
/**
 * Implements a very simiple rate limiter in Redis
 * Rate Limits to ($MAX / $TTL)
 * @see ./doc/RateLimit.md
 */

namespace App\Middleware\Filter;

class RateLimit
{
	private $_max = 4; // Events per TTL
	private $_ttl = 1; // In Seconds

	public function __invoke($REQ, $RES, $NMW)
	{
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);

		$kp1 = $_SERVER['REMOTE_ADDR'];

		// Three ways to get second key
		$kp2 = $REQ->getAttribute('AuthToken');
		if (empty($kp2)) {
			$kp2 = $REQ->getAttribute('API-Public-Key');
		}
		if (empty($kp2)) {
			$kp2 = $_GET['apipubkey'];
		}

		$key = sprintf('rate-limit:%s', sha1($kp1 . $kp2));

		$cur = $redis->incr($key);

		$RES = $RES->withHeader('X-Rate-Limit', sprintf('%d/%d/%d', $cur, $this->_max, $this->_ttl));

		if (1 == $cur) {
			$redis->expire($key, $this->_ttl);
		}

		if ($cur > $this->_max) {
			return $RES->withJSON(array(
				'status' => 'failure',
				'detail' => 'Rate Limited [MFR#048]',
			), 429);
		}

		$RES = $NMW($REQ, $RES);

		return $RES;

	}

}
