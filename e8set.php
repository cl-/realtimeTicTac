<?php
	//*****************Required for all html files*************//
		require "config/config.inc";
		include("databaseclass.php");

		$db = new database();     //Instentiate the class
		$db->dbconnect($hostname_logon, $hostport_logon, $database_logon, $username_logon, $password_logon);        //Connect to the database
	//*****************Required for all html files*************//	
	
	$obj = json_decode($_REQUEST['worldstate']);
	
	$db->setupPreparedStatements();

	if(isset($_REQUEST['requestedworld'])){
		foreach($obj as $key => $value){
			//Update this function below to this
			//$db->updateObjectState("$key", $value->x, $value->y, $timestamp, "default");
			//$db->updateObjectState("$key", $value->x, $value->y, $_REQUEST['requestedworld']);	
			$db->updateObjectState("$key", $value->x, $value->y, $_REQUEST['requestedworld'], $value->lastDate);			
		}

		//echo((($obj->x1->x)));
		//echo(var_dump($obj));
		echo(json_encode($obj));	
	}

	
	
?>

