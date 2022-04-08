<?php 
	global $Utils, $Strings;
	$GLOBALS['Utils']->Trace('View::FullView: Showing full view'); 
?>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width = 320;"/>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=yes" />
	<title><?= $GLOBALS['DISPLAY']['PAGE.TITLE'] ?></title>
	<link media="screen" type="text/css" rel="stylesheet" href="<?= INSTALL_PATH ?>/View/Style/MasterReset.css"  />
	
	<script type="text/javascript" charset="utf-8" src="<?= INSTALL_PATH ?>/View/Javascript/Sortie.js"></script>
</head>
<body>
	<?php $Utils->ShowView($GLOBALS['DISPLAY']['PATH.MAIN.VIEW']) ?>
		
	<?php if ( APP_STATE == 'production' ) { ?>
	<!-- google analytics -->
	<?php } ?>
</body>
</html>
