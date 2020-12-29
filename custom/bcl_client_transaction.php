<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * 
 */
class bcl_clientTransaction
{
	function __construct()
	{
		#..
	}

	public function create_request($url, $post_fields, $headers)
	{
		//$curl = curl_init($url);
		curl_setopt($curl, , CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		if(!empty($post_fields)) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
		}
		if(!empty($headers)) {
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		}

		$data = curl_exec($curl);
		
		curl_close($curl);

		$json_data = json_decode($data);

		if($json_data->status == 'Success') {

			return $json_data->Data;

		} else {
			return false;
		}
	}

	public function getClientToken()
	{
		$token_url = "https://portfolio.wealthmaker.in/wmapi/WealthMakerAPIKit.svc/GenerateToken";

		$headers = ['Content-Type' => 'application/json'];

		$post_fields = [
			"Source"   => "12",
			"UserId"   => "scrmuser",
			"Password" => "3pSw(cRm"
		];

		$request_data = $this->create_request($token_url, $post_fields, $headers);

		if($request_data != false ) {

			return $request_data[0]->Token;

		} else {

			$response = [
				"status" => "Fail",
				"message" => "Request not processed"
			]
			return json_encode($response);

		}
		
	}

	public function getClientTransactionReport()
	{
		$client_token = $this->getClientToken();

		$url = "https://portfolio.wealthmaker.in/wmapi/WealthMakerAPIKit.svc/ClientTransactionReport";

		$headers = ['Content-Type' => 'application/json'];

		$post_fields = [
			"token": $client_token,
    		"client_code":"40802547"
		];

		$request_data = $this->create_request($url, $post_fields, $headers);

		if($request_data != false ) {

			return $request_data;

		} else {

			$response = [
				"status" => "Fail",
				"message" => "Request not processed"
			]
			return json_encode($response);
		}
	}
}

$obj = new bcl_clientTransaction();
$client_transaction_report_data = $obj->getClientTransactionReport();