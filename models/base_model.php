<?php
class BaseModel {

	private $servername = "localhost";
	private $username = "secad_project";
	private $password = "secad_project";
	private $dbname = "secad_project";


	function __construct(){
		try{
			$this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";

		}

	}

	function __destruct(){

		try{
			$this->conn->close();
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";

		}
	}
}



?>