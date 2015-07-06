<?php
	//session_save_path("sess");
	//session_start();
	
	class database{

		//table fields for table appuser
		var $state_table = 'worldstate'; //table name
		var $state_object = 'objectname';
		var $state_xpos = 'xpos';
		var $state_ypos = 'ypos';
		var $state_world ='world';
		var $state_mtimestamp = 'mtimestamp';
		var $state_lastDateModified = 'lastdatemodified';
		//Primary key is the world and object
		


		var $dbconn = '';
		
		//connect to database
		function dbconnect($aHostname_logon, $aHostport_logon, $aDatabase_logon, $username_logon, $aPassword_logon){
			
			$connectionString = "host=$aHostname_logon port=$aHostport_logon dbname=$aDatabase_logon user=$username_logon password=$aPassword_logon";
			$this->dbconn = pg_connect($connectionString) or die ('Unable to connect to the database');
			
			return;
		}
		function login($aUsername, $aPassword){
			
			if(($this->dbconn)  != ''){
				//echo('logging in');
				$login_query="SELECT * FROM $this->user_table where $this->user_column = $1 AND $this->pass_column = $2;";
				$result = pg_prepare($this->dbconn, "my_query", $login_query);
				$result = pg_execute($this->dbconn, "my_query", array($aUsername,$aPassword));
				
				if(pg_num_rows($result)==0 || !($result)){
					//echo "no result";
					return false;
				}else{
					$row = pg_fetch_array($result);
					$_SESSION['databaseclass_isloggedin']=$row[userpasswords];
					$_SESSION['databaseclass_loggedinUsername']=$row[usernames];
					
					return true;
				}
			}
			else{
				//echo ("Cannot log in");
				return false;
			}
		}
		function logout(){
			$_SESSION['databaseclass_isloggedin'] = "";
			$_SESSION['databaseclass_loggedinUsername']="";
		}
		
		function logincheck($aLogincode){
		
			if(($this->dbconn)  != ''){
				$login_query="SELECT * FROM $this->user_table where $this->pass_column = $1;";
				$result = pg_prepare($this->dbconn, "my_query", $login_query);
				$result = pg_execute($this->dbconn, "my_query", array($aLogincode));
				if(pg_num_rows($result)==0 || !($result)){
					//echo "no result";
					return false;
				}else{
					//is logged on
					return true;
				}
				
			}
			return false;
		}

		function register($aUsername, $aPassword, $aBirthday, $aGender, $aAge){
			if(($this->dbconn)  != ''){
				$register_query="INSERT INTO $this->user_table ($this->user_column, $this->pass_column, $this->user_birthday, $this->user_gender, $this->user_age) values($1, $2, $3, $4, $5);";
				$result = pg_prepare($this->dbconn, "my_query", $register_query);
				$result = pg_execute($this->dbconn, "my_query", array($aUsername, $aPassword, $aBirthday, $aGender, $aAge));
				if($result){
					return true;
					
				} else {
					return false;
				}
			}
		}

		function setupPreparedStatements(){
			$updateStatement = "UPDATE $this->state_table SET $this->state_xpos = $1, $this->state_ypos = $2, $this->state_mtimestamp = $3 WHERE $this->state_world = $4 AND $this->state_object=$5;";
			$result = pg_prepare($this->dbconn, "update_query", $updateStatement);
			$select_query="SELECT * FROM $this->state_table WHERE $this->state_object=$1 AND $this->state_world=$2;";
			$result = pg_prepare($this->dbconn, "select_query", $select_query);
			$select_query="SELECT * FROM $this->state_table WHERE $this->state_world=$1;";
			$result = pg_prepare($this->dbconn, "select_world_query", $select_query);
			$select_query="SELECT DISTINCT $this->state_world FROM $this->state_table;";
			$result = pg_prepare($this->dbconn, "select_worldlist_query", $select_query);
			$select_query="INSERT INTO $this->state_table ($this->state_object, $this->state_xpos, $this->state_ypos, $this->state_world, $this->state_mtimestamp) values($1, $2, $3, $4, $5);";
			$result = pg_prepare($this->dbconn, "setup_new_world", $select_query);
		}
		function setupNewWorld($aWorld){
				if(($this->dbconn)  != ''){
					$left = 30;
					$top = 100;
					$itemsToInsert = array("x1", "x2", "x3", "x4", "x5");
					foreach ($itemsToInsert as $value) {
						$result = pg_execute($this->dbconn, "setup_new_world", array($value, $left, $top, $aWorld, '0'));	
						$top = $top + 60;
					}

					$itemsToInsert = array("o1", "o2", "o3", "o4", "o5");
					foreach ($itemsToInsert as $value) {
						$result = pg_execute($this->dbconn, "setup_new_world", array($value, $left, $top, $aWorld, '0'));	
						$top = $top + 60;
					}

					$itemsToInsert = array("tttboard");
					foreach ($itemsToInsert as $value) {
						$result = pg_execute($this->dbconn, "setup_new_world", array($value, 300, 200, $aWorld, '0'));	
						$top = $top + 60;
					}


					if(!$result){
						return false;
					}else{
						return true;
					}
				} else {
					return false;
				}


		}
		function updateObjectState($aObjectName, $xPos, $yPos, $aWorld, $aTimeStamp){
							
				if(($this->dbconn)  != ''){
					$result = pg_execute($this->dbconn, "update_query", array($xPos, $yPos, $aTimeStamp, $aWorld, $aObjectName));
					if(!$result){
						return false;
					}else{
						return true;
					}
				} else {
					return false;
				}
		}

		function getObjectState($aObjectName, $aWorld){
			if(($this->dbconn)  != ''){

					$result = pg_execute($this->dbconn, "select_query", array($aObjectName, $aWorld));
					if(!$result){
						return pg_last_error($this->dbconn);
						return false;
					}else{
						$arr = pg_fetch_array($result);
						return $arr;
					}
				} else {
					return false;
				}
		}
		function getWorldList(){
			if(($this->dbconn)  != ''){

					$result = pg_execute($this->dbconn, "select_worldlist_query", array());
					if(!$result){
						return pg_last_error($this->dbconn);
						return false;
					}else{
						$arr = pg_fetch_all($result);
						return $arr;
					}
				} else {
					return false;
				}	
		}

		function getWorldState($aWorld){
					if(($this->dbconn)  != ''){

							$result = pg_execute($this->dbconn, "select_world_query", array($aWorld));
							if(!$result){
								return pg_last_error($this->dbconn);
								return false;
							}else{
								$arr = pg_fetch_all($result);
								return $arr;
							}
						} else {
							return false;
						}
		}

		function getTasklist(){
			if(isset($_SESSION['databaseclass_loggedinUsername']) && $_SESSION['databaseclass_loggedinUsername']!=""){
				$username = $_SESSION['databaseclass_loggedinUsername'];
				
				if(($this->dbconn)  != ''){
					$select_query="SELECT * from $this->task_table_name where $this->task_username_column = $1 order by $this->task_dateCreated_column;";
					$result = pg_prepare($this->dbconn, "my_query2", $select_query);
					$result = pg_execute($this->dbconn, "my_query2", array($username));
					
					echo($aSortOrder);
					$taskList = array();
					if(!$result){
						return pg_last_error($this->dbconn);
					}else{	
					
						while ($row = pg_fetch_array($result)) {
							// Pull out individual columns from the current row
							$taskList[] = array($row[$this->task_name_column] , $row[$this->task_totalWorkUnits_column],$row[$this->task_progressedUnits_column],$row[$this->task_username_column],$row[$this->task_dateCreated_column]);
							
						}
						return $taskList;
					}
				}
				
			}
		}
	function getTask($aTaskName){
		if(isset($_SESSION['databaseclass_loggedinUsername']) && $_SESSION['databaseclass_loggedinUsername']!=""){
			$username = $_SESSION['databaseclass_loggedinUsername'];

			if(($this->dbconn)  != ''){
				$select_query="SELECT * from $this->task_table_name where $this->task_username_column = $1 AND $this->task_name_column = $2;";
				$result = pg_prepare($this->dbconn, "my_query2", $select_query);
				$result = pg_execute($this->dbconn, "my_query2", array($username, $aTaskName));
				
				$taskList = "";
				if(!$result){
					return pg_last_error($this->dbconn);
				}else{	
				
					while ($row = pg_fetch_array($result)) {
						// Pull out individual columns from the current row
						$taskList = array($row[$this->task_name_column] , $row[$this->task_totalWorkUnits_column],$row[$this->task_progressedUnits_column],$row[$this->task_username_column],$row[$this->task_dateCreated_column]);
						
					}
					return $taskList;
				}
			}
		}
	}
	function deleteTask($aTaskName){
		if(isset($_SESSION['databaseclass_loggedinUsername']) && $_SESSION['databaseclass_loggedinUsername']!=""){
			$username = $_SESSION['databaseclass_loggedinUsername'];

			if(($this->dbconn)  != ''){
				$delete_query="DELETE from $this->task_table_name where $this->task_username_column = $1 AND $this->task_name_column =$2;";
				$result = pg_prepare($this->dbconn, "my_query3", $delete_query);
				$result = pg_execute($this->dbconn, "my_query3", array($username, $aTaskName));
				$rows_affected=pg_affected_rows($result);
				//echo("rows_affected=$rows_affected");
				if(!$result){
					return false;
				}else{	
					return true;
				}
			}
		}
	}
	function modifyTask($aOldTaskName, $aNewTaskName, $taskTotalTimeSlots, $taskProgressedTimeSlots){
		
		if(isset($_SESSION['databaseclass_loggedinUsername']) && $_SESSION['databaseclass_loggedinUsername']!=""){
			$username = $_SESSION['databaseclass_loggedinUsername'];

			if(($this->dbconn)  != ''){
				$update_query="update $this->task_table_name SET $this->task_name_column =$1, $this->task_totalWorkUnits_column = $2, $this->task_progressedUnits_column = $3 where $this->task_username_column = $4 AND $this->task_name_column =$5;";
				$result = pg_prepare($this->dbconn, "my_query4", $update_query);
				$result = pg_execute($this->dbconn, "my_query4", array($aNewTaskName, $taskTotalTimeSlots, $taskProgressedTimeSlots, $username, $aOldTaskName));

				if(!$result){
					return false;
				}else{	
					return true;
				}
			}
		}
	}
	
	function incrementProgress($aTaskName){
		if(isset($_SESSION['databaseclass_loggedinUsername']) && $_SESSION['databaseclass_loggedinUsername']!=""){
			$username = $_SESSION['databaseclass_loggedinUsername'];

			if(($this->dbconn)  != ''){
				$update_query="update $this->task_table_name SET $this->task_progressedUnits_column = $this->task_progressedUnits_column+1
				 where $this->task_username_column = $1 AND $this->task_name_column =$2;";
				$result = pg_prepare($this->dbconn, "my_query5", $update_query);
				$result = pg_execute($this->dbconn, "my_query5", array($username, $aTaskName));
				echo( pg_last_error($this->dbconn));
				if(!$result){
					return false;
				}else{	
					return true;
				}
			}
		}
	}
	
	function getAccountInfo(){
		if(isset($_SESSION['databaseclass_loggedinUsername']) && $_SESSION['databaseclass_loggedinUsername']!=""){
			$username = $_SESSION['databaseclass_loggedinUsername'];

			if(($this->dbconn)  != ''){
				$select_query="SELECT * FROM $this->user_table WHERE $this->user_column = $1;";

				$result = pg_prepare($this->dbconn, "my_query6", $select_query);
				$result = pg_execute($this->dbconn, "my_query6", array($username));
				$userAccount = "";
				if(!$result){
					return pg_last_error($this->dbconn);
				}else{	
					while ($row = pg_fetch_array($result)) {
						// Pull out individual columns from the current row
						$userAccount = array($row[$this->user_column] , $row[$this->pass_column],$row[$this->user_gender],$row[$this->user_age],$row[$this->user_birthday]);
					}
					return $userAccount;
				}
			}
		}
	}
	
	function updateAccountInfo($aBirthday, $aGender, $aAge, $aPassword){
	

		if(isset($_SESSION['databaseclass_loggedinUsername']) && $_SESSION['databaseclass_loggedinUsername']!=""){
			$username = $_SESSION['databaseclass_loggedinUsername'];

			if(($this->dbconn)  != ''){
				$update_query="UPDATE $this->user_table SET $this->pass_column = $1, $this->user_birthday = $2, $this->user_gender = $3, $this->user_age = $4 WHERE $this->user_column = $5;";
				
				$result = pg_prepare($this->dbconn, "my_query1", $update_query);
				$result = pg_execute($this->dbconn, "my_query1", array($aPassword, $aBirthday, $aGender, $aAge, $username));
				if(!$result){
					return false;
				}else{	
					return true;
				}
			}
		}
	}
	
		
		
	}

?>