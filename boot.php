<?php
/**
	OpenTHC P2P Bootstrap
*/

namespace App;

// Update to your own values
define('APP_NAME', 'OpenTHC P2P');
define('APP_SITE', 'https://p2p.openthc.org');
define('APP_ROOT', dirname(__FILE__));
define('APP_SALT', md5(APP_NAME . APP_SITE . APP_ROOT));

openlog('openthc-p2p', LOG_ODELAY|LOG_PID, LOG_LOCAL0);

error_reporting((E_ALL|E_STRICT) ^ E_NOTICE);

// Composer Stuff
$fva = APP_ROOT . '/vendor/autoload.php';
if (!is_file($fva)) {
	echo "You must run composer first\n";
	exit(1);
}
require_once($fva);

// You may want to tweak stuff here to bootstrap your own environment.
