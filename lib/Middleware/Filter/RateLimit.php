<?php
/**
	Implements a very simiple rate limiter in Redis
	Rate Limits to ($MAX / $TTL)

	http://www.binpress.com/tutorial/introduction-to-rate-limiting-with-redis/155%20Introduction%20to%20rate%20limiting%20with%20Redis%20%5BPart%201%5D
	https://gist.github.com/chriso/54dd46b03155fcf555adccea822193da
	https://github.com/davedevelopment/stiphle
	https://github.com/beheh/flaps
	https://news.ycombinator.com/item?id=13997029
	https://collectiveidea.com/blog/archives/2012/11/30/down-boy-how-to-easily-throttle-requests-to-an-api-using-redis
*/

namespace App\Middleware\Filter;

class RateLimit
{
	private $_max = 3; // Events per TTL
	private $_ttl = 1; // In Seconds

	public function __invoke($REQ, $RES, $NMW)
	{
		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);

		$kp1 = $_SERVER['REMOTE_ADDR'];

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
			));
		}

		$RES = $NMW($REQ, $RES);

		return $RES;

	}

}
