<?php

	/* ActiveRecord v6 PDO */
	/* SecureMotion */
	/* Norbert van Adrichem */

	class ActiveRecord {

		var $mysqlConnection = "";
		var $table = "";
		var $lastQuery = "";

		var $activeRecord = "";
		var $mysqlResult = "";
		var $originalValues = ""; // Om te kunnen vergelijken met de geupdate waarden.

        var $internalPointer = 0;
        var $numRows = 0;

		/* Classspecific functions */

		function __construct($table) {

			$this->table = $table;
            $this->mysqlConnection = new PDO("mysql:host=".motioncms3_dbconf_server.";dbname=".motioncms3_dbconf_db.";charset=utf8", motioncms3_dbconf_username, motioncms3_dbconf_password);

			// Pak de kolommen
			$this->lastQuery = "SHOW COLUMNS FROM ".$table;
            $kolomStmt = $this->mysqlConnection->prepare($this->lastQuery);
            $kolomStmt->execute();
			foreach ($kolomStmt as $createARArray) {

				$this->activeRecord[$createARArray['Field']] = "";

			}

		}

		function __set($key, $value) {

			$this->activeRecord[$key] = $value;

	    }

		function __get($key) {

			return $this->activeRecord[$key];

		}

		public function throwDebug($die = false) {

			$this->throwError("debug", $die);

		}

		private function throwError($functionName, $die = true) {

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
				die($dieError);
			} else {
				echo($dieError);
			}

		}

		/* Functies voor AR */

		function returnComplete($returnWithIDasIndex = true) {

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

        function setMetadata($statement) {

            $this->numRows = $statement->rowCount();
            $this->internalPointer = 0;
            $this->activeRecord = $this->mysqlResult[$this->internalPointer];
            $this->originalValues = $this->activeRecord;

        }

		function waar($whereQuery, $valuesArray = array()) {

			// Bepaalde records pakken.
			$this->lastQuery = "SELECT * FROM ".$this->table." WHERE ".$whereQuery;
            $whereStmt = $this->mysqlConnection->prepare($this->lastQuery);
            $mysqlResult = $whereStmt->execute($valuesArray);
            $this->mysqlResult = $whereStmt->fetchAll(PDO::FETCH_ASSOC);
            $this->setMetadata($whereStmt);

		}

		function emptyValues() {

			$this->activeRecord = "";
            $this->numRows = 0;
            $this->internalPointer = 0;

            $this->lastQuery = "SHOW COLUMNS FROM ".$table;
            $kolomStmt = $this->mysqlConnection->prepare($this->lastQuery);
            $kolomStmt->execute();
			foreach ($kolomStmt as $createARArray) {

				$this->activeRecord[$createARArray['Field']] = "";

			}

		}

		function query($query) {

			$this->lastQuery = $query;
			// Voer deze specifieke query uit.
            $queryStmt = $this->mysqlConnection->query($this->lastQuery);
            $this->mysqlResult = $queryStmt->fetchAll(PDO::FETCH_ASSOC);
            $this->setMetadata($queryStmt);

		}

		function fetchAll($order = "") {

			if ($order == "") {
				$this->lastQuery = "SELECT * FROM ".$this->table;
			} else {
				$this->lastQuery = "SELECT * FROM ".$this->table." ORDER by ".$order;
			}
			// Alle records pakken.
            $whereStmt = $this->mysqlConnection->prepare($this->lastQuery);
            $mysqlResult = $whereStmt->execute($valuesArray);
            $this->mysqlResult = $whereStmt->fetchAll(PDO::FETCH_ASSOC);
            $this->setMetadata($whereStmt);

		}

		function volgende() {

            $this->internalPointer = $this->internalPointer + 1;
            $this->activeRecord = $this->mysqlResult[$this->internalPointer];
			$this->originalValues = $this->activeRecord;

		}

		function update() {

			// Loop door alle kolommen, bouw een query op.
			$updateQuery = "UPDATE ".$this->table." SET ";

			$keysAR = array_keys($this->activeRecord);

			for ($loopUp = 0; $loopUp < count($this->activeRecord); $loopUp++) {

                $updateQuery .= $keysAR[$loopUp] . " = ?, ";
                $paramArray[] = $this->activeRecord[$keysAR[$loopUp]];

			}

			$updateQuery = substr($updateQuery, 0, -2); // Haal de laatste komma weg.

			$updateQuery .= " WHERE ";

			// Fetch de primary key van de tabel.
            $fetchPrimary = $this->mysqlConnection->query("SHOW INDEX FROM ".$this->table);
            $arrayIndex = $fetchPrimary->fetch(PDO::FETCH_ASSOC);
            $kolomIndex = $arrayIndex['Column_name'];

            $updateQuery .= $kolomIndex." = ".$this->originalValues[$kolomIndex];

			$this->lastQuery = $updateQuery;

            $updateTable = $this->mysqlConnection->prepare($this->lastQuery);
            $updateTable->execute($paramArray);

		}

		function insert() {

			$insertQuery = "INSERT INTO ".$this->table." (";
			$keysAR = array_keys($this->activeRecord);

			for ($columnLoop = 0; $columnLoop <= count($this->activeRecord); $columnLoop++) {

				if ($this->activeRecord[$keysAR[$columnLoop]] != "") {

					$insertQuery .= $keysAR[$columnLoop].", ";

				}

			}

			$insertQuery = substr($insertQuery, 0, -2);
			$insertQuery .= ") VALUES (";

			for ($valuesLoop = 0; $valuesLoop <= count($this->activeRecord); $valuesLoop++) {

				if ($this->activeRecord[$keysAR[$valuesLoop]] != "") {

                    $insertQuery .= "?, ";
                    $paramArray[] = $this->activeRecord[$keysAR[$valuesLoop]];

				}
			}

			$insertQuery = substr($insertQuery, 0, -2);

			$insertQuery .= ")";

			$this->lastQuery = $insertQuery;

            $insertTable = $this->mysqlConnection->prepare($this->lastQuery);
            $insertTable->execute($paramArray);
			return $this->mysqlConnection->lastInsertId();

		}

		function returnRows() {

			return $this->numRows;

		}

		function delete() {

			// Fetch de primary key van de tabel.
            $fetchPrimary = $this->mysqlConnection->query("SHOW INDEX FROM ".$this->table);
            $arrayIndex = $fetchPrimary->fetch(PDO::FETCH_ASSOC);
            $kolomIndex = $arrayIndex['Column_name'];

			// Delete het huidige record.

			$deleteQuery = "DELETE FROM ".$this->table." WHERE ".$kolomIndex." = ".$this->originalValues[$kolomIndex];
			$this->lastQuery = $deleteQuery;

            $deleteTable = $this->mysqlConnection->prepare($this->lastQuery);
            $deleteTable->execute();

		}

	}

