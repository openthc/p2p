<?php
/**
	OpenTHC P2P Bootstrap
*/
namespace App;

require_once __DIR__.'vendor/autoload.php';

// Update to your own values
define('APP_NAME', 'OpenTHC P2P');
define('APP_SITE', 'https://p2p.openthc.org');
define('APP_ROOT', dirname(__FILE__));
define('APP_SALT', md5(APP_NAME . APP_SITE . APP_ROOT));

openlog('openthc-p2p', LOG_ODELAY|LOG_PID, LOG_LOCAL0);

error_reporting((E_ALL|E_STRICT) ^ E_NOTICE);

// My Cheap-Ass AutoLoader
spl_autoload_register(function($c) {
	$c = str_replace('_', '/', $c);
	$f = sprintf('%s/lib/%s.php', APP_ROOT, $c);
	if (is_file($f)) {
		require_once($f);
	}
}, true, false);

// Composer Stuff
$fva = APP_ROOT . '/vendor/autoload.php';
if (!is_file($fva)) {
	echo "You must run composer first\n";
	exit(1);
}
require_once($fva);

// You may want to tweak stuff here to bootstrap your own environment.
