<?php
/**
	Join a Peer to Peer Network
*/

$host = $argv[2];

echo "Attempting to join: $host\n";

$client = new \GuzzleHttp\Client();
$res = $client->request('POST', "https://$host/network/peer");
echo $res->getStatusCode();
echo "\n";
// 200
echo $res->getHeaderLine('content-type');
echo "\n";
// 'application/json; charset=utf8'
echo $res->getBody();
echo "\n";
// '{"id": 1420053, "name": "guzzle", ...}'

// Send an asynchronous request.
//$request = new \GuzzleHttp\Psr7\Request('GET', 'http://httpbin.org');
//$promise = $client->sendAsync($request)->then(function ($response) {
//    echo 'I completed! ' . $response->getBody();
//});
//$promise->wait();

