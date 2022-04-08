<?php 
	global $Utils, $Strings;
	$GLOBALS['Utils']->Trace('View::Login: Showing login view'); 
?>
<div id="loginPanel" style="text-align: center;">
	<div id="logo">
		<a href="<?= $Utils->CreateLink('About','Welcome'); ?>"><img src="<?= INSTALL_PATH ?>/View/Style/Images/GlueyNotes.jpg" class="smalllogo" alt="GlueyNotes" /></a></div>
	
	<form id="login" method="post" action="<?= $Utils->CreateLink('Session','DoLogin'); ?>"
		err="<?= $Strings->Get('error.formfillout') ?>"
		onsubmit="return validateCompleteForm(this);">
		<?php $Utils->ShowView('Tiles/Errors') ?>
		<div class="formholder">
			
			<div><?= $Strings->Get('label.username') ?>:</div>
			<input id="uname" type="text" value="" name="username" minlength="3" maxlength="10"
				required="1" realname="<?= $Strings->Get('label.username') ?>"
				onkeyup="this.value = this.value.toString().replace(/[^0-9a-z]+/gi,'');" />
			<div><?= $Strings->Get('label.password') ?>:</div>
			<input type="password" value="" name="password" minlength="3" maxlength="10" required="1"
				realname="<?= $Strings->Get('label.password') ?>"
			/>
			
			<div>
			<input type="submit" value="<?= $Strings->Get('label.login') ?>" />
			</div>
			
			<script type="text/javascript" charset="utf-8">
				document.getElementById('uname').focus();
			</script>
		</div>
	</form>
</div>
