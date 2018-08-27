#!/usr/bin/php
<?php
/**
	Command Line Bootstrapper
*/

namespace App\bin;

require_once(dirname(dirname(__FILE__)) . '/boot.php');

$action = $argv[1];
$action = preg_replace('/[^\w\-]+/', null, $action);
if (empty($action)) {
	_exit_show_available_actions("Provide a Command\n");
}

$action_file = sprintf('%s/lib/cli/%s.php', APP_ROOT, $action);

if (is_file($action_file)) {

	require_once($action_file);

} else {

	_exit_show_available_actions("Specified command '$action' not found\n");
}


exit(0);

function _exit_show_available_actions($msg=null)
{
	$file_glob = sprintf('%s/lib/cli/*php', APP_ROOT);
	$file_list = glob($file_glob);
	$act_list = array();
	foreach ($file_list as $f) {
		$a = basename($f, '.php');
		$act_list[] = $a;
	}
	sort($act_list);

	echo $msg;
	echo "Available Commands:\n  " . implode(', ', $act_list) . "\n";

	exit(1);

}
