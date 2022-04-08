<?php 
	global $Utils, $Strings;
	$GLOBALS['Utils']->Trace('View::ListEditor: Showing Editor'); 
?>
<style type="text/css" media="screen">@import "<?= INSTALL_PATH ?>/View/Style/Editor.css";</style>

<form id="editorForm" action="javascript:saveFile();">
<div style="height: 100%;">
	<div class="clear toolbar">
		<a class="button" href="javascript:document.getElementById('editorForm').submit()" accesskey="s" onclick="this.blur();"><span><?= $Strings->Get('label.save') ?></span></a>
		<a class="button" href="/View/Help/index.html" accesskey="h" onclick="this.blur();" target="_blank"><span><?= $Strings->Get('label.help') ?></span></a>
		<a class="button" href="<?= $Utils->CreateLink('Session','DoLogOut'); ?>" onclick="this.blur();"><span><?= $Strings->Get('label.logout') ?></span></a>
		<div id="feedback_div">&nbsp;</div>
	</div>
	
	<div class="list_editor_holder">
		<textarea id="list_editor" cols="30" rows="5"></textarea>
	</div>
</div>
</form>


<script type="text/javascript" charset="utf-8" src="<?= INSTALL_PATH ?>/View/Javascript/Editor.js"></script>
<script type="text/javascript" charset="utf-8">Sortie.Core.Include();</script>

<script type="text/javascript" charset="utf-8">
	openFile();
	document.getElementById('list_editor').focus();
</script>
