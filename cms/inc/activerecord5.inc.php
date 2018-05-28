<?php

	/* ActiveRecord v5 */
	/* SecureMotion */
	/* Norbert van Adrichem */

	class ActiveRecord {

		var $mysqlConnection = "";
		var $table = "";
		var $lastQuery = "";
		
		var $activeRecord = "";
		var $mysqlResult = "";
		var $originalValues = ""; // Om te kunnen vergelijken met de geupdate waarden.
		var $exceptionThrow = true; // Debug verandering

		/* Classspecific functions */
	
		function __construct($table) {
			$_SESSION['debugActiveRecord'] .= "<b>Created ActiveRecord Instance: ".$table."</b><br />";
		
			$this->table = $table;
			$this->mysqlConnection = mysql_connect(motioncms3_dbconf_server, motioncms3_dbconf_username, motioncms3_dbconf_password);
			mysql_select_db(motioncms3_dbconf_db, $this->mysqlConnection);
			// Pak de kolommen
			$this->lastQuery = "SHOW COLUMNS FROM ".$table;
			mysql_query("SET NAMES 'utf8'", $this->mysqlConnection);
			mysql_query('SET CHARACTER SET utf8', $this->mysqlConnection);
			$kolomFetch = mysql_query($this->lastQuery, $this->mysqlConnection) or $this->throwError("__construct");
			while ($createARArray = mysql_fetch_array($kolomFetch, MYSQL_ASSOC)) {
			
				$this->activeRecord[$createARArray['Field']] = "";
			
			}
			$_SESSION['debugActiveRecord'] .= "Query: ".$this->lastQuery."<br /><br />";
			$_SESSION['debugActiveRecordCount'] += 1;
			
			
		}
		
		function __set($key, $value) {
		
			$this->activeRecord[$key] = $value;
			$_SESSION['debugActiveRecord'] .= "Set: ".$key." > ".$value."<br /><br />";
			$_SESSION['debugActiveRecordCount'] += 1;
				
	    }

		function __get($key) {
		 
			return $this->activeRecord[$key];
		 
		}
		
		public function throwDebug($die = false) {
		
			$this->throwError("debug", $die);
		
		}
		
		private function throwError($functionName, $die = true) {
		
			if ($_SERVER['REMOTE_ADDR'] != "::1") {
			
				//header("Location: ".base_path_rewrite);
				//die();
			
			} 
			
			if (conf_veo_production == 0) {
			
				$dieError  = "<fieldset>";
				$dieError .= "<legend><strong>ActiveRecord 5 Foutmelding</strong> <i>in functie ActiveRecord::".$functionName."</i></legend>";
				$dieError .= "De laatst (gelogde) query die uitgevoerd is:<br />";
				$dieError .= $this->lastQuery;
				$dieError .= "<br /><br />";
				$dieError .= "MySQL heeft de volgende error teruggegeven:<br /><br />";
				$dieError .= mysql_error();
				$dieError .="</fieldset>";

			} else {
			
				$dieError  = "<fieldset>";
				$dieError .= "<legend><strong>Foutmelding</strong></legend>";
				$dieError .= "Er is een fout opgetreden tijdens het uitvoeren van de webapplicatie.<br />";
				$dieError .="</fieldset>";
				
			}
			
			if ($die == true) {
				
				if ($this->exceptionThrow == true) {
					throw new Exception($dieError);
				} else {
					die($dieError);
				}
			} else {
				echo($dieError);
			}

		}
			
		/* Functie van AR */
		
		function returnComplete($returnWithIDasIndex = false) {
		
			if ($returnWithIDasIndex == true) {
				
				for ($loopRecords = 0; $loopRecords < $this->returnRows(); $loopRecords++) {
				
					$returnArray[$this->activeRecord['id']] = $this->activeRecord;
					
					$this->volgende();
				
				}
				
				if ($loopRecords == 0) {
				
					$returnArray = false;
				
				}
				
			} else { 
		
				for ($loopRecords = 0; $loopRecords < $this->returnRows(); $loopRecords++) {
				
					$returnArray[$loopRecords] = $this->activeRecord;
					$this->volgende();
				
				}
				
				if ($loopRecords == 0) {
				
					$returnArray = false;
				
				}
				
			}
			
			return $returnArray;
		
		}
		
		function waar($whereQuery) {
		
			// Bepaalde records pakken.
			$this->lastQuery = "SELECT * FROM ".$this->table." WHERE ".$whereQuery;
			$this->mysqlResult = mysql_query($this->lastQuery) or $this->throwError("waar");
			$this->volgende();
			$_SESSION['debugActiveRecord'] .= "Query: ".$this->lastQuery."<br /><br />";
			$_SESSION['debugActiveRecordCount'] += 1;
			
		}
		
		function emptyValues() {
			
			$this->activeRecord = "";
		
			$this->lastQuery = "SHOW COLUMNS FROM ".$this->table;
			$kolomFetch = mysql_query($this->lastQuery, $this->mysqlConnection) or $this->throwError("emptyValues");
			while ($createARArray = mysql_fetch_array($kolomFetch, MYSQL_ASSOC)) {
			
				$this->activeRecord[$createARArray['Field']] = "";
			
			}
		
		}
		
		function query($query) {
		
			$this->lastQuery = $query;
			// Voer deze specifieke query uit.
			$this->mysqlResult = mysql_query($this->lastQuery) or $this->throwError("query");
			$this->volgende();
			$_SESSION['debugActiveRecord'] .= "Query: ".$this->lastQuery."<br /><br />";
			$_SESSION['debugActiveRecordCount'] += 1;
			
		}
		
		function fetchAll($order = "") {
		
			if ($order == "") {
				$this->lastQuery = "SELECT * FROM ".$this->table;
			} else {
				$this->lastQuery = "SELECT * FROM ".$this->table." ORDER by ".$order;
			}
			// Alle records pakken.
			$this->mysqlResult = mysql_query($this->lastQuery) or $this->throwError("fetchAll");
			$this->volgende();
		
		}
		
		function volgende() {
		
			$this->activeRecord = mysql_fetch_array($this->mysqlResult, MYSQL_ASSOC);
			$this->originalValues = $this->activeRecord;
		
		}
		
		function update() {
		
			// Loop door alle kolommen, bouw een query op.
			$updateQuery = "UPDATE ".$this->table." SET ";
			
			$keysAR = array_keys($this->activeRecord);
			
			for ($loopUp = 0; $loopUp < count($this->activeRecord); $loopUp++) {
							
				$updateQuery .= "`".$keysAR[$loopUp] . "` = '".mysql_real_escape_string($this->activeRecord[$keysAR[$loopUp]])."', ";
				
			}
			
			$updateQuery = substr($updateQuery, 0, -2); // Haal de laatste komma weg.
			
			$updateQuery .= " WHERE ";
			
			// Fetch de primary key van de tabel.
			$priKeyFetch = mysql_query("SHOW INDEX FROM ".$this->table, $this->mysqlConnection);
			$arrayIndex = mysql_fetch_array($priKeyFetch, MYSQL_ASSOC);
			$kolomIndex = $arrayIndex['Column_name'];
			
			$updateQuery .= $kolomIndex." = '".$this->originalValues[$kolomIndex]."'";
			$this->lastQuery = $updateQuery;
			
			mysql_query($this->lastQuery, $this->mysqlConnection) or $this->throwError("update");
			$_SESSION['debugActiveRecord'] .= "Query: ".$this->lastQuery."<br /><br />";
			$_SESSION['debugActiveRecordCount'] += 1;
			
		}
		
		function insert() {
		
			$insertQuery = "INSERT INTO ".$this->table." (";
			$keysAR = array_keys($this->activeRecord);
			
			for ($columnLoop = 0; $columnLoop <= count($this->activeRecord); $columnLoop++) {
			
				if ($this->activeRecord[$keysAR[$columnLoop]] != "") {
				
					$insertQuery .= "`".$keysAR[$columnLoop]."`, ";
				
				}
			
			}
			
			$insertQuery = substr($insertQuery, 0, -2);
			$insertQuery .= ") VALUES (";
			
			for ($valuesLoop = 0; $valuesLoop <= count($this->activeRecord); $valuesLoop++) {
			
				if ($this->activeRecord[$keysAR[$valuesLoop]] != "") {
				
					$insertQuery .= "'".mysql_real_escape_string($this->activeRecord[$keysAR[$valuesLoop]])."', ";
				
				}
			}
			
			$insertQuery = substr($insertQuery, 0, -2);
			
			$insertQuery .= ")";
		
			$this->lastQuery = $insertQuery;
			mysql_query($this->lastQuery, $this->mysqlConnection) or $this->throwError("insert");
			$_SESSION['debugActiveRecord'] .= "Query: ".$this->lastQuery."<br /><br />";
			$_SESSION['debugActiveRecordCount'] += 1;
			
			return mysql_insert_id();
			
		}
		
		function returnRows() {
		
			return mysql_num_rows($this->mysqlResult);
		
		}
		
		function delete() {
		
			// Fetch de primary key van de tabel.
			$priKeyFetch = mysql_query("SHOW INDEX FROM ".$this->table, $this->mysqlConnection);
			$arrayIndex = mysql_fetch_array($priKeyFetch, MYSQL_ASSOC);
			$kolomIndex = $arrayIndex['Column_name'];
			
			// Delete het huidige record.
			
			$deleteQuery = "DELETE FROM ".$this->table." WHERE ".$kolomIndex." = '".$this->activeRecord[$kolomIndex]."'";
			$this->lastQuery = $deleteQuery;
			mysql_query($this->lastQuery, $this->mysqlConnection) or $this->throwError("delete");
		
			$_SESSION['debugActiveRecord'] .= "Query: ".$this->lastQuery."<br />";
			$_SESSION['debugActiveRecordCount'] += 1;
			
		}
		
		function autoFill($formArray, $autoSetDate = true, $skipID = true) {
		
			foreach($formArray as $itemKey => $item) {
			
				if ($itemKey != 'id' || $skipID == false) {
				
					if (!is_array($item)) {
					
						if (array_key_exists($itemKey, $this->activeRecord)) {
						
							if (is_date($item) == true && $autoSetDate == true) {
								$this->activeRecord[$itemKey] = mysqlDate($item);
							} else {
								$this->activeRecord[$itemKey] = $item;
							}
							
						}
					
					} else {
						
						// Checkbox afhandelen
						foreach ($item as $checkKey => $checkValue) {
						
							if (array_key_exists($itemKey, $this->activeRecord)) {
								
								$this->activeRecord[$itemKey] .= $checkValue.",";
							
							}
						
						}
					
					}
					
				}
			
			}
		
		}
	
	}