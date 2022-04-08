<?php
	include_once('Model/GlueyItem.class.php');
	
	define('REGEX_ITEM', '/^[\*X]/');
	define('REGEX_COMMENT', '/^[#]/');
	define('REGEX_CONTEXT', '/\s@[^\ |^\t]+/');
	
	define('REGEX_TODO_START', '/^[\*]{'); 
	/* level,level goes here (see GetTodoItems) */
	define('REGEX_TODO_END', '}[^\*]/'); 
	
	define('TICK_MARKER', 'X');
	
	class GlueyList {
		var $task_file;
		var $fh;
		
		function GlueyList($file) {
			//mb_regex_encoding('UTF-8');
			$this->task_file = $file;
			$this->fh = fopen($this->task_file, 'r+');
		}
		
		/**
		 * Get a GlueyItem list of items by level.
		 */
		function GetTodoItems($level, $start_offset=0, $end_offset=-1, $callback=null) {
			//list active top level items with pointers
			$regex = REGEX_TODO_START . $level . ',' . $level . REGEX_TODO_END;
			
			return $this->GetSection($regex, $start_offset, $end_offset, $callback);
		}
		
		function GetSection($regex, $start_offset=0, $end_offset=-1, $callback=null) {
			$items_array = array();

			$gluey_item = null;

			fseek($this->fh, $start_offset);
			
			while( $text_line = fgets($this->fh) ) {
				if ( (ftell($this->fh) >= $end_offset) && $end_offset != -1 ) {
					//we've passed the offset - probably by a whole line of text -
					//so backup the length of the text and quit
					fseek($this->fh, ftell($this->fh) - strlen($text_line));
					if ( $callback != null) {
						//...
						//for appending text?
						//
					}
					break;
				}
				
				//if ( mb_ereg_match($regex, $text_line) ) {
				if ( preg_match($regex, $text_line) ) {
					$current_pos = ftell($this->fh);
					$text_len = strlen($text_line);

					//first passs
					if ( $gluey_item == null ) {
						$gluey_item = new GlueyItem();
						$gluey_item->start_offset = $current_pos - $text_len;
						$gluey_item->title = $text_line;
					} else {
						//other passes
						$gluey_item->end_offset = ($current_pos - $text_len)+1; //+1 for newline
						array_push($items_array, $gluey_item);

						$gluey_item = new GlueyItem();
						$gluey_item->start_offset = $current_pos - $text_len;
						$gluey_item->title = $text_line;
					}
				}
			}
			//push the last gluey note on the array
			//TODO this causes a bug on context listing where it makes the last context
			//in the list duplicated. Need to speical case it? Maybe not use this function?
			$gluey_item->end_offset = ftell($this->fh) + 1; //+1 for newline
			array_push($items_array, $gluey_item);

			return $items_array;
		}
		
		/**
		 * Get a GlueyItem list of items by tag (context)
		 */
		function GetItemByContext($tag) {
			$regex = '/^\*.*'.$tag.'.*/';
			return $this->GetSection($regex);
		}
		
		/**
		 * Get an array of all the contexts defined in the file
		 */
		function GetAllContexts() {
			fseek($this->fh, 0);
			$context_array = array();
			
			while( $text_line = fgets($this->fh) ) {
				$tmp_array = array();
				preg_match(REGEX_CONTEXT, $text_line, $tmp_array);
				if ( !empty($tmp_array[0]) ) {
					if ( !in_array($tmp_array[0], $context_array) ) {
						array_push($context_array, $tmp_array[0]);
					}
				}
			}

			return $context_array;
		}
		
		/**
		 * Get the text information about an item
		 */
		function GetItemText($start_offset=0, $end_offset=0) {
			$items_array = array();

			fseek($this->fh, $start_offset);
			//get the first line (should be the action item the
			//text is referring to
			array_push($items_array, fgets($this->fh));
			
			//now get anymore text that they have, and bail out
			//if we go past the end offset or hit the next list item
			while( $text_line = fgets($this->fh) ) {
				//if ( ftell($this->fh) >= $end_offset || preg_match('/^[\*X]/', $text_line) ) {
				if ( ftell($this->fh) >= $end_offset || preg_match(REGEX_ITEM, $text_line) ) {
					break;
				}
				
				//skip comment lines
				//if ( preg_match('/^[#]/', $text_line) ) {
				if ( preg_match(REGEX_COMMENT, $text_line) ) {
					continue;
				}
				
				array_push($items_array, $text_line);
			}
			
			return $items_array;
		}
		
		function TickItem($start_offset=0, $end_offset=0) {
			fseek($this->fh, $start_offset);
			fwrite($this->fh, TICK_MARKER);
		}
		
		function Close() {
			close($this->fh);
		}
	}
?>
