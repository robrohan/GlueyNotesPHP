<?php
	
	class About {
		
		function Welcome() {
			global $Strings;
						
			$GLOBALS['DISPLAY']['PAGE.TITLE'] = $Strings->Get('main.page.title');
			$GLOBALS['DISPLAY']['PAGE.META.DESCRIPTION'] = $Strings->Get('main.page.description');
			$GLOBALS['DISPLAY']['PAGE.META.KEYWORDS'] = $Strings->Get('main.page.keywords');
			
			$GLOBALS['DISPLAY']['STRING.MAIN.TITLE'] = $Strings->Get('glueynotes');
			$GLOBALS['DISPLAY']['PATH.MAIN.VIEW'] = 'Tiles/About_GlueyNotes';
		}
		
		function Help() {
			global $Strings;
			
			$GLOBALS['DISPLAY']['PAGE.TITLE'] = $Strings->Get('main.page.title');
			$GLOBALS['DISPLAY']['PAGE.META.DESCRIPTION'] = $Strings->Get('main.page.description');
			$GLOBALS['DISPLAY']['PAGE.META.KEYWORDS'] = $Strings->Get('main.page.keywords');
			
			$GLOBALS['DISPLAY']['STRING.MAIN.TITLE'] = $Strings->Get('glueynotes');
			$GLOBALS['DISPLAY']['PATH.MAIN.VIEW'] = 'Help/HelpIndex';
		}
	}

?>
