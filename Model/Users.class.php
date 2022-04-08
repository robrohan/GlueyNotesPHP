<?php 
	include_once('Model/DataBase/DataObject.class.php');
	
	define('MAX_SESSIONS', 5);
	
	class Users extends DataObject {
		var $COOKIE_KEY = "GLUEY";
		
		function GetUser($id=1) {
			//build a query
			$qry = '
				SELECT * 
				FROM users 
				WHERE id = ' . $this->CleanEntry($id);
			
			//Get an array of results (see SetQuery for Insert and Update and what
			//they return)
			$recordset = $this->GetQuery($qry);
			
			//this is optional and somwhat beta, what this does is take the results
			//of the recordset and attaches it to this instace of the object. So you
			//could access the database fields by doing $myinstance->my_db_column. You
			//by no meands have to do this and can just return the $recordset if you
			//wish.
			//$this->__ResultSetToAttributes($recordset);
			return $recordset;
		}
		
		/**
		 * Function: Login
		 * Attempts to login the user. If successful returns
		 * a result set with a bit of information about the 
		 * client - userid, session_key for example.
		 *
		 * Paramaters:
		 * 		user - the username
		 * 		pass - the password
		 *
		 * Returns:
		 * 		result
		 * 		result[id] - userid
		 * 		result[session_key] - session uuid
		 *
		 * or null if login fails
		 */
		function Login($user, $pass, $add_cookie=true) {
			$user = $this->CleanEntry($user);
			$pass = $this->CleanEntry($pass);
			
			$qry="
				SELECT id 
				FROM users 
				WHERE user_name = '$user'
				AND password = '$pass'
			";
			$results = $this->GetQuery($qry);
			
			if ( !empty($results) ) {
				//add the UUID tracking for our session
				$this->SetLoginCookie(&$results, $add_cookie);
			}
			
			return $results;
		}
		
		function Logout() {
			//TODO remove session record on logout
			/*$qry="
				DELETE FROM session
				WHERE uuid = '". $user_array[0]['id'] ."'
			";
			$results = $this->SetQuery($qry); */
			
			setcookie($this->COOKIE_KEY, "", (time()-60*60*24*30), '/');
		}
		
		/**
		 * Function: SetLoginCookie
		 * Sets the login UUID key for the session table. It
		 * sets a cookie, and in addition adds a column to the
		 * passed in user_array named "session_key"
		 */
		function SetLoginCookie(&$user_array, $add_cookie) {
			$session_uuid = $GLOBALS['Utils']->CreateUUID();
			
			//if there is a record in the session table remove it
			//updated by the new job that cleans out old session
			/* $qry="
				DELETE FROM session
				WHERE user_id = '". $user_array[0]['id'] ."'
			";
			$results = $this->SetQuery($qry); */
			
			//because they can login from several systems at the same time
			// (and logout is not a sure thing)
			//and the cleanup only happens once a month, there can be a lot
			//of dead sessions for a single user. this is a shot gun approach to
			//manage that. If they have more 5 sessions or more, kill them all
			//and start this new one.
			$qry="
				SELECT count(user_id) as count
				FROM session
				WHERE user_id = '". $user_array[0]['id'] ."'
			";
			$results = $this->GetQuery($qry);
			
			if ($results[0]['count'] >= MAX_SESSIONS) {
				$qry="
					DELETE FROM session
					WHERE user_id = '". $user_array[0]['id'] ."'
				";
				$results = $this->SetQuery($qry);
			}
			
			$qry="
				INSERT INTO session (
					uuid,
					user_id
				) VALUES (
					'$session_uuid',
					'".$user_array[0]['id']."'
				);
			";
			$results = $this->SetQuery($qry);
			
			//if ( !empty($results) ) {
				$user_array[0]['session_key'] = $session_uuid;
				//if called from a webservice, adding a cookie messes
				//up the headers for the request
				if($add_cookie) {
					setcookie($this->COOKIE_KEY, $session_uuid, (time()+60*60*24*30), '/');
				}
			//}
		}
		
		function GetLoggedInID($key='') {
			if (empty($key)) {
				if (!empty($_COOKIE[$this->COOKIE_KEY])) {
					$key = $_COOKIE[$this->COOKIE_KEY];
				}
			}
			
			//if the key didn't come from the web service or the
			//html version, no point in looking for it in the session
			//table
			if(!empty($key)) {
				$qry="
					SELECT user_id
					FROM session 
					WHERE uuid = '" . $this->CleanEntry($key) . "'
				";
				$results = $this->GetQuery($qry);
			
				if (!empty($results)) {
					return $results[0]['user_id'];
				}
			}
			
			return 0;
		}
		
		function IsLoggedIn() {
			$usr_qry = $this->GetLoggedInID();
			if ( !empty($usr_qry) ) {
				return true;
			}
			return false;
		}
		
		function SignUp($username, $password, $email) {
			$username = $this->CleanEntry($username);
			$password = $this->CleanEntry($password);
			$email = $this->CleanEntry($email);
			
			if( empty($username) || empty($password) || empty($email) ) {
				return false;
			}
			
			//see if this person already exists
			$qry="
				SELECT id 
				FROM users 
				WHERE user_name = '$username'
			";
			$result = $this->GetQuery($qry);
			
			if(empty($result)) {
				$qry="
					INSERT INTO users 
					SET user_name = '$username',
					password = '$password',
					email_address = '$email'
				";
				$result = $this->SetQuery($qry);
				
				//looks like the insert went ok, so now we
				//need to create the user's direcotry and add
				//the default list
				if ( !empty($result) ) {
					$this->SetLoginCookie($result);
					mkdir(SERVER_INSTALL_PATH . '/AppData/Users/' . $username, 0777);
					touch(SERVER_INSTALL_PATH . '/AppData/Users/' . $username . '/todo.txt');
					$fh = fopen(SERVER_INSTALL_PATH . '/AppData/Users/' . $username . '/todo.txt', 'w');
					fwrite($fh, "# Welcome to GlueyNotes\n* Add Tasks to my notes\n** Click Edit\nEdit is the button on the top right of the iPhone version of GlueyNotes\n** Type text :)");
					fclose($fh);
				}
				
				return $result;
			}
			
			return false;
		}
	}
?>