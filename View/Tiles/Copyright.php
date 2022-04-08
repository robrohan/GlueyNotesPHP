<?php
	global $Strings;
?>
<div class="copyholder">
Copyright &copy; <?= date('Y') ?> <a href="http://robrohan.com">Rob Rohan</a>
<br/>
<div class="small"><?= $Strings->Get('label.version') ?>: <?php include(SERVER_INSTALL_PATH . "/version.txt") ?></div>
</div>
