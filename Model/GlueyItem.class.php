<?php
	class GlueyItem {
		var $start_offset = 0;
		var $end_offset = 0;
		var $title = '';
		
		function HasChildren() {
			$a1 = $this->end_offset - $this->start_offset - 1;
			$a2 = strlen($this->title);
			if ( $a1 == $a2 ) {
				return false;
			}
			
			return true;
		}
	}
?>