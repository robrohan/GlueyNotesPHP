<?php

/**
* Access the HTTP Request
*/
class HttpRequest {

	/** additional HTTP headers not prefixed with HTTP_ in $_SERVER superglobal */
	var $add_headers = array('CONTENT_TYPE', 'CONTENT_LENGTH');

	/**
	* Construtor
	* Retrieve HTTP Body
	* @param Array Additional Headers to retrieve
	*/
	function HttpRequest($add_headers = false) {
	
		$this->RetrieveHeaders($add_headers);
		$this->body = @file_get_contents('php://input');
	}
	
	/**
	* Retrieve the HTTP request headers from the $_SERVER superglobal
	* @param Array Additional Headers to retrieve
	*/
	function RetrieveHeaders($add_headers = false) {
		
		if ($add_headers) {
			$this->add_headers = array_merge($this->add_headers, $add_headers);
		}
	
		if (isset($_SERVER['HTTP_METHOD'])) {
			$this->method = $_SERVER['HTTP_METHOD'];
			unset($_SERVER['HTTP_METHOD']);
		} else {
			$this->method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : false;
		}
		$this->protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : false;
		$this->request_method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : false;
		
		$this->headers = array();
		foreach($_SERVER as $i=>$val) {
			if (strpos($i, 'HTTP_') === 0 || in_array($i, $this->add_headers)) {
				$name = str_replace(array('HTTP_', '_'), array('', '-'), $i);
				$this->headers[$name] = $val;
			}
		}
	}
	
	/** 
	* Retrieve HTTP Method
	*/
	function Method() {
		return $this->method;
	}
	
	/** 
	* Retrieve HTTP Body
	*/
	function Body() {
		return $this->body;
	}
	
	/** 
	* Retrieve an HTTP Header
	* @param string Case-Insensitive HTTP Header Name (eg: "User-Agent")
	*/
	function Header($name) {
		$name = strtoupper($name);
		return isset($this->headers[$name]) ? $this->headers[$name] : false;
	}
	
	/**
	* Retrieve all HTTP Headers 
	* @return array HTTP Headers
	*/
	function Headers() {
		return $this->headers;
	}
	
	/**
	* Return Raw HTTP Request (note: This is incomplete)
	* @param bool ReBuild the Raw HTTP Request
	*/
	function Raw($refresh = false) {
	
		if (isset($this->raw) && !$refresh) {
			return $this->raw; // return cached
		}
	
		$headers = $this->headers();
		$this->raw = "{$this->method}\r\n";
		
		foreach($headers as $i=>$header) {
				$this->raw .= "$i: $header\r\n";
		}
		
		$this->raw .= "\r\n{$http_request->body}";
		
		return $this->raw;
	}

}

/*
* Example Usage
* Echos the HTTP Request back to the client/browser
$http_request = new http_request();
$resp = $http_request->raw();
echo nl2br($resp);
*/
?>