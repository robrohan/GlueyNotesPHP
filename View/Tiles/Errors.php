<div class="error">
	<?php 
		if( $GLOBALS['Utils']->HasErrors() ) {
			foreach( $GLOBALS['ERRORS'] as $error ) {
				echo $error . "<br />";
			}
		}
	?>
</div>