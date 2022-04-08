<?php global $Strings; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?= $Strings->Get('glueynotes') ?></title>
	<meta name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
	<style type="text/css" media="screen">@import "<?= INSTALL_PATH ?>/View/Style/iPhoneNav.css";</style>
	<script type="application/x-javascript" src="<?= INSTALL_PATH ?>/View/Javascript/Sortie.js"></script>
</head>

<body id="body">
	<h1>&nbsp;</h1>
	<a class="button button_pos_far_left" id="homeButton" href="javascript:showPanel('panel1',true);"><?= $Strings->Get('label.todo') ?></a>
	<a class="button button_pos_far_right" href="<?= $GLOBALS['Utils']->CreateLink('Session','ShowEditor'); ?>"><?= $Strings->Get('label.edit') ?></a>
	<a class="button button_pos_center" href="javascript:showPanel('searchForm',false)"><?= $Strings->Get('label.context') ?></a>
	<h2 id="pageTitle"></h2>
	<!-- //////////////////////////////////////////////////////////////////// -->
	
	<ul id="panel1" title="<?= $Strings->Get('label.todo_items') ?>">
	</ul>
	
	<ul id="panel2" title="Loading..." selected="true"><div style="text-align: center; margin-top: 100px;"><img src='/View/Style/Images/busy.gif' alt="loading..."></div></ul>
	
	<!-- //////////////////////////////////////////////////////////////////// -->
	<div id="editor" class="panel" title="<?= $Strings->Get('label.editor') ?>">
		<form id="fileForm" action="javascript:void(0)">
		<textarea id="list_editor" style="width: 94%; height: 140px; font-size: 17px;"></textarea>
		<div id="feedback_div" style="width: 100%;"></div>
		<input type="button" value="<?= $Strings->Get('label.save') ?>"
			onclick="saveFile()">
		<input type="button" value="<?= $Strings->Get('label.done') ?>"
			onclick="showPanel('panel1',true)">
		</form>
	</div>
	
	<div id="itemInfo" class="panel" title="">
		<div id="itemInfoDisplay">
		</div>
	</div>
	
	<ul id="contextResults" title="<?= $Strings->Get('label.context') ?>">
	</ul>
	
	<form id="searchForm" class="dialog" action="javascript:searchContext( document.getElementById('contextSelector').value)">
	<fieldset>
		<h1><?= $Strings->Get('label.choose_context') ?></h1>
		<a class="button toolButton goButton button_pos_far_right" 
			href="javascript:document.getElementById('searchForm').submit()"><?= $Strings->Get('label.search') ?></a>
		
		<div style="text-align: left; margin-top: 3px;"><span class="contextSelectorLabel"><?= $Strings->Get('label.context') ?>:</span>
		<select id="contextSelector">
			<?php foreach( $GLOBALS['DISPLAY']['STARTUP_CONTEXTS'] as $contexts) { ?>
				<option value="<?= trim($contexts) ?>"><?= trim($contexts) ?></option>
			<? } ?>
		</select>
		</div>
	</fieldset>
	</form>
	
	<script type="text/javascript" charset="utf-8" src="<?= INSTALL_PATH ?>/View/Javascript/GlueyNotes.js"></script>
	<script type="text/javascript" charset="utf-8">Sortie.Core.Include();</script>
	<script type="text/javascript" charset="utf-8">
		showPage(document.getElementById('panel2'));
		runOrientorIfNeeded();
		setTimeout(scrollTo, 0, 0, 1);
		
		document.getElementById("homeButton").innerHTML = GlueyNotes.selected_list;
		showPanel('panel1');
	</script>
	
	<?php if ( APP_STATE == 'production' ) { ?>
	<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
	<script type="text/javascript">
	_uacct = "UA-547266-5";
	urchinTracker();
	</script>
	<?php } ?>
</body>
</html>
