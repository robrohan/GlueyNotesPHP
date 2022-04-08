<?php 
	global $Utils, $Strings;
	$GLOBALS['Utils']->Trace('View::SignUp: Showing signup view'); 
?>
<div id="signupPanel" style="text-align: center;">
	<div id="logo">
		<a href="<?= $Utils->CreateLink('About','Welcome'); ?>"><img src="<?= INSTALL_PATH ?>/View/Style/Images/GlueyNotes.jpg" class="smalllogo"></a></div>
	<form name="login" method="post" action="<?= $Utils->CreateLink('Session','DoSignUp'); ?>"
		err="<?= $Strings->Get('error.formfillout') ?>"
		onsubmit="return validateCompleteForm(this);"
	>
		<?php $Utils->ShowView('Tiles/Errors') ?>
		<div class="formholder">
			
			<div><?= $Strings->Get('label.username') ?>:</div>
			<input id="username" type="text" value="" name="username" minlength="3" maxlength="10"
				required="1" realname="<?= $Strings->Get('label.username') ?>"
				onkeyup="this.value = this.value.toString().replace(/[^0-9a-z]+/gi,'');">
			<div><?= $Strings->Get('label.password') ?>:</div>
			<input type="password" value="" name="password" minlength="3" maxlength="10" required="1"
				realname="<?= $Strings->Get('label.password') ?>">
			<div><?= $Strings->Get('label.email') ?>:</div>
			<input type="text" value="" name="email" required="1"
				realname="<?= $Strings->Get('label.email') ?>" regexp="JSVAL_RX_EMAIL">
			
			<div style="margin-top: 5px;">
			<input type="checkbox" name="terms" required="1" value="1"
				realname="<?= $Strings->Get('form.terms') ?>"><?= $Strings->Get('form.agreeterms') ?>
			</div>
			
			<div id="termsofservice">
				<?php $Utils->ShowView('Tiles/Terms') ?>
			</div>
			
			<div style="margin-top: 5px;">
			<input type="submit" value="<?= $Strings->Get('label.signup') ?>">
			</div>
		</div>
	</form>
</div>
<script type="text/javascript" charset="utf-8">
	function toggleTerms() {
		var terms = document.getElementById('termsofservice');
		if(terms.style.visibility == 'visible') {
			terms.style.visibility = 'hidden';
			terms.style.display = 'none';
		} else {
			terms.style.visibility = 'visible';
			terms.style.display = 'block';
		}
	}

	document.getElementById('username').focus();
</script>
