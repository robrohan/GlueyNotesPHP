<?php
	include_once('Model/Users.class.php');
	include_once('Model/GlueyList.class.php');
	
	include_once('Controller/UserSession.class.php');
	include_once('Controller/HTTPRequest.class.php');
	
	define('ERR_CODE_CONFLICT', 409);
	
	class RemoteError {
		function RemoteError() {
			$this->type = 'error';
			$this->code = 0;
			$this->text = 'error';
			$this->payload = '';
		}
	}
	
	class Remote extends UserSession {
		
		/**
		 * GetSection
		 * the json function to get a section
		 *
		 * 	var position = new Object();
		 * 	position.level = level;
		 * 	position.start_offset = start_offset;
		 * 	position.end_offset = end_offset;
		 */
		function GetSection() {
			$http_req = new HttpRequest();
			$phpobj = json_decode($http_req->body);
			
			$section_list = $this->__GetSection($phpobj);
			
			//write out a JSON encoded array
			$GLOBALS['JSON.RESPONSE'] = json_encode($section_list);
			//use the JSON template (minimal with headers set)
			$GLOBALS['VIEW'] = 'JSON';
			//we also need to make sure debug is off because this is a 
			//raw transmission, so force off debug
			$GLOBALS['APP_DEBUG'] = false;
			//$gluey_list->Close();
		}
		
		/** 
		 * Function: __GetSection
		 * Gets an array of todo items within a section
		 */
		function __GetSection($phpobj, $key='') {
			$user_info = $this->VerifyLogin($key);
			
			if(empty($phpobj->list)) {
				$phpobj->list = 'todo';
			}
			$phpobj->name = $this->__CleanFileName($phpobj->list);
			
			$file_name = $this->__BuildFileName($user_info[0]['user_name'],  $phpobj->name);
			/* $gluey_list = new GlueyList(
				SERVER_INSTALL_PATH . '/AppData/Users/' . $user_info[0]['user_name'] . '/todo.txt'
			); */
			$gluey_list = new GlueyList($file_name);
			
			$section_list = $gluey_list->GetTodoItems(
				$phpobj->level,
				$phpobj->start_offset,
				$phpobj->end_offset
			);
			
			return $section_list;
		}
		
		/**
		 * 	var ctext = new Object();
		 * 	ctext.context = context;
		 */
		function GetContext() {
			$http_req = new HttpRequest();
			$phpobj = json_decode($http_req->body);
			
			$GLOBALS['JSON.RESPONSE'] = json_encode($this->__GetContext($phpobj) );
			$GLOBALS['VIEW'] = 'JSON';
			$GLOBALS['APP_DEBUG'] = false;
		}
		
		function __GetContext($phpobj, $key='') {
			$user_info = $this->VerifyLogin($key);
			
			$phpobj->name = $this->__CleanFileName($phpobj->list);
			
			$file_name = $this->__BuildFileName($user_info[0]['user_name'],  $phpobj->name);
			//$file_hash = sha1_file($file_name);
			
			$gluey_list = new GlueyList($file_name);
			
			return $gluey_list->GetItemByContext($phpobj->context);
		}
		
		/**
		 * obj->name
		 * obj->start_offset
		 * obj->end_offset
		 */
		function GetText() {
			$http_req = new HttpRequest();
			$phpobj = json_decode($http_req->body);
			
			$GLOBALS['JSON.RESPONSE'] = json_encode($this->__GetText($phpobj));
			
			$GLOBALS['VIEW'] = 'JSON';
			$GLOBALS['APP_DEBUG'] = false;
			//$gluey_list->Close();
		}
		
		function __GetText($phpobj, $key='') {
			$user_info = $this->VerifyLogin($key);
			
			$phpobj->name = $this->__CleanFileName($phpobj->list);
			
			$file_name = $this->__BuildFileName($user_info[0]['user_name'],  $phpobj->name);
			$file_hash = sha1_file($file_name);
			
			$gluey_list = new GlueyList($file_name);
			
			$rtn = $gluey_list->GetItemText(
				$phpobj->start_offset, $phpobj->end_offset
			);
			
			return $rtn;
		}
		
		function GetFile() {
			$http_req = new HttpRequest();
			$phpobj = json_decode($http_req->body);
			
			$GLOBALS['JSON.RESPONSE'] = json_encode($this->__GetFile($phpobj));
			
			$GLOBALS['VIEW'] = 'JSON';
			$GLOBALS['APP_DEBUG'] = false;
		}
		
		/**
		 *
		 * Returns:
		 * 		obj->name
		 * 		obj->contents
		 * 		obj->hash
		 */
		function __GetFile($phpobj, $key='') {
			$file_meta = false;
			
			$user_info = $this->VerifyLogin($key);
			
			$phpobj->name = $this->__CleanFileName($phpobj->list);
			
			$file_name = $this->__BuildFileName($user_info[0]['user_name'],  $phpobj->name);
			$file_hash = sha1_file($file_name);
			
			$fh = fopen($file_name, 'r');
			
			if ($fh) {
				$file_data = fread($fh, filesize($file_name));
				
				$file_meta = '';
				$file_meta->list = $phpobj->name;
				$file_meta->contents = $file_data;
				$file_meta->hash = $file_hash;
			}
			fclose($fh);
			
			return $file_meta;
		}
		
		function SaveFile() {
			global $Strings;
			
			$http_req = new HttpRequest();
			$phpobj = json_decode($http_req->body);
			
			$save_rtn = $this->__SaveFile($phpobj);
			
			if ($save_rtn == false) {
				//error, the file was changed between our last save and now
				//(probably...)
				$error = new RemoteError();
				$error->text = $Strings->Get('error.409');
				$error->code = ERR_CODE_CONFLICT;
				
				$GLOBALS['JSON.RESPONSE'] = json_encode($error);
			} else {
				$GLOBALS['JSON.RESPONSE'] = json_encode($save_rtn);
			}
			
			$GLOBALS['VIEW'] = 'JSON';
			$GLOBALS['APP_DEBUG'] = false;
		}
		
		function __SaveFile($phpobj, $key='') {
			$file_meta = false;
			
			$user_info = $this->VerifyLogin($key);
			
			$phpobj->name = $this->__CleanFileName($phpobj->list);
			$file_name = $this->__BuildFileName($user_info[0]['user_name'],  $phpobj->name);
			
			//file edit
			if(file_exists($file_name)) {
				$file_hash = sha1_file($file_name);
				
				//if the sent hash is the same as the hash of the file
				//on disk then save the file. If it is not it was edited
				//by another process, and we don't want to kill a possible
				//change
				if ( $phpobj->hash == $file_hash ) {
					$fh = fopen($file_name, 'w');
					if ($fh) {
						fwrite($fh, $phpobj->contents);
						fclose($fh);
						
						$file_meta = '';
						$file_meta->list = $phpobj->name;
						$file_meta->contents = '';
						$file_meta->hash = sha1_file($file_name);
					}
				}
			} else {
				//TODO create new files, need to link this to account type
				//new file
				$file_meta = '';
				$file_meta->list = '[[not allowed]]'; //$phpobj->name;
				//$file_meta->hash = sha1_file($file_name);
				$file_meta->contents = '';
				$file_meta->hash = -1;
			}
			
			return $file_meta;
		}
		
		function TickItem() {
			$http_req = new HttpRequest();
			$phpobj = json_decode($http_req->body);
			
			//write out a JSON encoded array
			$GLOBALS['JSON.RESPONSE'] = json_encode($this->__TickItem($phpobj));
			$GLOBALS['VIEW'] = 'JSON';
			$GLOBALS['APP_DEBUG'] = false;
			//$gluey_list->Close();
		}
		
		function __TickItem($phpobj, $key='') {
			$user_info = $this->VerifyLogin($key);
			
			//TODO remove this patch when the ajax client is fixed
			if (empty($phpobj->list)) {
				$phpobj->list = 'todo';
			}
			//TODO remove this patch when the ajax client is fixed
			$phpobj->name = $this->__CleanFileName($phpobj->list);
			
			$gluey_list = new GlueyList(
				SERVER_INSTALL_PATH . '/AppData/Users/' . $user_info[0]['user_name'] . '/' . $phpobj->name .'.txt'
			);
			$gluey_list->TickItem(
				$phpobj->start_offset,
				$phpobj->end_offset
			);
			
			return true;
		}
		
		/**
		 * Function: Ping
		 * Remote function to see if the server is working
		 * though the PHP (and for connection test)
		 *
		 * Paramaters:
		 *   obj->text
		 * 
		 * Returns:
		 *   obj->stuff
		 */
		function Ping() {
			$http_req = new HttpRequest();
			$phpobj = json_decode($http_req->body);
			
			$return_obj = $this->__Ping($phpobj);
			
			$GLOBALS['JSON.RESPONSE'] = json_encode($return_obj);
			$GLOBALS['VIEW'] = 'JSON';
			$GLOBALS['APP_DEBUG'] = false;
		}
		
		function __Ping($phpobj, $key='') {
			$return_obj = '';
			
			if ( empty($phpobj->text) ) {
				$return_obj->stuff = array("ping","pong","ping");
			} else {
				$return_obj->stuff = str_rot13($phpobj->text);
			}
			
			return $return_obj;
		}
		
		function __BuildFileName($client, $file) {
			return SERVER_INSTALL_PATH . '/AppData/Users/' . $client . '/' . $file . '.txt';
		}
		
		function __CleanFileName($file_name) {
			return str_replace( array('/','\\','..'), array('','',''), $file_name);
		}
	}
?>