<?php
	include_once('Controller/Remote.class.php');
	include_once('Model/Users.class.php');
	
	function GN_Login($username, $password) {
		$user = new Users();
		
		$result = $user->Login($username, $password, false);
		
		if (!empty($result)) {
			return $result[0]['session_key'];
		}
		
		return $result;
	}
	
	function GN_Ping($pingin) {
		$remote = new Remote();
		return $remote->__Ping($pingin);
	}
	
	function GN_GetSection($key, $position) {
		$remote = new Remote();
		return $remote->__GetSection($position, $key);
	}
	
	function GN_GetFileText($key, $section) {
		$remote = new Remote();
		return $remote->__GetText($section, $key);
	}
	
	function GN_GetFile($key, $section) {
		$remote = new Remote();
		return $remote->__GetFile($section, $key);
	}
	
	function GN_SaveFile($key, $file) {
		$remote = new Remote();
		return $remote->__SaveFile($file, $key);
	}
	
	function GN_TickItem($key, $section) {
		$remote = new Remote();
		return $remote->__TickItem($section, $key);
	}
	
	function GN_GetContext($key, $context) {
		$remote = new Remote();
		return $remote->__GetContext($context, $key);
	}

	///////////////////////////////////////////////////////////////////////
	
	class Service {
		
		function Serve() {
			if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
				
				ini_set("soap.wsdl_cache_enabled", "0"); // disabling WSDL cache 
				
				$server = new SoapServer("Controller/service.wsdl"); 
				$server->addFunction(
					array(
						"GN_Login",
						"GN_Ping",
						"GN_GetSection",
						"GN_GetFileText",
						"GN_GetFile",
						"GN_SaveFile",
						"GN_TickItem",
						"GN_GetContext"
					)
				);
				
				$server->handle();
				die();
			}
			
		}
		
		function wsdl() {
			header("Content-type: text/xml;charset=utf-8");
			echo file_get_contents("Controller/service.wsdl");
			
			exit;
		}
	}
?>