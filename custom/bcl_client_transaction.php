<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * Class to get Bajaj capital client transaction report.
 */
class bcl_clientTransaction
{
	/*
	* Function to make a http request.
	* @param string $url,
	* @param array $post_fields,
	* @param array $headers
	*/
	private function create_request($url, $post_fields, $headers)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($curl);

		$curl_error = curl_error($curl);
		if($curl_error){
			echo $curl_error;
		}
		curl_close($curl);

		$data = json_decode($result);

		if($data->status == 'Success') {
			return $data;
		}
		
	}

	/*
	* Function to get Client token from CRM.
	* @response string (client token)
	*/
	public function getClientToken()
	{
		$token_url = "https://portfolio.wealthmaker.in/wmapi/WealthMakerAPIKit.svc/GenerateToken";

		$headers = ['Content-Type: application/json'];

		$post_fields = json_encode([
			"Source"   => "12",
			"UserId"   => "scrmuser",
			"Password" => "3pSw(cRm"
		]);
		
		$token_data = $this->create_request($token_url, $post_fields, $headers);
		
		return $token_data->Data[0]->Token;
	}
	/*
	* Function to get client transaction report data.
	* @response array.
	*/
	public function getClientTransactionReport()
	{
		// $client_token = "B7985C06444A53EFE055000000000001";
		$client_token = $this->getClientToken();

		$url = "https://portfolio.wealthmaker.in/wmapi/WealthMakerAPIKit.svc/ClientTransactionReport";
		
		$post_data = json_encode([
			"token" => $client_token,
			"client_code" => "40802547"
		]);

		$headers = ['Content-Type: application/json'];

		$request_data = $this->create_request($url, $post_data, $headers);
		// echo "<pre>";
		// echo print_r($request_data);
		if($request_data->status == "Success" && empty($request_data->error)) {
			return $request_data->Data;
		}
	}
}

$obj = new bcl_clientTransaction();
$client_transaction_report_data = $obj->getClientTransactionReport();