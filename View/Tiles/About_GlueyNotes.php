<?php
	global $Utils, $Strings;
?>
<div style="text-align: center;">
<a href="<?= $Utils->CreateLink('Session','Login'); ?>"><img src="<?= INSTALL_PATH ?>/View/Style/Images/GlueyNotes.jpg" class="largelogo" alt="GlueyNotes" /></a>
</div>

<div style="width: 100%; height: 30px;">
	<div class="main_button_bar">
	<a class="button" href="<?= $Utils->CreateLink('Session','SignUp'); ?>"><span><?= $Strings->Get('label.signup') ?></span></a>
	<a class="button" href="http://smallturtle.com/forum/6"><span><?= $Strings->Get('label.forum') ?></span></a>
		<a class="button" href="/View/Help/index.html" target="_blank"><span><?= $Strings->Get('label.help') ?></span></a>
	<a class="button" href="<?= $Utils->CreateLink('Session','Login'); ?>"><span><?= $Strings->Get('label.login') ?></span></a>
	</div>
</div>

