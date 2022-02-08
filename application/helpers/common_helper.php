<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



if(! function_exists('response')){

	function response($key = '', $msg = '', $data = ''){

		$is_sandbox = "";
		if(ENV=='development') 
		{
			$is_sandbox = 'DEVELOPMENT';	
		}
		else 
		{
			$is_sandbox = 'LIVE';
		}

		$res = [
            'statuscode' => 'ERR',
			'status'     => 'Unknown Error',
			'data'		 => '',
			'timestamp'  => date('Y-m-d H:i:s'),
			'environment'=> $is_sandbox,
		];
		
		
		$error_code = [
			"TXN"=>"Transaction Successful",
		];

		if(array_key_exists($key,$error_code))
		{
			$res['statuscode'] 	= $key;
			$res['status']		= $error_code[$key];
		}

		if ($msg != '') {
            $res['status'] = $msg;
        }

        $res['data'] = $data;

		die(json_encode($res));
	}
}



?>