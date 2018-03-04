<?php
/**
	Join a Peer to Peer Network
*/

$host = $argv[2];
if (empty($host)) {
	echo "peer [host]\n";
	exit(1);
}

echo "Attempting to join: $host\n";

$client = new \GuzzleHttp\Client();
try {
	$res = $client->request('POST', "https://$host/network/peer");
} catch (Exception $e) {
	$res = $e->getResponse();
}

printf("Response Code: %03d\n", $res->getStatusCode());
printf("Media Type: %s\n", $res->getHeaderLine('content-type'));
printf("Body:\n%s\n###\n", $res->getBody());

exit(0);
