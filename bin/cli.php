#!/usr/bin/php
<?php
/**
	Command Line Bootstrapper
*/
namespace App\bin;

require_once(dirname(dirname(__FILE__)) . '/boot.php');

$action = $argv[1];
$action = preg_replace('/[^\w\-]+/', null, $action);

$action_file = sprintf('%s/lib/cli/%s.php', APP_ROOT, $action);

if (is_file($action_file)) {
	require_once($action_file);
} else {
	echo "Command not found\n";
	exit(1);
}

exit(0);
