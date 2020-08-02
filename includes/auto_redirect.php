<?php
if(!SessionManager::isUserLoggedIn()){
	header('Location: login.php');
	exit();

}

?>