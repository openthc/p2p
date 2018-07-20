<?php
/**
	Front Controller for OpenTHC P2P
*/

use Slim\App;
use Slim\Container;
use App\Network;

require_once(dirname(dirname(__FILE__)) . '/boot.php');

// See Below
$app = _new_slim_app();

// Network Interface - Info, List, Peer, Ping
$app->group('/network', function() {

	// $this is the App

	// Request to view Peer List
	$this->get('', function($REQ, $RES, $ARG) {

		$host_list = Network::listPeers();

		$RES = $RES->withJSON($host_list);

		return $RES;

	});

	$this->get('/info', 'App\Controller\Network\Info')
		->add('App\Middleware\Verify\Localhost')
		;

	// Request to join this Peer
	$this->post('/peer', 'App\Controller\Network\Peer')
		->add('App\Middleware\Verify\DNS')
		;

	// A Ping Responds with PONG, and some useful information
	$this->get('/ping', 'App\Controller\Network\Ping');

});

// Object Query Interface
$app->group('/object/{L0}', function() {

	$this->get('/lot/{GUID}', 'App\Example\Lot');
	$this->get('/product/{GUID}', 'App\Example\Product');
	$this->get('/qa/{GUID}', 'App\Example\Product');
	$this->get('/strain/{GUID}', 'App\Example\Product');
	$this->get('/transfer/{GUID}', 'App\Example\Transfer');

})
//->add('App\Middleware\Auth\LicenseAsk')
//->add('App\Middleware\Auth\PeerID')
//->add('App\Middleware\Verify\Secret')
//->add('App\Middleware\Verify\HMAC')
;


// Trusted Host query /Search to search the network
$app->get('/search', 'App\Controller\Search')
	->add('App\Middleware\Verify\Localhost')
	->add('App\Middleware\Verify\Secret')
	;


/**
	These are various ideas of Middleware that could(should?) be added
*/
// Adding Concentric Rings of Middleware, Inner => Outer
//$app->add('App\Middleware\Verify\Peer_Service');
//$app->add('App\Middleware\Verify\Signature');
//$app->add('App\Middleware\Verify\DNS');
//$app->add('App\Middleware\Filter\RateLimit');
//$app->add('App\Middleware\Log\File');
//$app->add('App\Middleware\Log\HTTP');

$app->run();

exit(0);

/**
	Slim Loading Routine
*/
function _new_slim_app()
{
	// Create App Container
	$con = new Container(array(
		'debug' => false,
		'settings' => array(
			'addContentLengthHeader' => false,
			'determineRouteBeforeAppMiddleware' => true,
			'displayErrorDetails' => false,
			'db' => array(
				'filename' => '',
				'hostname' => '',
				'database' => '',
				'username' => '',
				'password' => '',
			),
		),
	));

	// 404 Handler
	$con['notFoundHandler'] = function($c) {
		return function ($REQ, $RES) {
			return $RES->withJSON(array(
				'status' => 'failure',
				'detail' => 'Not Found [HEC#404]',
			), 404);
		};
	};

	// 405 Handler
	$con['notAllowedHandler'] = function ($c) {
		return function ($REQ, $RES, $methods) use ($c) {
			return $c['response']
				->withStatus(405)
				->withHeader('Allow', implode(', ', $methods))
				->withJSON(array(
					'status' => 'failure',
					'detail' => 'Method Not Allowed [HEC#405]',
				));
		};
	};

	// 500 Handler
	$con['phpErrorHandler'] = function ($c) {
		return function ($REQ, $RES, $err) use ($c) {
			return $c['response']
				->withStatus(500)
				->withJSON(array(
					'status' => 'failure',
					'detail' => 'Server Error [HEC#500]',
				));
		};
	};

	//unset($con['errorHandler']);
	//unset($con['phpErrorHandler']);

	// Database Connection
	$con['db'] = function ($c) {

		$cfg = $c['settings']['db'];

		if (empty($cfg['filename']) && empty($cfg['hostname'])) {
			return null;
		}

		// MySQL - http://php.net/manual/en/ref.pdo-sqlsrv.connection.php
		// $dsn = sprintf('sqlsrv:Server=%s;Database=%s', $cfg['hostname'], $cfg['database']);

		// MySQL - http://php.net/manual/en/ref.pdo-mysql.connection.php
		// $dsn = sprintf('mysql:host=%s;dbname=%s', $cfg['hostname'], $cfg['database']);

		// PostgreSQL - http://php.net/manual/en/ref.pdo-pgsql.connection.php
		// $dsn = sprintf('pgsql:host=%s;dbname=%s', $cfg['hostname'], $cfg['database']);

		// Sqlite
		// $dsn = sprintf('sqlite:%s', $cfg['filename']);

		$pdo = new \PDO($dsn, $cfg['username'], $cfg['password']);

		$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

		return $pdo;
	};

	// Create App
	$app = new App($con);

	return $app;

}
