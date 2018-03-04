<?php
/**
	Execute a Search from the Command Line
*/
namespace App\lib\cli;

use App\lib\Search;

if (empty($argv[4])) {
	echo "  search OBJECT LICENSE GUID\n";
	exit(1);
}

$S = new Search();
echo "_search({$argv[2]}, {$argv[3]}, {$argv[4]});\n";

$res = $S->_search_parallel($argv[2], $argv[3], $argv[4]);
// var_dump($res);
echo "Parallel Found " . count($res) . " Results\n";
foreach ($res as $rec) {
	print_r($rec);
}


$res = $S->_search_serial($argv[2], $argv[3], $argv[4]);
// var_dump($res);
echo "Serial Found " . count($res) . " Results\n";
foreach ($res as $rec) {
	print_r($rec);
}
