<?php

class clsWebServices{
	
	function callWebService($url = NULL, $paramsString=NULL, $paramSecure=FALSE, $paramDecode=TRUE, $paramDebug=FALSE){
		if(empty($url)){
			$url = "";
		}
		$methodParamHeaders = json_encode(array(
		    'page' => 2,
		    'limit' => 50
		));
		if(!empty($paramsString)){
		$paramsData = explode(',', $paramsString);
		$params = array();
			foreach ($paramsData as $datum) {
				list($key, $value) = explode('=', $datum);
					$params[$key] = $value;
				}
			$methodParamHeaders = json_encode($params);
		}
		$authHeaders = json_encode(array(
		    'app_id' => 1,
		    'app_token' => '1234',
		    'session_token' => '_absdjwsef43ismk43efdker'
		));
		
		//$secure = (in_array('--secure', $argv));
		//$decode = (!in_array('--no-decode', $argv));
		//$debug = (in_array('--debug', $argv));
		
		$secure = $paramSecure;
		$decode = $paramDecode;
		$debug = $paramDebug;
		
		$data = ""; 
		
		$curlResource = curl_init(); 
		curl_setopt($curlResource, CURLOPT_URL, $url); 
		curl_setopt($curlResource, CURLOPT_PORT , 80); 
		curl_setopt($curlResource, CURLOPT_VERBOSE, 0); 
		curl_setopt($curlResource, CURLOPT_HEADER, 0); 
		curl_setopt($curlResource, CURLOPT_SSLVERSION, 3); 
		
		if ($secure) {
		    curl_setopt($curlResource, CURLOPT_SSLCERT, getcwd() . "/client.pem"); 
		    curl_setopt($curlResource, CURLOPT_SSLKEY, getcwd() . "/keyout.pem"); 
		    curl_setopt($curlResource, CURLOPT_CAINFO, getcwd() . "/ca.pem"); 
		    curl_setopt($curlResource, CURLOPT_POST, 1); 
		}
		
		curl_setopt($curlResource, CURLOPT_SSL_VERIFYPEER, 1); 
		curl_setopt($curlResource, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($curlResource, CURLOPT_POSTFIELDS, $data); 
		curl_setopt($curlResource, CURLOPT_HTTPHEADER, array(
		    "Content-Type: text/xml",
		    "ohrm_ws_auth_parameters: {$authHeaders}",
		    "ohrm_ws_method_parameters: {$methodParamHeaders}",
		    "Content-length: ". strlen($data),
		)); 
		
		$resultData = curl_exec($curlResource); 
		
		if ($debug) {
		    if(!curl_errno($curlResource)) { 
		        $info = curl_getinfo($curlResource); 
		        print_r($info);
		    } else { 
		        echo 'Curl error: ' . curl_error($curlResource), "\n"; 
		        $info = curl_getinfo($curlResource); 
		        print_r($info);
		    } 
		}
		
		curl_close($curlResource); 
		
		if ($decode) {
		    $resultData = json_decode($resultData);
		} else {
			
		}
		return $resultData;
	}
}