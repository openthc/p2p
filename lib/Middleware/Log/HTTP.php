<?php
/**
	Log Request and Response
*/

namespace App\Middleware\Log;

class HTTP
{
	public function __invoke($REQ, $RES, $NMW)
	{
		$ym = strftime('%Y%m', $_SERVER['REQUEST_TIME']);
		$tab = 'log_http';

		$dbf = sprintf('%s/var/request-log-%s.db', APP_ROOT, $ym);
		$pdo = new \PDO(sprintf('sqlite:', APP_ROOT));
		$pdo->query(sprintf('CREATE TABLE %s (id INTEGER NOT NULL PRIMARY KEY, ts INTEGER, ip TEXT, uid TEXT, req TEXT, res TEXT)', $tab));

		// Insert Requested Data
		$req = json_encode(array(
			'_GET' => $_GET,
			'_POST' => $_POST,
			'_COOKIE' => $_COOKIE,
			'_SESSION' => $_SESSION,
			'_SERVER' => $_SERVER,
		));

		$sql = "INSERT INTO $tab (ts, ip, req) VALUES (?, ?, ?)";
		$ins = $pdo->prepare($sql);
		$ins->execute(array(
			$_SERVER['REQUEST_TIME'],
			$_SERVER['REMOTE_ADDR'],
			$req,
		));

		$lid = $pdo->lastInsertId();


		// Next Middleware
		$RES = $NMW($REQ, $RES);


		// Log Response
		$res = json_encode(array(
			'_HEAD' => $RES->getHeaders(),
			'_BODY' => $RES->getBody()->__toString(),
		));
		//file_put_contents($file, $data);

		$sql = "UPDATE $tab SET res = ? WHERE id = ?";
		$ins = $pdo->prepare($sql);
		$ins->execute(array($res, $lid));

		return $RES;

	}
}
