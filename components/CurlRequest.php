<?php

namespace app\components;

use Yii;
use app\components\OAuthUtil;


class CurlRequest {
	/** 
	 * Contains the last HTTP status code returned.  
	 * 
	 * @ignore 
	 */ 
	public $http_code;
	/** 
	 * Contains the last API call. 
	 * 
	 * @ignore 
	 */ 
	public $url;
	/** 
	 * Set up the API root URL. 
	 * 
	 * @ignore 
	 */ 
	public $host = 'http://i.api.weibo.com/2/';
	/** 
	 * Set timeout default. 
	 * 
	 * @ignore 
	 */ 
	public $timeout = 30;
	/**  
	 * Set connect timeout. 
	 * 
	 * @ignore 
	 */ 
	public $connecttimeout = 30; 
	/** 
	 * Verify SSL Cert. 
	 * 
	 * @ignore 
	 */ 
	public $ssl_verifypeer = FALSE;
	/** 
	 * Respons format. 
	 * 
	 * @ignore 
	 */ 
	public $format = 'json';
	/** 
	 * Decode returned json data. 
	 * 
	 * @ignore 
	 */ 
	public $decode_json = TRUE;
	/** 
	 * Contains the last HTTP headers returned. 
	 * 
	 * @ignore 
	 */ 
	public $http_info;
	/** 
	 * Set the useragnet. 
	 * 
	 * @ignore 
	 */ 
	public $useragent = 'Sae T OAuth v0.2.0-beta2';
	/* Immediately retry the API call if the response was not successful. */ 
	//public $retry = TRUE;
	

	private $cookie ;

	private $key = '2337075699';

	




	/** 
	 * construct WeiboOAuth object 
	 */ 
	function __construct(){
		$this->setWeiboCookie();
	}

	private function setWeiboCookie() {
		if(isset($_COOKIE['SUE']) && isset($_COOKIE['SUP'])){
			$this->cookie = "SUE=".urlencode($_COOKIE['SUE'])."; SUP=".urlencode($_COOKIE['SUP']).";";
		}
		else return false;
	}



	/** 
	 * GET wrappwer for oAuthRequest. 
	 * 
	 * @return mixed 
	 */ 
	function get($url, $parameters = array()){
		$response = $this->oAuthRequest($url, 'GET', $parameters);
		if ($this->format === 'json' && $this->decode_json){
			return json_decode($response, true);
		}
		return $response;
	}

	/** 
	 * POST wreapper for oAuthRequest. 
	 * 
	 * @return mixed 
	 */ 
	function post($url, $parameters = array(), $multi = false){
		
		$response = $this->oAuthRequest($url, 'POST', $parameters , $multi );
		if ($this->format === 'json' && $this->decode_json){
			return json_decode($response, true);
		}
		return $response;
	}

	/** 
	 * DELTE wrapper for oAuthReqeust. 
	 * 
	 * @return mixed 
	 */ 
	function delete($url, $parameters = array()){
		$response = $this->oAuthRequest($url, 'DELETE', $parameters);
		if ($this->format === 'json' && $this->decode_json){
			return json_decode($response, true);
		}
		return $response;
	}

	/** 
	 * Format and sign an OAuth / API request 
	 * 
	 * @return string 
	 */ 
	function oAuthRequest($url, $method, $parameters , $multi = false){

		if (strpos($url, 'http://')!== 0 && strrpos($url, 'http://')!== 0){
			$url = "{$this->host}{$url}.{$this->format}";
		}
		$defaults = array('source'		=> $this->key);

		$parameters = array_merge($defaults, $parameters);

		switch ($method){
		case 'GET': 
			//echo $request->to_url();
			return $this->http($this->to_url($parameters,$url), 'GET');
		default: 
			return $this->http($this->get_normalized_http_url($url), $method, $this->to_postdata($parameters,$multi), $multi );
		}
	}






//////////

	/** 
	 * parses the url and rebuilds it to be 
	 * scheme://host/path 
	 */ 
	public function get_normalized_http_url($http_url){
		$parts = parse_url($http_url);

		$port = @$parts['port'];
		$scheme = $parts['scheme'];
		$host = $parts['host'];
		$path = @$parts['path'];

		$port or $port = ($scheme == 'https')? '443' : '80';

		if (($scheme == 'https' && $port != '443')
			|| ($scheme == 'http' && $port != '80')){
				$host = "$host:$port";
			}
		return "$scheme://$host$path";
	}

	/** 
	 * builds a url usable for a GET request 
	 */ 
	public function to_url($parameters,$url){
		$post_data = $this->to_postdata($parameters);
		$out = $this->get_normalized_http_url($url);
		if ($post_data){
			$out .= '?'.$post_data;
		}
		return $out;
	}

	/** 
	 * builds the data one would send in a POST request 
	 */ 
	public function to_postdata($parameters, $multi = false ){
	if( $multi )
		return OAuthUtil::build_http_query_multi($parameters);
	else 
		return OAuthUtil::build_http_query($parameters);
	}
/////////



	/** 
	 * Make an HTTP request 
	 * 
	 * @return string API results 
	 */ 
	function http($url, $method, $postfields = NULL , $multi = false){
		$this->http_info = array();
		$ci = curl_init();
		/* Curl settings */ 
		curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
		curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);

		curl_setopt($ci, CURLOPT_COOKIE, $this->cookie);

		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);

		curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));

		curl_setopt($ci, CURLOPT_HEADER, FALSE);

		switch ($method){
		case 'POST': 
			curl_setopt($ci, CURLOPT_POST, TRUE);
			if (!empty($postfields)){
				curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
			}
			break;
		case 'DELETE': 
			curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
			if (!empty($postfields)){
				$url = "{$url}?{$postfields}";
			}
		}

		$header_array = array();
		
/*
		$header_array["FetchUrl"] = $url;
		$header_array['TimeStamp'] = date('Y-m-d H:i:s');
		$header_array['AccessKey'] = SAE_ACCESSKEY;


		$content="FetchUrl";

		$content.=$header_array["FetchUrl"];

		$content.="TimeStamp";

		$content.=$header_array['TimeStamp'];

		$content.="AccessKey";

		$content.=$header_array['AccessKey'];

		$header_array['Signature'] = base64_encode(hash_hmac('sha256',$content, SAE_SECRETKEY ,true));
*/
		//curl_setopt($ci, CURLOPT_URL, SAE_FETCHURL_SERVICE_ADDRESS );

		//print_r( $header_array );
		$header_array2=array();
		if( $multi )
			$header_array2 = array("Content-Type: multipart/form-data;boundary=" . OAuthUtil::$boundary , "Expect: ");
		foreach($header_array as $k => $v)
			array_push($header_array2,$k.': '.$v);

		curl_setopt($ci, CURLOPT_HTTPHEADER, $header_array2 );
		curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );

		//echo $url."<hr/>";

		curl_setopt($ci, CURLOPT_URL, $url);

		$response = curl_exec($ci);
		$this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
		$this->http_info = array_merge($this->http_info, curl_getinfo($ci));
		$this->url = $url;

		//echo '=====info====='."\r\n";
		//print_r( curl_getinfo($ci));
		
		//echo '=====$response====='."\r\n";
		//print_r( $response );

		curl_close ($ci);
		return $response;
	}

	/** 
	 * Get the header info to store. 
	 * 
	 * @return int 
	 */ 
	function getHeader($ch, $header){
		$i = strpos($header, ':');
		if (!empty($i)){
			$key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
			$value = trim(substr($header, $i + 2));
			$this->http_header[$key] = $value;
		}
		return strlen($header);
	}
}