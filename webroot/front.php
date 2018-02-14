<?php
/**
	Front Controller for OpenTHC P2P
*/

require_once(dirname(dirname(__FILE__)) . '/boot.php');

// See Below
$app = _new_slim_app();

// Network Info, List, Peer, Ping
$app->group('/network', function() {

	// $this is the App

	// Request to view Peer List
	$this->get('', function($REQ, $RES, $ARG) {

		$host_list = Network::listPeers();

		return $RES->withJSON($host_list);

	});

	// Request to join this Peer
	$this->post('/peer', 'Network_Peer');

	// A Ping Responds with PONG, and some useful information
	$this->get('/ping', 'Network_Ping');

});


// Query License Info
$app->group('/license/{license}', function() {

	// Maybe Add Middleware Here?
	// $this->add('Middleware_Filter_Something');

	// Set to this to disable that interface
	//$this->get('/lot/{guid}', 'Disable_Disable');

	// Share Lot Details
	//$this->get('/lot/{guid}', 'Example_Lot');

	// Share Product data
	//$this->get('/product/{guid}', 'Example_Product');

	// Share QA data
	//$this->get('/qa/{guid}', 'Example_QA');

	// Share Strain data
	//$this->get('/strain/{guid}', 'Example_Strain');

})
//->add('Middleware_Custom_Magic')
;

/**
	These are various ideas of Middleware that could(should?) be added
*/
// Adding Concentric Rings of Middleware, Inner => Outer
// $app->add('Middleware_Verify_Peer_Service');
// $app->add('Middleware_Verify_Signature');
// $app->add('Middleware_Verify_DNS');
// $app->add('Middleware_Filter_RateLimit');
// $app->add('Middleware_Log_HTTP');

$app->run();

function _new_slim_app()
{
	// Create App Container
	$con = new \Slim\Container(array(
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
				'detail' => 'Not Found',
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
					'detail' => 'Not Allowed',
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
					'detail' => 'Server Error',
				));
		};
	};

	//unset($con['errorHandler']);

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

		$pdo = new PDO($dsn, $cfg['username'], $cfg['password']);

		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

		return $pdo;
	};

	// Create App
	$app = new \Slim\App($con);

	return $app;

}
