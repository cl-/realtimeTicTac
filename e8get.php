<?php
	//*****************Required for all html files*************//
		require "config/config.inc";
		include("databaseclass.php");

		$db = new database();     //Instentiate the class
		$db->dbconnect($hostname_logon, $hostport_logon, $database_logon, $username_logon, $password_logon);        //Connect to the database

		$db->setupPreparedStatements();
	//*****************Required for all html files*************//	
	
	//$obj = json_decode($_REQUEST['worldstate']);
		$item = "";

		if(isset($_REQUEST['opcode'])){
			if($_REQUEST['opcode'] == "getworldlist"){
				$item = $db->getWorldList();
			}else if($_REQUEST['opcode'] == "newworld"){
				$db->setupNewWorld($_REQUEST['requestedworld']);

			}

			
		}else{
			if(isset($_REQUEST['requestedworld'])){
				$item = $db->getWorldState($_REQUEST['requestedworld']);			
			}
			
		}


	
	
	
	//echo((($obj->x1->x)));
	//echo(var_dump($obj));
	echo(json_encode($item));
	
?>