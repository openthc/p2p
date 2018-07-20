<?php
/**
	Implements a very simiple rate limiter

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
	private $_rate_max = 3; // Events per TTL
	private $_rate_ttl = 1; // In Seconds

	public function __invoke($REQ, $RES, $NMW)
	{
		$redis = new Redis();
		$redis->connect('127.0.0.1', 6379);

		$key = sprintf('rate-limit:%s:%s', $_SERVER['REMOTE_ADDR'], $REQ->getAttribute('AuthToken'));

		$rate = $redis->incr($key);

		if ($rate == 1) {
			$redis->expire($key, $this->_rate_ttl);
		}
		if ($rate > $this->_rate_max) {
			return $RES->withJSON(array(
				'status' => 'failure',
				'detail' => 'Rate Limited',
				'result' => $rate,
			));
		}

		$RES = $NMW($REQ, $RES);

		return $RES;

	}

}
