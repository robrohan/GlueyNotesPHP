<?php
	include_once('AppCore/Settings.php');
	include_once('AppCore/Utils.class.php');
	include_once('Model/DataBase/DataObject.class.php');
	
	define('SESSION_TIMEOUT', (60*60*24*7*4) );
	
	class CleanSession extends DataObject {
		function RunJob() {
			$qry="
				DELETE FROM session
				WHERE time BETWEEN '". date('Y-m-d',0) . "'
					AND '". date('Y-m-d',time()-SESSION_TIMEOUT) . "'
			";
			$results = $this->SetQuery($qry);
		}
	}
	
	$job = new CleanSession();
	$job->RunJob();
?>