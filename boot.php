<?php
/**
	OpenTHC P2P Bootstrap
*/

// Update to your own values
define('APP_HOST', 'p2p.openthc.org');
define('APP_NAME', 'OpenTHC P2P');
define('APP_ROOT', __DIR__);
define('APP_SITE', 'https://' . APP_HOST);
define('APP_SALT', md5(APP_HOST . APP_NAME . APP_ROOT . APP_SITE));

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

function _curl_init($url)
{
	$ch = curl_init($url);

	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

	// Booleans
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($ch, CURLOPT_COOKIESESSION, false);
	curl_setopt($ch, CURLOPT_CRLF, false);
	curl_setopt($ch, CURLOPT_FAILONERROR, false);
	curl_setopt($ch, CURLOPT_FILETIME, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
	//curl_setopt($ch, CURLOPT_FORBID_REUSE, false);
	//curl_setopt($ch, CURLOPT_FRESH_CONNECT, false);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_NETRC, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLINFO_HEADER_OUT,true);

	// curl_setopt($ch, CURLOPT_BUFFERSIZE, 16384);

	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
	// curl_setopt($ch, CURLOPT_SSLVERSION, 3); // 2, 3 or GnuTLS

	curl_setopt($ch, CURLOPT_MAXREDIRS, 0);

	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);

	curl_setopt($ch, CURLOPT_USERAGENT, 'OpenTHC/P2P v420.17.248');

	return $ch;

}
