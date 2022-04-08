<?php 
	global $Utils, $Strings;
	$GLOBALS['Utils']->Trace('View::MainView: Showing main view'); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="verify-v1" content="ATiEybiSKx5vc4aFU2EglZ5l2DT0ohIzLclT8rjaghk=" />
	<meta name="viewport" content="width = 320;"/>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=yes" />
	<title><?= $GLOBALS['DISPLAY']['PAGE.TITLE'] ?></title>
	<meta name="description" content="<?= $GLOBALS['DISPLAY']['PAGE.META.DESCRIPTION'] ?>">
	<meta name="keywords" content="<?= $GLOBALS['DISPLAY']['PAGE.META.KEYWORDS'] ?>">
	
	<link media="screen" type="text/css" rel="stylesheet" href="<?= INSTALL_PATH ?>/View/Style/MasterReset.css"  />
	<link media="screen" type="text/css" rel="stylesheet"
		href="<?= INSTALL_PATH ?>/View/Style/Default.css"  />
	<link media="only screen and (max-device-width: 480px)" type="text/css" rel="stylesheet" 
		href="<?= INSTALL_PATH ?>/View/Style/Default-Small.css" />
	
	<script type="text/javascript" src="<?= INSTALL_PATH ?>/View/Javascript/Sortie.js"></script>
	<script type="text/javascript" src="<?= INSTALL_PATH ?>/View/Javascript/json.js"></script>
	<script type="text/javascript" src="<?= INSTALL_PATH ?>/View/Javascript/jsval.js"></script>
</head>
<body>
	<div id="contents">
		
		<div><?php $Utils->ShowView($GLOBALS['DISPLAY']['PATH.MAIN.VIEW']) ?></div>
		
		<div class="ad_area">
		<!--/* Kalator.com Javascript Tag v2.4.0 */-->

		<script type='text/javascript'><!--//<![CDATA[
		   var m3_u = (location.protocol=='https:'?'https://kalator.com/www/delivery/ajs.php':'http://kalator.com/www/delivery/ajs.php');
		   var m3_r = Math.floor(Math.random()*99999999999);
		   if (!document.MAX_used) document.MAX_used = ',';
		   document.write ("<scr"+"ipt type='text/javascript' src='"+m3_u);
		   document.write ("?zoneid=43");
		   document.write ('&amp;cb=' + m3_r);
		   if (document.MAX_used != ',') document.write ("&amp;exclude=" + document.MAX_used);
		   document.write ("&amp;loc=" + escape(window.location));
		   if (document.referrer) document.write ("&amp;referer=" + escape(document.referrer));
		   if (document.context) document.write ("&context=" + escape(document.context));
		   if (document.mmm_fo) document.write ("&amp;mmm_fo=1");
		   document.write ("'><\/scr"+"ipt>");
		//]]>--></script><noscript><a href='http://kalator.com/www/delivery/ck.php?n=ad8a7413' target='_blank'><img src='http://kalator.com/www/delivery/avw.php?zoneid=43&amp;n=ad8a7413' border='0' alt='' /></a></noscript>
		</div>
		
		<div><?php $Utils->ShowView('Tiles/Copyright') ?></div>
	</div>
	
	
	<?php if ( APP_STATE == 'production' ) { ?>
	<!-- google analytics here -->
	<?php } ?>
</body>
</html>
