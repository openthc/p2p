<?php
/**
	A Disabled Interface
*/
namespace App\lib\Disable;

class Disable_Disable
{
	function __invoke($REQ, $RES, $ARG)
	{
		return $RES->withJSON(array(
			'status' => 'failure',
			'detail' => 'Not Implemented',
		), 501);
	}
}
