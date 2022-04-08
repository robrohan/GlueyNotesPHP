<?php
	include_once('Model/Users.class.php');
	include_once('Model/GlueyList.class.php');
	include_once('Controller/UserSession.class.php');
	
	class Session extends UserSession {
		
		function iPhone() {
			$user_info = $this->VerifyLogin();
			
			if ( empty($user_info) ) {
				$GLOBALS['Utils']->AddError($Strings->Get('error.login'));
				$GLOBALS['DISPLAY']['PATH.MAIN.VIEW'] = 'Forms/Login';
			} else {
				$gluey_list = new GlueyList(
					SERVER_INSTALL_PATH . '/AppData/Users/' . $user_info[0]['user_name'] . '/todo.txt'
				);
				
				$GLOBALS['DISPLAY']['STARTUP_ITEMS'] = $gluey_list->GetTodoItems(1);
				$GLOBALS['DISPLAY']['STARTUP_CONTEXTS'] = $gluey_list->GetAllContexts();
				$GLOBALS['VIEW'] = 'iPhoneMain';
			}
		}
		
		function Login() {
			global $Strings;
			$GLOBALS['DISPLAY']['PAGE.TITLE'] = $Strings->Get('login.page.title');
			$GLOBALS['DISPLAY']['PAGE.META.DESCRIPTION'] = $Strings->Get('login.page.description');
			$GLOBALS['DISPLAY']['PAGE.META.KEYWORDS'] = $Strings->Get('login.page.keywords');
			
			$GLOBALS['DISPLAY']['PATH.MAIN.VIEW'] = 'Forms/Login';
		}
		
		function DoLogin() {
			global $Strings;
			$user = new Users();
			
			if ( $user->Login($_POST['username'], $_POST['password']) ) {
				$this->__LoginSwitch();
			} else {
				global $Strings;
				$GLOBALS['DISPLAY']['PAGE.TITLE'] = $Strings->Get('login.page.title');
				$GLOBALS['DISPLAY']['PAGE.META.DESCRIPTION'] = $Strings->Get('login.page.description');
				$GLOBALS['DISPLAY']['PAGE.META.KEYWORDS'] = $Strings->Get('login.page.keywords');
				
				$GLOBALS['Utils']->AddError($Strings->Get('error.login'));
				$GLOBALS['DISPLAY']['PATH.MAIN.VIEW'] = 'Forms/Login';
			}
		}
		
		function DoLogOut() {
			$user = new Users();
			$user->Logout();
			$GLOBALS['Utils']->JumpTo('Session', 'Login');
		}
		
		function __LoginSwitch() {
			$browser_style = '';
			if ( 
				preg_match('/iPhone/', $_SERVER['HTTP_USER_AGENT']) || 
				preg_match('/iPod/', $_SERVER['HTTP_USER_AGENT']) ||
				preg_match('/Android/', $_SERVER['HTTP_USER_AGENT']) 
			) {
				$browser_style = 'iphone';
			} else {
				$browser_style = 'browser';
			}
			
			switch($browser_style) {
				case 'iphone':
					$GLOBALS['Utils']->JumpTo('Session', 'iPhone');
				break;
				case 'browser':
					$GLOBALS['Utils']->JumpTo('Session', 'ShowEditor');
					//$GLOBALS['Utils']->JumpTo('Session', 'iPhone');
				break;
			}
		}
		
		function ShowEditor() {
			global $Strings;
			$user_info = $this->VerifyLogin();
			
			if ( !empty($user_info) ) {
				$GLOBALS['DISPLAY']['PAGE.TITLE'] = $Strings->Get('editor.page.title');
				$GLOBALS['DISPLAY']['PAGE.META.DESCRIPTION'] = $Strings->Get('editor.page.description');
				$GLOBALS['DISPLAY']['PAGE.META.KEYWORDS'] = $Strings->Get('editor.page.keywords');
				
				$GLOBALS['VIEW'] = 'FullView';
				$GLOBALS['DISPLAY']['PATH.MAIN.VIEW'] = 'Forms/ListEditor';
			} else {
				$GLOBALS['DISPLAY']['PAGE.TITLE'] = $Strings->Get('login.page.title');
				$GLOBALS['DISPLAY']['PAGE.META.DESCRIPTION'] = $Strings->Get('login.page.description');
				$GLOBALS['DISPLAY']['PAGE.META.KEYWORDS'] = $Strings->Get('login.page.keywords');
				
				$GLOBALS['Utils']->AddError($Strings->Get('error.login'));
				$GLOBALS['DISPLAY']['PATH.MAIN.VIEW'] = 'Forms/Login';
			}
		}
		
		function SignUp() {
			global $Strings;
			$GLOBALS['DISPLAY']['PAGE.TITLE'] = $Strings->Get('signup.page.title');
			$GLOBALS['DISPLAY']['PAGE.META.DESCRIPTION'] = $Strings->Get('signup.page.description');
			$GLOBALS['DISPLAY']['PAGE.META.KEYWORDS'] = $Strings->Get('signup.page.keywords');
			
			$GLOBALS['DISPLAY']['PATH.MAIN.VIEW'] = 'Forms/SignUp';
		}
		
		function DoSignUp() {
			global $Strings;
			
			$username = $_POST['username'];
			$password = $_POST['password'];
			$address = $_POST['email'];
			
			$user = new Users();
			$result = $user->SignUp($username, $password, $address);
			
			if ( empty($result) ) {
				$GLOBALS['Utils']->AddError($Strings->Get('error.signup'));
				$GLOBALS['DISPLAY']['PATH.MAIN.VIEW'] = 'Forms/SignUp';
			} else {
				$this->__LoginSwitch();
			}
		}
	}
?>
