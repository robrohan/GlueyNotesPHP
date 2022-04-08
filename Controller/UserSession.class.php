<?php
	include_once('Model/Users.class.php');
	
	class UserSession {
		
		function VerifyLogin($key='') {
			$user = new Users();
			$id = $user->GetLoggedInID($key);
			$user_info = '';
			
			if ( empty($id) ) {
				$GLOBALS['Utils']->JumpTo('Session', 'Login');
			} else {
				$user_info = $user->GetUser($id);
			}
			
			return $user_info;
		}
		
	}
?>