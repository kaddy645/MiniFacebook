<?php
session_start();

class SessionManager {

	public static function logout(){
		session_destroy();
	}


	/*
	* Params
	*  - username: String, name of the user
	*  - role: Integer, 0: User, 1: Admin
	*/

	public static function login($username,$userId,$role,$fullname=""){

		//Regenerate Session to avoid Session Fixation
		session_regenerate_id();


		$_SESSION['logged'] = true;
		$_SESSION['role'] = $role;
		$_SESSION['username'] = $username;
		$_SESSION['userId'] = $userId;
		$_SESSION['fullname'] = $fullname;

	}

	public static function debugSessionInfo(){
		// print_r($_SESSION);

	}

	public static function generateCRSFToken(){
		$rand = bin2hex(openssl_random_pseudo_bytes(16));
		$_SESSION["nocsrftoken"] = $rand;
		return $rand;
	}

	public function validateCRSFToken($token){
		

		if($_SESSION["nocsrftoken"] ==$token ){

			unset($_SESSION["nocsrftoken"]);
			return true;


		}
		return false;

	}



	public static function isUserLoggedIn(){
		if(isset($_SESSION['logged']) && $_SESSION['logged'] ){
			return true;
		}

		return false;
	}

	public static function getUserName(){
		if(SessionManager::isUserLoggedIn()){
			return $_SESSION['username'];
		}

		return "Guest";
		
	 
	}

	public static function getFullname(){
		if(SessionManager::isUserLoggedIn()){
			return $_SESSION['fullname'];
		}

		return "Guest";
		
	 
	}

	public static function getUserId(){
		if(SessionManager::isUserLoggedIn()){
			return $_SESSION['userId'];
		}

		return 0;
		
	 
	}

	//Check if User is an Admin
	public static function isAdmin(){
		if(SessionManager::isUserLoggedIn() &&  $_SESSION['role'] == 1){
			return true;

		}

		return false;

	}


}


?>