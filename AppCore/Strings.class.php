<?php 
	/**
	 * File: AppCore/Strings.class.php
	 * 	Used to do i18n
	 *
	 * Copyright:
	 * 	2007 robrohan (rob.rohan@yahoo.com)
	 */
	
	/**
	 * Class: ImplStrings
	 * 	This class is used to get displayable string information
	 * based on locale. If you don't care about internationalizing
	 * your application you can ignore this class.
	 */
	class ImplStrings {
		
		function ImplStrings() {
			//use the application settings to load the proper
			//language file
			$app_lang = $GLOBALS['APP_LANG'];
			$pref_lang = '';
			
			if(!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
				$pref_lang = $this->GetPreferredLanguage($_SERVER['HTTP_ACCEPT_LANGUAGE']);
				
				//if they have a preference, and the file exists, use it
				if( !empty($pref_lang) ) {					
					if( file_exists(SERVER_INSTALL_PATH . '/Resources/Strings_' . $pref_lang . FILE_EXT) ) {
						$app_lang = $pref_lang;
					}		
				}
			}
			
			include_once(
				SERVER_INSTALL_PATH . '/Resources/Strings' 
				. ( (empty($app_lang)) ? '' : "_$app_lang" ) . FILE_EXT
			);
		}
		
		/**
		 * Function GetPreferredLanguage
		 *  Looks at what the browser sends for preferred language and tries
		 * to get the first item out. Note this currently ignores other
		 * selections. For example, if they have zh_cn,en_gb,en_us this will
		 * only return zh_cn. If that language is not supported, the application
		 * will use the default applications language.
		 */
		function GetPreferredLanguage($accept_lang) {
			$lang_weight = explode(';', $accept_lang);
			
			if( empty($lang_weight) ) {
				return "";
			}
		
			$good_langs = explode(',', $lang_weight[0]);
			
			return strtolower($good_langs[0]);
		}
		
		
		/**
		 * Function: Get
		 * 	Gets an i18n string. The list of strings available are
		 * defined in Resources/Strings___.php
		 * 
		 * Parameters:
		 * 	key - the key to the string you'd like
		 *  items - an array of parameters to apply to the string
		 * 	default - if the key can not be found, use this instead
		 *
		 * Returns:
		 * 	Formatted string for display, or a place holder string if not found
		 */
		function Get($key, $items = array(''), $default='') {	
			if ( empty($GLOBALS['APPLICATION_STRINGS'][$key]) ) {
				$default = ( (empty($default)) ? '[['.$key.']]' : $default );
				return vsprintf($default, $items);
			} else {
				return vsprintf($GLOBALS['APPLICATION_STRINGS'][$key], $items);
			}
		}
	}

	/**
	 * Variable: Strings
	 * 	This is the implementation of ImplStrings one should use while using the
	 * framework.
	 * 
	 * SeeAlso:
	 *  <ImplStrings>
	 *
	 * Global:
	 *  true
	 */
	$Strings = new ImplStrings();
?>
